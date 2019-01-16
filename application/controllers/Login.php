<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller{
  public function __construct(){
    parent::__construct();
    $this->load->library(array('authentication'));
  }
  public function index(){
    //Check if the user is already logged in
    if($this->authentication->logged_in() == TRUE){
      //Redirect the user to the dashboard
      if($this->authentication->is_admin() == TRUE OR $this->authentication->is_staff() == TRUE){
        redirect('/admin/dashboard');
      }else{
        redirect('/dashboard');
      }
    }

    //Check if the login form got submited
    if(isset($_POST['username'])){
      $username = $this->input->post('username');
      $password = $this->input->post('password');
      //Check if neither of them is empty
      if(!empty($username) && !empty($password)){
        //Check if the credentials are correct
        if($this->authentication->login($username, $password) == TRUE){
          //Make sure that the user is a staff member
          if($this->authentication->is_admin() == TRUE OR $this->authentication->is_staff() == TRUE){
            redirect('/admin/dashboard');
          }else{
            redirect('/dashboard');
          }
        }else{
          //Display an error message
          $this->errorMessage($this->lang->line('error_login'));
        }
      }
    }
    //Display the login form
    $this->getMessages();
    $this->parser->parse('user/header.php', $this->template_data);
    $this->parser->parse('user/login.php', $this->template_data);
    $this->parser->parse('user/footer.php', $this->template_data);

  }
}
