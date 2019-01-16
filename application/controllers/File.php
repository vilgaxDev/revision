<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File extends MY_Controller {
	public function __construct(){
		parent::__construct();
    if(!$this->authentication->logged_in() == TRUE){
      redirect('/');
    }
	}

	//This function creates a new folder, either in the home directory or in an already existing folder
	public function createFolder()
	{
    //Check if we have all variables that we need
    $public_key = $this->input->post('parent_public_key');
    $folder_name = $this->input->post('folder_name');
		$user_id = $this->authentication->uid;

    //TODO !!!!! Validate the path here !!!!!
    if($folder_name == '' || $public_key == ''){
			$this->errorMessage('Please enter a folder name');
      redirect('/dashboard');
    }

		//Variable to check if the folder exists
		$folder_exists = false;

		//Make sure that the parent exists
		if($public_key == '0'){
			//We're in the main directory
			$parent_id = 0;
			$folder_exists = true;
		}

		if($this->DataModel->folderExists(array('public_key' => $public_key), $this->authentication->uid)){
			//We found the parent folder
			$folder_exists = true;
			$parent_id = $this->DataModel->getFolderInfo(array('public_key' => $public_key), $this->authentication->uid)['id'];
		}

		//Check if the folder is shared with the user
		if($this->DataModel->folderExists(array('public_key' => $public_key))){
			$folder = $this->DataModel->getFolderInfo(array('public_key' => $public_key));
			if($this->DataModel->hasSharedAccessFolder($public_key, $this->authentication->uid)){
				if($this->DataModel->sharedPermission == 1){
					$folder_exists = true;
					//Set the user id of the user who owns the shared folder
					$user_id = $folder['user_id'];
					$parent_id = $folder['id'];
				}
			}
		}

		//Final check if the folder exists
		if($folder_exists == false){
			redirect("/dashboard");
		}

		//Check if the user is allowed to create new folders (limit)
		$stats = $this->usagestatistics->getUser($user_id);
		if($stats['usage']['foldercount'] < $stats['max']['foldercount'] || $stats['max']['foldercount'] == 0){
			//Create the folder
	    $this->DataModel->createFolder($parent_id, $folder_name, $user_id);
			$this->successMessage($this->lang->line('success_folder_created'));
		}else{
			//The folder could not be created.
			$this->errorMessage($this->lang->line('error_folder_usageLimit'));
		}

		//Redirect the user to the dasbhoard if the parent folder was the "main" folder
		if($parent_id == 0){
			redirect('/dashboard');
		}

		//If the owner of the folder is the current user
		if($this->authentication->uid == $user_id){
			redirect('folders/'.$public_key);
		}else{
			//If it's a shared folder
			redirect('sharedFolder/'.$public_key);
		}

	}

	//Function that is called with one parameter to delete a file
	public function deleteFile(){
		//Get the storage name
		$storage_name = $this->uri->segment(3);

		//Check if the file exists
		if(!$this->DataModel->fileExists($storage_name) == TRUE){
			//File doesn't exist
			redirect('dashboard');
		}

		//Check if the user has permission to delete the file
		if(!$this->DataModel->userPermission('edit', $storage_name, $this->authentication->uid)){
			//User doesn't has permission to edit / delete this file
			redirect('dashboard');
		}

		//Get information of file
		$file = $this->DataModel->fileInformation($storage_name);

		//Mark the file as deleted in the database so they get displayed in the trashcan
		$this->DataModel->fileIntoTrash($file['id']);
		$this->successMessage($this->lang->line('success_file_deleted'));

		//Check if the file was deleted by the original owner or by a user who has access
		if($this->authentication->uid == $file['user_id']){
			if($file['parent'] == 0 ){
				redirect('/dashboard');
			}else{
				//Get parent information
				$parent = $this->DataModel->getFolderInfo(array('id' => $file['parent']), $this->authentication->uid);
				redirect('folders/'.$parent['public_key']);
			}
		}else{
			$parent = $this->DataModel->getFolderInfo(array('id' => $file['parent']));
			redirect('sharedFolder/'.$parent['public_key']);
		}
	}

	public function deleteFilePerm(){
		//Get the storage name
		$storage_name = $this->uri->segment(3);

		//Check if the file exists
		if(!$this->DataModel->fileExists($storage_name) == TRUE){
			//File doesn't exist
			redirect('dashboard');
		}

		//Check if the user has permission to delete the file
		if(!$this->DataModel->userPermission('edit', $storage_name, $this->authentication->uid)){
			//User doesn't has permission to edit / delete this file
			redirect('dashboard');
		}

		//Get information of file
		$file = $this->DataModel->fileInformation($storage_name);
		//Make sure that that file is already trashed
		if($file['trash'] == 1){
			//Permanently delete the file
			$files = array ();
			$files[] = $file;
			$this->deleteFilesPermanently($files);

		}	else{
			redirect('/');
		}
	}

	public function moveFile(){
		$file_storage_name = $this->uri->segment(3);
		$folder_key = $this->uri->segment(4);
		$file_storage_name = str_replace('file-', '', $file_storage_name);
		$folder_key = str_replace('folder-', '', $folder_key);
		$folder_key = str_replace('path-', '', $folder_key);
		//Home direcotry
		if($folder_key !== '0home'){
			//Check if the file and folder exists
			if(!$this->DataModel->fileExists($file_storage_name) || !$this->DataModel->folderExists(array('public_key' => $folder_key))){
				redirect('/dashboard');
			}

			//Get file information
			$file = $this->DataModel->fileInformation($file_storage_name);
			//Get folder information
			$folder = $this->DataModel->getFolderInfo(array('public_key' => $folder_key));
			$old_folder = $this->DataModel->getFolderInfo(array('id' => $file['parent']));

			//Check if the folder and the file belongs to the current user
			if(!$file['user_id'] == $this->authentication->uid || !$folder['user_id'] == $this->authentication->uid){
				redirect('/dashboard');
			}

			//File and folder belongs to the user. Move the file now
			$this->DataModel->updateFile(array('id' => $file['id']), array('parent' => $folder['id']));
			if($file['parent'] == 0){
				redirect('/dashboard');
			}else{
				redirect('folders/'.$old_folder['public_key']);
			}

			//HOME Direcotry
		}else{
			//Check if the file and folder exists
			if(!$this->DataModel->fileExists($file_storage_name)){
				redirect('/dashboard');
			}

			//Get file information
			$file = $this->DataModel->fileInformation($file_storage_name);
			$old_folder = $this->DataModel->getFolderInfo(array('id' => $file['parent']));
			//Check if the folder and the file belongs to the current user
			if(!$file['user_id'] == $this->authentication->uid){
				redirect('/dashboard');
			}

			//File and folder belongs to the user. Move the file now
			$this->DataModel->updateFile(array('id' => $file['id']), array('parent' => 0));
			if($file['parent'] == 0){
				redirect('/dashboard');
			}else{
				redirect('folders/'.$old_folder['public_key']);
			}
		}

		redirect('dashboard/');
	}

	public function moveFolder(){
		$move = $this->uri->segment(3);
		$dest = $this->uri->segment(4);
		$move = str_replace('folder-', '', $move);
		$move = str_replace('path-', '', $move);
		$dest = str_replace('folder-', '', $dest);
		$dest = str_replace('path-', '', $dest);

		//Check if the folder to move exists
		if(!$this->DataModel->folderExists(array('public_key' => $move), $this->authentication->uid)){
			redirect('/dashboard');
		}
			//Get the old parent
			$folder = $this->DataModel->getFolderInfo(array('public_key' => $move));

			//Check if the destination is the home directory

			if($dest == '0home'){
				$this->DataModel->updateFolder(array('public_key' => $move), array('parent' => 0));

			}else{
				//Folder is being moved to a different folder
				if($this->DataModel->folderExists(array('public_key' => $dest), $this->authentication->uid)){

					$new_parent = $this->DataModel->getFolderInfo(array('public_key' => $dest));
					$this->DataModel->updateFolder(array('public_key' => $move), array('parent' => $new_parent['id']));

				}
			}

			if($folder['parent'] == 0 ){
				redirect('/dashboard');
			}else{
				$old_parent = $this->DataModel->getFolderInfo(array('id' => $folder['parent']));
				redirect('folders/'.$old_parent['public_key']);
			}
	}

	//Function is called to delete a folder with all its content.
	public function deleteFolderPerm(){
		//Get the folder key
		$folder_key = $this->uri->segment(3);

		//Check if the folder exists
		if(!$this->DataModel->folderExists(array('public_key' => $folder_key), $this->authentication->uid) == TRUE){
			//Folder doesn't exist. Redirect to dashboard
			redirect('dashboard');
		}
		//Get more information about the folder
		$folderDetails = $this->DataModel->getFolderInfo(array('public_key' => $folder_key), $this->authentication->uid);

		//Ok, let's start to delete the folder...
		//First we need to check if there is any content in it

		//Get all folders in that folder
		$folderContent = $this->DataModel->getContent($folderDetails['id'], $this->authentication->uid);

		//Check if the folder is empty
		if(empty($folderContent['files']) && empty($folderContent['folders'])){
			//Simply delete the folder and redirect to the parent of that folder
			$this->DataModel->deleteFolder($folder_key);

			$this->successMessage[] = 'The selected folder was deleted successfuly';
			$this->save_messages();

			//Get the parent of that folder
			redirect('trashcan');

			$parentDetails = $this->DataModel->getFolderInfo(array('id' => $folderDetails['parent']), $this->authentication->uid);
			redirect('folders/'.$parentDetails['public_key']);
		}

		//Ok there's still content in that folder. Loop through all subfolders and get the files
		$this->allFiles = array();
		$this->allFolders = [$folderDetails['id']];
		$this->allFiles = $folderContent['files'];

		$this->findAllFiles($folderContent['folders']);


		//Ok, now load every storage engine that we need
		$storage_engines = array();
		foreach($this->allFiles as $file){

			//Load the storage engine
			if(!isset($storage_engines[$file['storage_engine']])){
				$storage_engines[$file['storage_engine']] = array();

				$storageDetails = $this->DataModel->storageEngine($file['storage_engine']);

				//Try to load the storage engine
				try {
					require_once(APPPATH.'libraries/storage/'.$storageDetails['library_name'].'/init.php');
					$storage_engines[$file['storage_engine']] = new $storageDetails['library_name']();
					$storage_engines[$file['storage_engine']]->connect();
				} catch (Exception $e) {
					$this->errorMessage($this->lang->line('error_storage_generic'));
					redirect('trashcan');
				}
			}

			//Delete the file from the storage engine
			try {
				$storage_engines[$file['storage_engine']]->delete($file['storage_name']);
				$storage_engines[$file['storage_engine']]->delete('thumb_'.$file['storage_name']);
			}catch (Exception $e){}
			//Delete the files from the database
			$this->DataModel->delete($file['storage_name']);
		}


		//Delete all folders from the database
		foreach($this->allFolders as $f){
			$this->DataModel->deleteFolderSearch(array('id' => $f));
		}

		$this->successMessage($this->lang->line('success_folder_deleted_perm'));

		//Get the parent of that folder
		redirect('trashcan');

	}

	public function deleteFolder(){
		//Get the folder key
		$folder_key = $this->uri->segment(3);

		//Check if the folder exists
		if(!$this->DataModel->folderExists(array('public_key' => $folder_key), $this->authentication->uid) == TRUE){
			//Folder doesn't exist. Redirect to dashboard
			redirect('dashboard');
		}
		//Mark the folder as deleted
		$this->DataModel->updateFolder(array('public_key' => $folder_key), array('trash' => 1));

		//Redirect to the folders parent
		$folder = $this->DataModel->getFolderInfo(array('public_key' => $folder_key));

		$this->successMessage($this->lang->line('success_folder_deleted'));

		if($folder['parent'] == 0){
			redirect('/dashboard');
		}else{
			$parent = $this->DataModel->getFolderInfo(array('id' => $folder['parent']));
			redirect('folders/'.$parent['public_key']);
		}

	}

	public function renameFolder(){
		$public_key = $this->uri->segment(3);

		//Check if the folder exists
		if(!$this->DataModel->folderExists(array('public_key' => $public_key)) == TRUE){
			//Folder doesn't exist
			redirect('/dashboard');
		}

		if(!$this->DataModel->userPermissionFolder('edit', $public_key, $this->authentication->uid) == TRUE){
				redirect('/dashboard');
		}

		//Get information about the folder
		$folderDetails = $this->DataModel->getFolderInfo(array('public_key' => $public_key));

		//Get information about the parent folder
		if(!$folderDetails['parent'] == 0){
			$parentDetails = $this->DataModel->getFolderInfo(array('id' => $folderDetails['parent']));
		}else{
			$parentDetails = array();
		}
		//Get the new name
		$newFolderName = $this->input->post('newName');

		//Check if the folder has at least one character
		if(!strlen($newFolderName) > 0){
			//Name not long enough. Redirect to the parent of that folder
			if(!empty($parentDetails)){
				redirect('folders/'.$parentDetails['public_key']);
			}else{
				redirect('/dashboard');
			}
		}

		//Rename the folder
		$this->DataModel->updateFolder(array('public_key' => $public_key), array('folder_name' => $newFolderName));

		$this->successMessage($this->lang->line('success_folder_renamed'));

		//All done. Redirect to the parent folder
		if($this->authentication->uid !== $folderDetails['user_id']){
			redirect('shared');
		}

		if(!empty($parentDetails)){
				redirect('folders/'.$parentDetails['public_key']);
		}else{
			redirect('/dashboard');
		}

	}

	public function renameFile(){
		$storage_name = $this->uri->segment(3);
		$newName = $this->input->post('newName');

		//Check if the file exists
		if(!$this->DataModel->fileExists($storage_name) == TRUE){
			redirect('/dashboard');
		}

		//Check if the user has permission to edit this file
		if(!$this->DataModel->userPermission('edit', $storage_name, $this->authentication->uid) == TRUE){
				redirect('/dashboard');
		}

		//Get detailed information about that file
		$fileInformation = $this->DataModel->fileInformation($storage_name);

		//Check if the new name is valid
		if(!strlen($newName) > 0){
			redirect('/dashboard');
		}

		//Change the filename
		$this->DataModel->updateFile(array('storage_name' => $storage_name), array('real_name' => $newName));

		//Redirect to the parent folder
		if($fileInformation['parent'] == 0){
			redirect('/dashboard');
		}else{
			$parentFolder = $this->DataModel->getFolderInfo(array('id' => $fileInformation['parent']));
			if($this->authentication->uid == $fileInformation['user_id']){
				redirect('folders/'.$parentFolder['public_key']);
			}else{
				redirect('sharedFolder/'.$parentFolder['public_key']);
			}
		}
	}

	private function findAllFiles($subfolders){
		foreach ($subfolders as $key => $folder) {
			//Get all subfolders of that folder
			$folderContent = $this->DataModel->getContent($folder['id'], $this->authentication->uid);
			array_push($this->allFolders, $folder['id']);
			$this->allFiles = array_merge($this->allFiles, $folderContent['files']);
			//Check if there are folders in that folder
			if(!empty($folderContent['folders'])){
					$this->findAllFiles($folderContent['folders']);
			}
		}
	}

	public function markFile(){
		$storage_name = $this->uri->segment(3);
		//Check if the file exists
		if(!$this->DataModel->fileExists($storage_name) == TRUE){
			redirect('/dashboard');
		}
		//Check if the user has permission to edit this file
		if(!$this->DataModel->userPermission('edit', $storage_name, $this->authentication->uid) == TRUE){
				redirect('/dashboard');
		}

		//Get file information to check if the file is already marked
		$file_information = $this->DataModel->fileInformation($storage_name);

		if($file_information['marked'] == true){
			$this->DataModel->updateFile(array('storage_name' => $storage_name), array('marked' => 0));
		}else{
			$this->DataModel->updateFile(array('storage_name' => $storage_name), array('marked' => 1));
		}

		//Redirect to the parent folder
		if($file_information['parent'] == 0){
			redirect('/dashboard');
		}else{
			$parent = $this->DataModel->getFolderInfo(array('id' => $file_information['parent']), $this->authentication->uid);
			redirect('folders/'.$parent['public_key']);
		}
	}

	public function restoreFile(){
		$storage_name = $this->uri->segment(3);

		//Check if the file exists
		if(!$this->DataModel->fileExists($storage_name)){
			redirect('/trashcan');
		}

		$file = $this->DataModel->fileInformation($storage_name);

		if(!$file['user_id'] == $this->authentication->uid){
			redirect('/trashcan');
		}

		$this->DataModel->updatefile(array('id' => $file['id']), array('trash' => 0));

		if($file['parent'] == 0){
			redirect('/dashboard');
		}else{
			$parent = $this->DataModel->getFolderInfo(array('id' => $file['parent']));
			redirect('folders/'.$parent['public_key']);
		}

		redirect('/dashboard');
	}

	public function restoreFolder(){
		$public_key = $this->uri->segment(3);

		//Check if the folder exists
		if(!$this->DataModel->folderExists(array('public_key' => $public_key, 'user_id' => $this->authentication->uid))){
			redirect('/trashcan');
		}

		$this->DataModel->updateFolder(array('public_key' => $public_key), array('trash' => 0));
		$folder = $this->DataModel->getFolderInfo(array('public_key' => $public_key));

		if($folder['parent'] == 0){
			redirect('/dashboard');
		}else{
			$parent = $this->DataModel->getFolderInfo(array('id' => $folder['parent']));
			redirect('folders/'.$parent['public_key']);
		}
		redirect('/dashboard');
	}

	public function markFolder(){
		$public_key = $this->uri->segment(3);

		//Check if the folder exists
		if(!$this->DataModel->folderExists(array('public_key' => $public_key), $this->authentication->uid) == TRUE){
			//Folder doesn't exist
			redirect('/dashboard');
		}

		//Get information about the folder
		$folderDetails = $this->DataModel->getFolderInfo(array('public_key' => $public_key), $this->authentication->uid);

		if($folderDetails['marked']){
			$this->DataModel->updateFolder(array('public_key' => $public_key), array('marked' => 0));
		}else{
			$this->DataModel->updateFolder(array('public_key' => $public_key), array('marked' => 1));
		}

		//Get information about the parent folder
		if(!$folderDetails['parent'] == 0){
			$parentDetails = $this->DataModel->getFolderInfo(array('id' => $folderDetails['parent']), $this->authentication->uid);
		}else{
			$parentDetails = array();
		}
		//All done. Redirect to the parent folder
		if(!empty($parentDetails)){
			redirect('folders/'.$parentDetails['public_key']);
		}else{
			redirect('/dashboard');
		}
	}

	public function share(){
		$this->load->model('UserModel');
		$user = $this->input->post('user_email');
		$share_id = $this->input->post('share-id');
		$permission_post = $this->input->post('permission');

		//Check that we've got a valid email address
		if(!filter_var($user, FILTER_VALIDATE_EMAIL)){
			echo json_encode(array('error' => $this->lang->line('error_validemail')));
			return true;
		}

		//Check that a user with that email address exists
		if($this->authentication->checkUniqueCredentials(array('email' => $user))){
			echo json_encode(array('error' => $this->lang->line('error_validuser')));
			return true;
		}

		//Check what permission flag we have to set
		if($permission_post == 'edit'){
			$permission = 1;
		}else{
			$permission = 0;
		}

		//Get user details
		$new_access = $this->UserModel->getUserSearch(array('email' => $user));

		//Make sure that the user isn't going to share the item with itself
		if($new_access['id'] == $this->authentication->uid){
			echo json_encode(array('error' => $this->lang->line('error_shareself')));
			return true;
		}

		//Check what type of sharing setting we have (file or folder)
		if(strpos($share_id, 'folder-') === 0){
			//We're sharing a folder
			$share_id = str_replace('folder-', '', $share_id);

			//Check if that folder exists
			if(!$this->DataModel->folderExists(array('public_key' => $share_id, 'user_id' => $this->authentication->uid))){
				echo json_encode(array('error' => $this->lang->line('error_folder_notfound')));
				return true;
			}
			//Get details about the folder
			$folder = $this->DataModel->getFolderInfo(array('public_key' => $share_id));

			//Check that the folder isn't already shared with the user
			if(!$this->DataModel->folderShareExists(array('folder_id' => $folder['id'], 'user_id' => $new_access['id']))){
				$this->DataModel->createShareFolder($folder['id'], $new_access['id'], $permission);

				//Send a notification mail to the user
				$this->load->library('Communication');
				$message_data = array('firstname' => $new_access['firstname'], 'receiver' => $new_access['email'], 'shared_link' => site_url('shared/'));
				$this->communication->send('folder_shared', $message_data);

				echo json_encode(array('success' => $this->lang->line('success_folder_shared')));
				return true;
			}else{
				echo json_encode(array('error' => $this->lang->line('error_folder_alreadyshared')));
				return true;
			}



		}elseif(strpos($share_id, 'file-') === 0){
			//Sharing a file
			$share_id = str_replace('file-', '', $share_id);
			//Check if that file exists
			if(!$this->DataModel->fileExists($share_id)){
				echo json_encode(array('error' => $this->lang->line('error_file_notfound')));
				return true;
			}

			//Check that the file is owned by the current user
			$file = $this->DataModel->fileInformation($share_id);

			if($file['user_id'] !== $this->authentication->uid){
				echo json_encode(array('error' => $this->lang->line('error_file_nopermission')));
				return true;
			}

			//Check that the file isn't already shared with the user
			if($this->DataModel->fileShareExists(array('file_id' => $file['id'], 'user_id' => $new_access['id'])) == false){
				//Create the sharing record
				$this->DataModel->createShareFile($file['id'], $new_access['id']);

				//Send a notification mail to the user
				$this->load->library('Communication');
				$message_data = array('firstname' => $new_access['firstname'], 'receiver' => $new_access['email'], 'shared_link' => site_url('shared/'));
				$this->communication->send('file_shared', $message_data);

				echo json_encode(array('success' => $this->lang->line('success_file_shared')));
				return true;
			}else{
				echo json_encode(array('error' => $this->lang->line('error_file_alreadyshared')));
				return true;
			}
		}
		return true;
	}

	public function getShares(){
		$share_id = $this->uri->segment(3);
		$shares = array();
		//Check what type of share we're working with
		if(strpos($share_id, 'file-') === 0){
			$share_id = str_replace('file-', '', $share_id);

			//Check if that file exists and belongs to the current user
			if(!$this->DataModel->fileExists(array('storage_name' => $share_id, 'user_id' => $this->authentication->uid))){
				echo json_encode(array('error' => $this->lang->line('error_file_notfound')));
				return true;
			}

			//Get information about that file
			$file = $this->DataModel->fileInformation($share_id);

			//Get all shares
			$shares = $this->DataModel->fileShares($file['id']);
			echo json_encode(array('shares' => $shares));
			return true;




		}else{
			$share_id = str_replace('folder-', '', $share_id);

			//Check if the folder exists and the user has access to it
			if(!$this->DataModel->folderExists(array('public_key' => $share_id, 'user_id' => $this->authentication->uid))){
				echo json_encode(array('error' => $this->lang->line('error_folder_notfound')));
				return true;
			}

			//Get information about that folder
			$folder = $this->DataModel->getFolderInfo(array('public_key' => $share_id));

			//Get all shares
			$shares = $this->DataModel->folderShares($folder['id']);
			echo json_encode(array('shares' => $shares));
			return true;
		}

		echo json_encode($shares);
	}

	public function deleteShare(){

		$obj_key = $this->uri->segment(3);
		$share_id = $this->uri->segment(4);

		if(!is_numeric($share_id)){
			echo json_encode(array('error' => $this->lang->line('error_share_notfound')));
			return true;
		}

		//Check what we're working with
		if(strpos($obj_key, 'file-') === 0){
			$storage_name = str_replace('file-', '', $obj_key);

			//Check if the file exists
			if(!$this->DataModel->fileExists(array('storage_name' => $storage_name, 'user_id' => $this->authentication->uid))){
				echo json_encode(array('error' => $this->lang->line('error_file_notfound')));
				return true;
			}

			//Get details about that file
			$file = $this->DataModel->fileInformation($storage_name);

			//Check if the share exists
			if(!$this->DataModel->fileShareExists(array('file_id' =>$file['id'], 'id' => $share_id))){
				echo json_encode(array('error' => $this->lang->line('error_share_notfound')));
				return true;
			}

			//Delete the share
			$this->DataModel->deleteFileShare(array('file_id' =>$file['id'], 'id' => $share_id));
			echo json_encode(array('success' => $this->lang->line('success_share_deleted')));
			return true;



		}elseif(strpos($obj_key, 'folder-') === 0){
			$public_key = str_replace('folder-', '', $obj_key);

			//Check if the folder exists
			if(!$this->DataModel->folderExists(array('public_key' => $public_key, 'user_id' => $this->authentication->uid))){
				echo json_encode(array('error' => $this->lang->line('error_folder_notfound')));
				return true;
			}

			//Get information about the folder
			$folder = $this->DataModel->getFolderInfo(array('public_key' => $public_key, 'user_id' => $this->authentication->uid));

			//Check if the share exists
			if(!$this->DataModel->folderShareExists(array('folder_id' =>$folder['id'], 'id' => $share_id))){
				echo json_encode(array('error' => $this->lang->line('error_share_notfound')));
				return true;
			}

			//Delete the share
			$this->DataModel->deleteFolderShare(array('folder_id' =>$folder['id'], 'id' => $share_id));
			echo json_encode(array('success' => $this->lang->line('success_share_deleted')));
			return true;
		}
		return true;
	}

	private function deleteFilesPermanently($fileList){
		$storage_engines = array();
		foreach($fileList as $file){
			if(!isset($storage_engines[$file['storage_engine']])){
				$storage_engine = $this->DataModel->storageEngine($file['storage_engine']);
				require_once(APPPATH.'libraries/storage/'.$storage_engine['library_name'].'/init.php');
				$storage_engine[$file['storage_engine']] = new $storage_engine['library_name']();
				$storage_engine[$file['storage_engine']]->connect();
			}
		}

		foreach($fileList as $file){
			$storage_name = $file['storage_name'];
			try {
				//Delete the file
				$storage_engine[$file['storage_engine']]->delete($storage_name);

				//Check if the file had a thumbnail too
				if($file['thumbnail'] == 1){
					//Delete the thumbnail
					$storage_engine[$file['storage_engine']]->delete('thumb_'.$storage_name);
				}

			} catch (Exception $e) {
				//IDEA We should catch errors here. Maybe add a log in the admin cp or so...
				$this->errorMessage($this->lang->line('error_storage_generic'));
			}

			//Delete the file from the database
			$this->DataModel->delete($storage_name);

			//Redirect to the trashcan and display a success message
			$this->successMessage($this->lang->line('success_file_deleted_perm'));
			redirect('/trashcan');
		}
	}

	private function deleteUserFiles($user_id){
		$files = $this->DataModel->getUserFiles($user_id);
		$folders = $this->DataModel->getUserFolders($user_id);

		$this->deleteFilesPermanently($files);
		foreach($folders as $folder){
			$this->DataModel->deleteFolder($folder['public_key']);
		}
		return true;
	}

}
