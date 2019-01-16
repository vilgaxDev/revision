<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {
	public function __construct(){
		parent::__construct();

    $this->load->model(array('AuthenticationModel', 'payment'));
	}

  public function account(){
		if(!$this->authentication->logged_in() == TRUE){
			redirect('/');
		}
    //Get details of the user account
    $this->template_data['userdetails'] = $this->AuthenticationModel->userInformation($this->authentication->uid);

		//Get details about the users premium subscription if the user is a premium user
		if($this->authentication->premium == true){
			$this->template_data['premium']['formatted_valid_until'] = gmdate("Y-m-d", $this->authentication->premium_until);
			//Get the users last transaction he made
			$last_transaction = $this->payment->last_transaciton_detail($this->authentication->uid);
			$this->template_data['premium']['formatted_purchase_date'] = gmdate("Y-m-d", $last_transaction['time']);
			$this->template_data['premium']['payment_method_display_name'] = $last_transaction['payment_method_display_name'];
		}

    $this->parser->parse('user/header_loggedin.php', $this->template_data);
    $this->parser->parse('user/my_account.php', $this->template_data);
  	$this->parser->parse('user/footer.php', $this->template_data);
  }

  public function changepw(){
		if(!$this->authentication->logged_in() == TRUE){
			redirect('/');
		}

    //Check if the form has been submitted
    if(isset($_POST['new_password'])){
      //Check if both passwords are present
      $new_password = $this->input->post('new_password');
      $repeat_new_password = $this->input->post('repeat_new_password');

      //Check that both arent empty
      if($new_password == '' || $repeat_new_password == ''){
        //One of the passwords is empty. Display an error message
        $this->errorMessage[] = 'The password can not be empty!';
      }

      //Check if the passords match
      if(!$new_password == $repeat_new_password){
        $this->errorMessage[] = 'The entered passwords don\'t match!';
      }

      //Check that the password is at least 5 characters long
      if(strlen($new_password) < 4){
        $this->errorMessage[] = 'The password has to be at least 5 characters long!';
      }

      //Check if we have any error messages
      if(empty($this->errorMessage)){
        //Update the password
        $this->authentication->update($this->authentication->uid, arraY('password' => $new_password));
        $this->successMessage[] = 'Password updated successfuly!';
      }


    }

    //Save the error or success messages
    $this->session->set_userdata('success', $this->successMessage);
    $this->session->set_userdata('error', $this->errorMessage);
    //Display the template
    $this->parser->parse('user/header_loggedin.php', $this->template_data);
    $this->parser->parse('user/changepw.php', $this->template_data);
    $this->parser->parse('user/footer.php', $this->template_data);

  }

	public function completereg(){
		$this->load->model('UserModel');
		//Get the activation code
		$activation_code = $this->uri->segment(3);

		//Check if the code exists
		if(!$this->UserModel->activationCode($activation_code)){
			$this->errorMessage[] = 'Activation code not found';
		}else{
			//Activate the user
			$user = $this->UserModel->getUserSearch(array('activation_code' => $activation_code));
			$this->UserModel->activateUser($activation_code);

			//Send a confirmation email
			$this->load->library('Communication');
			$user_details = array('firstname' => $user['firstname'], 'receiver' => $user['email']);
			$this->communication->send('account_activated', $user_details);
			$this->successMessage[] = 'Account activated successfuly';
		}

		$this->save_messages();
		redirect('/login');
	}


	public function resetpw(){
    //Make sure that the user isn't logged in
    if($this->authentication->logged_in() == FALSE){

      //Check if the reset_code is present
      if(!$this->uri->segment(3) == ''){
        $reset_code = $this->uri->segment(3);
        //Check if the reset code exists
        if($this->authentication->reset_code($reset_code) == TRUE){
          //Check if the form has been submitted
          if(isset($_POST['password'])){
            //Check if the passwords match
            if($this->input->post('password') == $this->input->post('password_repeat')){
              $this->authentication->reset_code($reset_code, $this->input->post('password'));
              $this->successMessage[] = 'Password set successfully!';
            }else{
              $this->errorMessage[] = 'Both passwords have to match!';
            }
						$this->save_messages();
          }
          //Display the password reset page
          $this->template_data['reset_code'] = $reset_code;
          $this->parser->parse('user/header.php', $this->template_data);
          $this->parser->parse('user/set_new_password.php', $this->template_data);
          $this->parser->parse('user/footer.php', $this->template_data);
        }else{
          redirect('user/resetpw');
        }

      }else{
        //The form has been submitted
        if(isset($_POST['email_address'])){
          //Check if we have a valid email address
          if(filter_var($this->input->post('email_address'), FILTER_VALIDATE_EMAIL)){
            //Generate a reset code
            $details = $this->authentication->resetpw($this->input->post('email_address'));
            //Send an email with the reset code
            $this->successMessage[] = 'If there is an user with this email address a password reset email has been send!';
						$this->save_messages();

						//If a user with the email address was found
						if(!empty($details)){
							//Send a confirmation email
							$details['link'] = site_url('user/resetpw/'.$details['pw_reset_code']);
							$details['receiver'] = $this->input->post('email_address');
							$this->load->library('Communication');
							$this->communication->send('user_forgotpw', $details);
						}
          }else{
            $this->errorMessage[] = 'Please enter a valid email address!';
						$this->save_messages();
          }
        }
        $this->parser->parse('user/header.php', $this->template_data);
        $this->parser->parse('user/reset_password.php', $this->template_data);
        $this->parser->parse('user/footer.php', $this->template_data);
      }
    }else{
      redirect('dashboard');
    }
  }





	public function logout(){
		if(!$this->authentication->logged_in() == TRUE){
			redirect('/');
		}
		$this->authentication->logout();
		redirect('/');
	}
}
?>
