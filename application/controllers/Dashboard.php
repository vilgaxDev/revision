<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {
	public function __construct(){
		parent::__construct();
		if(!$this->authentication->logged_in() == TRUE){
      redirect('/');
    }
	}

	public function index()
	{
		$this->displayFolderContent();
	}

	public function displayFolderContent(){
		$this->template_data['mode'] = 'default';
		$public_id = $this->uri->segment(2);
		if($public_id == ''){
			$parent['id'] = 0;
			$parent['public_key'] = 0;
		}else{
			//Check if the folder exists
			if($this->DataModel->folderExists(array('public_key' => $public_id), $this->authentication->uid)){
				$parent = $this->DataModel->getFolderInfo(array('public_key' => $public_id), $this->authentication->uid);
			}else{
				redirect('/dashboard');
			}
		}

		//Get all files and folders for the current path
		$filesAfolders = $this->DataModel->getContent($parent['id'], $this->authentication->uid);

		$this->template_data['folder_content']['folders'] = $filesAfolders['folders'];
		$this->template_data['folder_content']['files'] = $filesAfolders['files'];

		//Now check for each file if there is a thumbnail available
		$this->getThumbnails();

		$this->template_data['parent_public_key'] = $parent['public_key'];
		$this->template_data['full_path'] = $this->DataModel->getFullFolderPath($parent['id']);

		$this->getMessages();
		$this->parser->parse('user/header_loggedin.php', $this->template_data);
		$this->parser->parse('user/dashboard.php', $this->template_data);
		$this->parser->parse('user/footer.php', $this->template_data);

	}

	//This has nothing to do with the premium feature of this product. It's basicly just getting all files that are marked with a star.
	public function displayPremiumContent(){
			$this->template_data['mode'] = 'marked';
			$parent['id'] = 0;
			$parent['public_key'] = 0;

		//Get all files and folders for the current path
		$filesAfolders = $this->DataModel->getPremiumContent($this->authentication->uid);

		$this->template_data['folder_content']['folders'] = $filesAfolders['folders'];
		$this->template_data['folder_content']['files'] = $filesAfolders['files'];

		$this->getThumbnails();

		$this->template_data['parent_public_key'] = $parent['public_key'];
		$this->template_data['full_path'] = $this->DataModel->getFullFolderPath($parent['id']);

		$this->getMessages();
		$this->parser->parse('user/header_loggedin.php', $this->template_data);
		$this->parser->parse('user/dashboard.php', $this->template_data);
		$this->parser->parse('user/footer.php', $this->template_data);
	}

	public function displayTrashCanContent(){
			$this->template_data['mode'] = 'trashcan';
			$parent['id'] = 0;
			$parent['public_key'] = 0;

		//Get all files and folders for the current path
		$filesAfolders = $this->DataModel->getTrashCanContent($this->authentication->uid);

		$this->template_data['folder_content']['folders'] = $filesAfolders['folders'];
		$this->template_data['folder_content']['files'] = $filesAfolders['files'];

		//Now check for each file if there is a thumbnail available
		$this->getThumbnails();

		$this->template_data['parent_public_key'] = $parent['public_key'];
		$this->template_data['full_path'] = $this->DataModel->getFullFolderPath($parent['id']);

		$this->getMessages();
		$this->parser->parse('user/header_loggedin.php', $this->template_data);
		$this->parser->parse('user/trashcan.php', $this->template_data);
		$this->parser->parse('user/footer.php', $this->template_data);
	}

	public function displaySharedFiles(){
		$public_key = $this->uri->segment(2);
		$parent['id'] = 0;
		$parent['public_key'] = 0;
		$this->template_data['mode'] = 'shared';

		if(!$public_key == ''){
			//Check that the folder exists
			if(!$this->DataModel->folderExists(array('public_key' => $public_key))){
				redirect('shared');
			}
			$folder = $this->DataModel->getFolderInfo(array('public_key' => $public_key));
			$parent['id'] = $folder['id'];
			$parent['public_key'] = $folder['public_key'];

			//Check if the user has access to the folder
			if(!$this->DataModel->hasSharedAccessFolder($public_key, $this->authentication->uid)){
				redirect('shared');
			}

			$this->template_data['permission'] = $this->DataModel->sharedPermission;

			$filesAfolders = $this->DataModel->getContent($folder['id']);
			$this->template_data['folder_content']['folders'] = $filesAfolders['folders'];
			$this->template_data['folder_content']['files'] = $filesAfolders['files'];

		}else{
			//Get all shared files
			$this->template_data['permission'] = 0;
			$this->template_data['folder_content']['files'] = $this->DataModel->sharedAccessFiles($this->authentication->uid);
			$this->template_data['folder_content']['folders'] = $this->DataModel->sharedAccessFolders($this->authentication->uid);
		}

		$this->getThumbnails();

		$this->template_data['parent_public_key'] = $parent['public_key'];
		$this->template_data['full_path'] = $this->DataModel->getFullFolderPath($parent['id']);

		$this->getMessages();
		$this->parser->parse('user/header_loggedin.php', $this->template_data);
		$this->parser->parse('user/shared.php', $this->template_data);
		$this->parser->parse('user/footer.php', $this->template_data);
	}

	private function getThumbnails(){
		//Now check for each file if there is a thumbnail available
		foreach($this->template_data['folder_content']['files'] as $key => $file){
			if($file['thumbnail'] == True){
				//Add the file to an array so we only need to connect to each storage engine once
				$thumbnails[$file['storage_engine']][$key] = $file['storage_name'];
			}else{
				//Check if we have an image saved so we can display that instead
				switch($file['mime']){
					case "image/png":{
						$this->template_data['folder_content']['files'][$key]['thumbnail_default'] = true;
						$this->template_data['folder_content']['files'][$key]['thumbnail_source'] = base_url().'/public/file_previews/png.png';
						break;
					}
					case "image/jpeg":{
						$this->template_data['folder_content']['files'][$key]['thumbnail_default'] = true;
						$this->template_data['folder_content']['files'][$key]['thumbnail_source'] = base_url().'/public/file_previews/jpg.png';
						break;
					}
					case "image/gif":{
						$this->template_data['folder_content']['files'][$key]['thumbnail_default'] = true;
						$this->template_data['folder_content']['files'][$key]['thumbnail_source'] = base_url().'/public/file_previews/gif.png';
						break;
					}
					case "application/xhtml+xml":{
						$this->template_data['folder_content']['files'][$key]['thumbnail_default'] = true;
						$this->template_data['folder_content']['files'][$key]['thumbnail_source'] = base_url().'/public/file_previews/html.png';
						break;
					}
					case "audio/mp3":{
						$this->template_data['folder_content']['files'][$key]['thumbnail_default'] = true;
						$this->template_data['folder_content']['files'][$key]['thumbnail_source'] = base_url().'/public/file_previews/mp3.png';
						break;
					}
					case "video/mp4":{
						$this->template_data['folder_content']['files'][$key]['thumbnail_default'] = true;
						$this->template_data['folder_content']['files'][$key]['thumbnail_source'] = base_url().'/public/file_previews/mp4.png';
						break;
					}
					case "image/vnd.adobe.photoshop":{
						$this->template_data['folder_content']['files'][$key]['thumbnail_default'] = true;
						$this->template_data['folder_content']['files'][$key]['thumbnail_source'] = base_url().'/public/file_previews/psd.png';
						break;
					}
					case "application/octet-stream":{
						$this->template_data['folder_content']['files'][$key]['thumbnail_default'] = true;
						$this->template_data['folder_content']['files'][$key]['thumbnail_source'] = base_url().'/public/file_previews/txt.png';
						break;
					}
					case "application/pdf":{
						$this->template_data['folder_content']['files'][$key]['thumbnail_default'] = true;
						$this->template_data['folder_content']['files'][$key]['thumbnail_source'] = base_url().'/public/file_previews/pdf.png';
						break;
					}
					case "application/msword":{
						$this->template_data['folder_content']['files'][$key]['thumbnail_default'] = true;
						$this->template_data['folder_content']['files'][$key]['thumbnail_source'] = base_url().'/public/file_previews/doc.png';
						break;
					}
					case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":{
						$this->template_data['folder_content']['files'][$key]['thumbnail_default'] = true;
						$this->template_data['folder_content']['files'][$key]['thumbnail_source'] = base_url().'/public/file_previews/doc.png';
						break;
					}
					case "application/vnd.ms-excel":{
						$this->template_data['folder_content']['files'][$key]['thumbnail_default'] = true;
						$this->template_data['folder_content']['files'][$key]['thumbnail_source'] = base_url().'/public/file_previews/xls.png';
						break;
					}
					case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":{
						$this->template_data['folder_content']['files'][$key]['thumbnail_default'] = true;
						$this->template_data['folder_content']['files'][$key]['thumbnail_source'] = base_url().'/public/file_previews/xls.png';
						break;
					}
				}
			}
		}

		//Now loop through every storage engine to retrive the thumbnail
		if(isset($thumbnails)){
			foreach($thumbnails as $key => $sengine){
				try{
					$storageEngine = $this->DataModel->storageEngine($key);

					require_once(APPPATH.'libraries/storage/'.$storageEngine['library_name'].'/init.php');
					$storage = new $storageEngine['library_name']();
					//Connect the storage engine to the storage server
					$storage->connect();

					//Loop through all files for that storage engine
					foreach($sengine as $fkey => $file){
						$this->template_data['folder_content']['files'][$fkey]['thumbnail_source'] = $storage->download('thumb_'.$file);
						if(strlen($this->template_data['folder_content']['files'][$fkey]['thumbnail_source']) < 10){
							//No thumbnail downloaded
							$this->template_data['folder_content']['files'][$fkey]['thumbnail_source'] = false;
						}else{
							//Base64 Encode it for display
							$this->template_data['folder_content']['files'][$fkey]['thumbnail_source'] = base64_encode($this->template_data['folder_content']['files'][$fkey]['thumbnail_source']);
						}
					}
				}catch(Exception $e){
					//Maybe add error reporting for the admin cp here
				}
			}
		}
	}

}
