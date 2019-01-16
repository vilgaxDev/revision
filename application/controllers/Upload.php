<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends MY_Controller {

	public function __construct(){
		//Check that the user is logged in
		parent::__construct();
		if(!$this->authentication->logged_in() == TRUE){
			redirect('/login');
		}
	}
	public function index()
	{
		$jsonMessages = array();

      //Start the upload process
      $upload_files = array();
      $file_count =  count($_FILES['files']['name']);
			$user_id = $this->authentication->uid;
			//Check if the parent directory exists
			$parent_public_key = $this->input->post('parent_public_key');
			if(!$this->DataModel->folderExists(array('public_key' => $parent_public_key), $this->authentication->uid) == TRUE){

				//Just uplaod it into the main directory
				$parent_id = 0;

				//Check if the folder is shared with the current user

				if($this->DataModel->folderExists(array('public_key' => $parent_public_key))){
					$folder = $this->DataModel->getFolderInfo(array('public_key' => $parent_public_key));

					$this->DataModel->hasSharedAccessFolder($folder['public_key'], $this->authentication->uid);
					if($this->DataModel->sharedPermission == 1){
						$parent_id = $folder['id'];
						$user_id = $folder['user_id'];
					}

				}
			}else{
				$parent_id = $this->DataModel->getFolderInfo(array('public_key' => $parent_public_key), $this->authentication->uid);
				$parent_id = $parent_id['id'];
			}

      //Before we save the file we need to do some final validations
      //********
      $maxFileSize = $this->SettingsModel->getSetting('file_maxSize');
      $storageEngine = $this->DataModel->storageEngine();
      foreach($_FILES as $file){
        $pass = true;
				if($file['tmp_name'] == ''){
					$pass = false;
				}
        //Check that we have an allowed file type;
        if($this->DataModel->validateFileType($file['type']) == FALSE){
          //Set an error message to display it to the user
          $jsonMessages['error'] = $this->lang->line('error_file_notuploaded');
          $pass = false;
        }

        //Mark the file ready for upload
				if($pass == TRUE){
					$stats = $this->usagestatistics->getUser($user_id);
					//Check if the user is allowed to upload more files
					if($stats['usage']['filecount'] >= $stats['max']['filecount']){
						if($stats['max']['filecount'] != -1){
							$pass = false;
							$jsonMessages['error'] = $this->lang->line('error_max_filecount');
						}
					}else{
						$stats['usage']['filecount']++;
					}

					//Check if the max storage is exceeded
					$max_storage = $stats['max']['total_filesize'];
					$used_storage = $stats['usage']['total_filesize']+$file['size'];

					if($used_storage > $max_storage && $max_storage !== 0){
						$pass = false;
						$jsonMessages['error'] = $this->lang->line('error_max_storage');
					}else{
						$stats['usage']['total_filesize'] += $file['size'];
					}

					//Check if the user has unlimited storage
					if($max_storage == 0){
						$pass = true;
						$stats['usage']['total_filesize'] += $file['size'];
					}
				}

        if($pass == TRUE){
          $upload_files[] = array(
            'name' => $file['name'],
            'tmp_name' => $file['tmp_name'],
            'size' => $file['size'],
            'type' => $file['type']
          );
        }

      }
      //Check if we have any files to upload
      if(count($upload_files) > 0){
				try{
					$jsonMessages['success'] = $this->lang->line('success_file_uploaded');
        //Ok we have files that we need to upload.
        //Connect to the storage engine
        require_once(APPPATH.'libraries/storage/'.$storageEngine['library_name'].'/init.php');
        $storage = new $storageEngine['library_name']();
        //Connect the storage engine to the storage server
        $storage->connect();

        //Start uploading the files
        foreach($upload_files as $file){
					$jsonMessages['name'] = $file['name'];
					$thumbnail = false;
          //Generate the storage name for the file
          $file['storage_name'] = $this->DataModel->generateStorageName();
					$jsonMessages['storage'] = $file['storage_name'];
					$jsonMessages['mime'] = $file['type'];

					#First store the file in the APPPATH/tmp folder so we can upload it to the storage engine and create the thumbnail
					move_uploaded_file($file['tmp_name'], APPPATH.'tmp/'.$file['storage_name']);

					//Check if we have an image of some sort
					if($file['type'] == 'image/jpeg' || $file['type'] == 'image/png' || $file['type'] == 'image/gif'){
						//Create the thumbnail of the tmp file and also save it into the tmp folder
						if($this->create_thumb(APPPATH.'tmp/'.$file['storage_name'], APPPATH.'tmp/thumb_'.$file['storage_name'], $file['type']) == TRUE){
							$thumbnail = true;
						}else{
							$thumbnail = false;
						}
					//If its a PDF document
				}elseif($file['type'] == 'application/pdf'){
					//Create the thumbnail of the tmp file and save it again

					if(class_exists('Imagick')){
					if($this->create_pdf_thumb(APPPATH.'tmp/'.$file['storage_name'], APPPATH.'tmp/thumb_'.$file['storage_name'], $file['type']) == TRUE){
						$thumbnail = true;
					}else{
						$thumbnail = false;
					}
				}else{
					$thumbnail = false;
				}
				}

					if($thumbnail == true){
						//Thumbnail created successfuly
						//Upload the thumbnail to the storage engine too
						$jsonMessages['thumbnail'] = true;
						$jsonMessages['thumbnail_data'] = base64_encode(file_get_contents(APPPATH.'tmp/thumb_'.$file['storage_name']));

						$storage->upload(array('tmp_name' => APPPATH.'tmp/thumb_'.$file['storage_name'], 'storage_name' => 'thumb_'.$file['storage_name'], 'type' => 'image/png'));
						unlink(APPPATH.'tmp/thumb_'.$file['storage_name']);
					}

          //Upload the actual file to the storage server
          $storage->upload(array('tmp_name' => APPPATH.'tmp/'.$file['storage_name'], 'storage_name' => $file['storage_name'], 'type' => $file['type']));
					//Delete the file from the tmp directory
					unlink(APPPATH.'tmp/'.$file['storage_name']);

          //Insert the data in the database
          $file['storage_engine'] = $storageEngine['id'];
          $this->DataModel->save($file, $thumbnail, $user_id, $parent_id);

        }
				if($user_id == $this->authentication->uid){
					if($parent_id > 0){
						//redirect('/folders/'.$parent_public_key);
					}else{
						//redirect('/dashboard');
					}

				}else{
					//redirect('sharedFolder/'.$parent_public_key);
				}
			}catch(Exception $e){
				$jsonMessages['error'] = 'Error while uploading files';
				$this->save_messages();
			}
			}
			//redirect('dashboard');
			echo json_encode($jsonMessages);
	}

	private function create_thumb($src, $dest, $type) {
			try{
				/* read the source image */
				if($type == 'image/jpeg'){
					$source_image = imagecreatefromjpeg($src);
				}elseif($type == 'image/png'){
					$source_image = imagecreatefrompng($src);
				}elseif($type == 'image/gif'){
					$source_image = imagecreatefromgif($src);
				}else{
					return false;
				}
				$width = imagesx($source_image);
				$height = imagesy($source_image);

				/* find the "desired height" of this thumbnail, relative to the desired width  */

				/* create a new, "virtual" image */
				$virtual_image = imagecreatetruecolor(180, 100);
				$background = imagecolorallocate($virtual_image , 0, 0, 0);
				imagecolortransparent($virtual_image, $background);

				/* copy source image at a resized size */
				imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, 180, 100, $width, $height);

				/* create the physical thumbnail image to its destination */
				imagepng($virtual_image, $dest);
				return true;
			}catch(Exception $e){
				return false;
			}
	}

	private function create_pdf_thumb($src, $dest, $type){
		try{
			$im = new imagick($src.'[0]');
			$im->setImageFormat('jpg');
			$im->setresolution(180, 100);

			file_put_contents($dest, $im);
			return true;

		}catch(Exception $e){
			return false;
		}
	}

}
