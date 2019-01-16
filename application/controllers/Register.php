<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends MY_Controller{
  public function __construct(){
    parent::__construct();
    $this->load->library(array('authentication'));
  }

  public function index(){

    //Check if the form got submitted
    if(isset($_POST['firstname'])){
      $firstname = $this->input->post('firstname');
      $lastname = $this->input->post('lastname');
      $email = $this->input->post('username');
      $password = $this->input->post('password');
      $password_repeat = $this->input->post('password_repeat');
      $terms = $this->input->post('terms');

      $registration = true;

      //Verify first and lastname
      if(strlen($firstname) < 3 || strlen($lastname) < 3){
        $registration = false;
      }

      //Verify the email address
      if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $registration = false;
        $this->errorMessage[] = 'Please enter a valid email address';
      }else{
        if(!$this->authentication->checkUniqueCredentials(array('email' => $email))){
          $registration = false;
          $this->errorMessage[] = 'An account with the email address already exists';
        }
      }

      //Check that the password is at least 5 characters and they match
      if(strlen($password) < 5 || !$password == $password_repeat){
        $registration = false;
        $this->errorMessage[] = 'The passwords don\'t match';
      }

      //Check if everything is ok
      if($registration == true){
        $credentials = array(
          'firstname' => $firstname,
          'lastname' => $lastname,
          'email' => $email,
          'password' => $password
        );

        //Create a random activation key
        $activation = $this->generateRandomString(6);
        $credentials['active'] = 0;
        $credentials['activation_code'] = $activation;

        $this->authentication->register($credentials);
        $this->load->library('Communication');
        $message_data = array('firstname' => $credentials['firstname'], 'receiver' => $credentials['email'], 'link' => site_url('user/completereg/'.$activation));
        $this->communication->send('registration', $message_data);
        $this->successMessage[] = 'User created successfuly. Please check your email account';
      }

      $this->save_messages();
    }

    $this->parser->parse('user/header.php', $this->template_data);
    $this->parser->parse('user/register.php', $this->template_data);
    $this->parser->parse('user/footer.php', $this->template_data);
  }

  private function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
}
