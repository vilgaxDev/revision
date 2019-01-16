<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DirectPreview extends MY_Controller {
	public function __construct(){
		parent::__construct();
		if(!$this->authentication->logged_in() == TRUE){
      redirect('/');
    }
	}

  public function index(){
    $file = $this->uri->segment(2);
    $this->load->model('DataModel');
    //Check if we're allowed to access that file
    if(!$this->DataModel->userPermission('view', $file, $this->authentication->uid)){
      return false;
    }

    //Get more details about that file
    $file_information = $this->DataModel->fileInformation($file);

    //Get the correct storage engine
    $storage_engine = $this->DataModel->storageEngine($file_information['storage_engine']);

    //Load the storage engine
    //IDEA maybe add error handling here
    require_once(APPPATH.'libraries/storage/'.$storage_engine['library_name'].'/init.php');
    $storage = new $storage_engine['library_name']();
		$storage->connect();
    $file = $storage->download($file_information['storage_name']);

    //Start the download of the file
    if($file_information['mime'] == 'application/pdf'){
      header("Content-type: application/pdf");
    }
    if($file_information['mime'] == 'audio/mp3'){
      header("Content-type: audio/mpeg");
    }
    if($file_information['mime'] == 'image/png'){
      header("Content-type: image/png");
    }
		if($file_information['mime'] == 'video/mp4'){
			header("Content-Type: video/mp4");
			header("Content-Length: ".$file_information['filesize']);
		}

    echo $file;
    //That's it. The file was downloaded by the client

    //Disconnect from the storage engine
    $storage_engine = NULL;

  }

}
