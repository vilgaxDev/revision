<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Download extends MY_Controller {
	public function __construct(){
		parent::__construct();
		if(!$this->authentication->logged_in() == TRUE){
      redirect('/');
    }
	}
  //This function starts the download of a file without displaying anything.
  //It shall be called from the dashboard via ajax so we can display a dynamic success message
  public function start(){
    $storage_name = $this->uri->segment(3);

    //Check if the file exists
    if(!$this->DataModel->fileExists($storage_name) == TRUE){
      //File doesn't exists. Redirect to dasboard
      redirect('/dashboard');
    }

    //Check if the user has permission to download this file
    if(!$this->DataModel->userPermission('view', $storage_name, $this->authentication->uid) == TRUE){
      //The user doesn't has permission to access this file. Redirect to dashboard
      redirect('/dashboard');
    }

    //Get the file information
    $file_information = $this->DataModel->fileInformation($storage_name);

    //Get the correct storage engine
    $storage_engine = $this->DataModel->storageEngine($file_information['storage_engine']);

    //Load the storage engine
    //IDEA maybe add error handling here
    require_once(APPPATH.'libraries/storage/'.$storage_engine['library_name'].'/init.php');
    $storage = new $storage_engine['library_name']();
		$storage->connect();
    $file = $storage->download($file_information['storage_name']);

    //Start the download of the file
    $this->load->helper('download');
    force_download($file_information['real_name'], $file);

    //That's it. The file was downloaded by the client

    //Disconnect from the storage engine
    $storage_engine = NULL;
  }

}
