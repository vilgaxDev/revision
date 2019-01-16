<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Files extends MY_Controller {

  public function index(){
    if($this->authentication->logged_in() == TRUE){
      redirect('admin/files/overview');
    }else{
      redirect('admin/login');
    }
  }

  public function overview(){
    if($this->authentication->logged_in() == FALSE){
      redirect('admin/login');
    }
    //Create the pagination
    $files_per_page = 15;

    //Check how many pages are there
    $total_pages = ceil($this->Statistics->file_uploads('total') / $files_per_page);
    //Protect from infinite loops when no items are uploaded yet
    if($total_pages == 0){
      $total_pages = 1;
    }

    //Check what page we're on
    if($this->uri->segment(4) == '' || !is_numeric($this->uri->segment(4))){
      redirect('admin/files/overview/1');
    }
    $current_page = $this->uri->segment(4);
    //Check if the page exists
    if($current_page > $total_pages || $current_page < 1){
      redirect('admin/files/overview/1');
    }
    if($this->input->get('page') > 0 && is_numeric($this->input->get('page')) && $this->input->get('page') <= $total_pages){
      $offset = ($this->input->get('page') - 1) * $files_per_page;
      $current_page = $this->input->get('page');
    }else{
      $offset = 0;
    }

    //check if we need to order the files
    $order_var = $this->input->get('order_by');
    switch ($order_var) {
      case 'udnf':
        break;
      case 'udof':
        break;
      case 'fsbf':
        break;
      case 'fssf':
        break;
      default:
        $order_var = '';
        break;
    }
    //check if there is a search term defined
    $search_term = $this->input->get('search_term');

    //Get the files to display in the overview table
    $files = $this->DataModel->getFiles(0, $files_per_page, $order_var, $search_term);

    //Display the templates
    $this->template_data['total_pages'] = $total_pages;
    $this->template_data['files'] = $files;
    $this->template_data['current_page'] = $current_page;
    $this->template_data['order_by'] = $order_var;
    $this->template_data['search_term'] = $search_term;

    $this->parser->parse('admin/header.php', $this->template_data);
    $this->parser->parse('admin/menu.php', $this->template_data);
    $this->parser->parse('admin/file_overview.php', $this->template_data);
    $this->parser->parse('admin/footer.php', $this->template_data);
  }

  public function delete(){
    if($this->authentication->logged_in() == FALSE){
      redirect('admin/login');
    }

    //Make sure the file the user is trying to delete actually exists
    $file_id = $this->uri->segment(4);
    if($this->DataModel->fileExists($file_id) == FALSE){
      redirect('admin/files/overview');
    }

    #Get all file information
    $file_information = $this->DataModel->fileInformation($file_id);


    //Check if the user has already confirmed the deletion
    if($this->input->post('confirmed') == true){
      #Get the storage type of the file
      $storage_engine = $file_information['storage_engine'];
      $storageEngine = $this->DataModel->storageEngine($storage_engine);
      require_once(APPPATH.'libraries/storage/'.$storageEngine['library_name'].'/init.php');
      $storage = new $storageEngine['library_name']();
      //Connect the storage engine to the storage server
      $storage->connect();
      $storage->delete($file_id);
      $this->DataModel->delete($file_id);
      $this->session->set_userdata('success_message', 'File deleted successfully!');
      redirect('admin/files/overview');
    }else{
      #Display a confirmation page
      $this->template_data['file'] = $file_information;
      #Display the confirmation page
      $this->parser->parse('admin/header.php', $this->template_data);
      $this->parser->parse('admin/menu.php', $this->template_data);
      $this->parser->parse('admin/delete_file.php', $this->template_data);
      $this->parser->parse('admin/footer.php', $this->template_data);

    }
  }

}
