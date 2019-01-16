<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller{
  public $template_data;
  private $error_message, $success_message;

  public function __construct(){
    parent::__construct();

    $this->template_data = array();
    $this->load->library(array('session'));

    //Load the language files
    $this->lang->load('errorMessages', 'english');
    $this->lang->load('successMessages', 'english');

    //Get the current usage statistics of that user
    if($this->authentication->logged_in() == true){
      $this->load->library(array('usagestatistics'));
      $this->template_data['usage_statistics'] = $this->usagestatistics->getUser($this->authentication->uid);
    }

    //Check if premium features are enabled
    $this->template_data['premium_enabled'] = $this->SettingsModel->getSetting('premium_enabled');


    $this->getPageTitle();
    $this->getFavicon();
  }

  public function getMessages(){
    $messages['success_message'] = $this->session->userdata('success_message');
    $messages['error_messsage'] = $this->session->userdata('error_message');
    $this->session->unset_userdata('success_message');
    $this->session->unset_userdata('error_message');
    $this->template_data = array_merge($this->template_data, $messages);
  }

  public function errorMessage($msg){
    $this->error_message[] = $msg;
    $this->session->set_userdata('error_message', $this->error_message);
  }

  public function successMessage($msg){
    $this->success_message[] = $msg;
    $this->session->set_userdata('success_message', $this->success_message);
  }

  private function getPageTitle(){
    //Get the current page title
    $cache = file_get_contents(APPPATH.'cache/cache.php');
    $cache = json_decode($cache,true);
    //Check how old the cached page_title variable is
    if($cache['page_title']['time']+500 < time() || !isset($cache['page_title']['content']) || !isset($cache['page_title']['time'])){
      //Reload the variable
      $page_title = $this->SettingsModel->getSetting('page_title');

      $data = array();
      $data['page_title']['content'] = $page_title;
      $data['page_title']['time'] = time();
      file_put_contents(APPPATH.'cache/cache.php', json_encode($data));
      $cache = $data;
    }
    $this->template_data['page_title'] = $cache['page_title']['content'];
  }

  private function getFavicon(){
    if(file_exists(APPPATH.'uploads/favicon.png')){
      $this->template_data['page_favicon'] = base64_encode(file_get_contents(APPPATH.'uploads/favicon.png'));
    }else{
      $this->template_data['page_favicon'] = '';
    }
  }


  public function finish(){
    $this->session->set_userdata('successMessage', $this->successMessage);
    $this->session->set_userdata('errorMessage', $this->errorMessage);
  }
  public function save_messages(){
    $this->finish();
  }

}
