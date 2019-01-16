<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class directDownload extends MY_Controller {
  function __construct(){
    parent::__construct();
  }
  public function index(){
    $storage_name = $this->uri->segment(3);
    $storage_name = str_replace('file-', '', $storage_name);
    //Check if the file exists
    if(!$this->DataModel->fileExists(array('storage_name' => $storage_name))){
      $this->getMessages();
      $this->parser->parse('user/header.php', $this->template_data);
      $this->parser->parse('user/directDownload_notFound.php', $this->template_data);
      $this->parser->parse('user/footer.php', $this->template_data);
    }else{

      $this->template_data['file'] = $this->DataModel->fileInformation($storage_name);

      //Check if the download of the file is confirmed
      if($this->input->post('download') == true){
        $storage_engine = $this->DataModel->storageEngine($this->template_data['file']['storage_engine']);
        require_once(APPPATH.'libraries/storage/'.$storage_engine['library_name'].'/init.php');
        $storage = new $storage_engine['library_name']();
        $storage->connect();
        $file = $storage->download($this->template_data['file']['storage_name']);

        //Start the download of the file
        $this->load->helper('download');
        force_download($this->template_data['file']['real_name'], $file);
      }

      //Display the overview page
      $this->getMessages();
      $this->parser->parse('user/header.php', $this->template_data);
      $this->parser->parse('user/directDownload.php', $this->template_data);
      $this->parser->parse('user/footer.php', $this->template_data);

    }
  }
}
