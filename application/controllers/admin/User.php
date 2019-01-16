<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller{
  public function __construct(){
    parent::__construct();
    if($this->authentication->logged_in() == FALSE || $this->authentication->is_staff() == FALSE){
      redirect('/login');
    }
    $this->load->model('UserModel');
  }

  //Display an overview of every user
  public function index(){

    //Check what page we're on
    if(isset($_GET['page']) && $this->input->get('page') > 0 &&is_numeric($this->input->get('page'))){
      $page = $this->input->get('page');
    }else{
      redirect('admin/user?page=1');
    }

    //Check if we have a search term defined
    $search_term = NULL;
    if($this->input->post('search_term') !== NULL){
      $search_term = $this->input->post('search_term');
      redirect('admin/customer?page=1&term='.$search_term);
    }elseif($this->input->get('term') !== NULL){
      $search_term = $this->input->get('term');
    }
    $this->template_data['search_term'] = $search_term;

    //Check if the page actually exists
    $total_pages = $this->UserModel->total_pages_customers($search_term);
    if($total_pages >= $page == TRUE){
      //Get the invoices for the current page
      $this->template_data['users'] = $this->UserModel->listCustomers($page-1, $search_term);
      //Pass the data to the template parser
      $this->template_data['total_pages'] = $total_pages;
      $this->template_data['page'] = $page;
    }else{
      redirect('admin/customer?page=1&term='.$search_term);
    }

    $this->parser->parse('admin/header.php', $this->template_data);
    $this->parser->parse('admin/menu.php', $this->template_data);
    $this->parser->parse('admin/userList.php', $this->template_data);
    $this->parser->parse('admin/footer.php', $this->template_data);
  }

  public function edit(){
    $user_id = $this->uri->segment(4);
    if(!is_numeric($user_id)){
      redirect('admin/user');
    }
    //Check if the user actually exists
    if(!$this->UserModel->userExists($user_id)){
      redirect('admin/user');
    }

    //Check if changes have been submitted
    if(isset($_POST['firstname'])){
      $firstname = $this->input->post('firstname');
      $lastname = $this->input->post('lastname');
      $email = $this->input->post('email');
      $password = $this->input->post('new_password');
      $password_repeat = $this->input->post('new_password_repeat');
      $update_data = array();

      $update_data['firstname'] = $firstname;
      $update_data['lastname'] = $lastname;

      //Check if the email address is unique
      if($this->authentication->checkUniqueCredentials(array('email' => $email), $user_id) == FALSE){
        $this->errorMessage[] = 'The email address already belongs to a different user!';
      }else{
        //Check if we have a valid email address
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
          $update_data['email'] = $email;
        }else{
          $this->errorMessage[] = 'Please enter a valid email address';
        }
      }

      //Check if the passwords got changed
      if(strlen($password) > 0){
        //Check if both passwords match
        if($password == $password_repeat){
          $update_data['password'] = $password;
          $this->successMessage[] = 'Password updated successfuly!';
        }else{
          $this->errorMessage[] = 'The entered passwords don\'t match!';
        }
      }

      //Check if the user has been set to a premium user and a deletion is not in progress
      if(strlen($this->input->post('premium_until')) > 0 && $this->input->post('deletePremium') == 0){
        $premium_until = strtotime($this->input->post('premium_until'));

        //Check that the time is greater than the current time
        if($premium_until > time()){
          //The time is valid. Update the user acccount
          $update_data['premium'] = 1;
          $update_data['premium_until'] = $premium_until;
          $this->successMessage[] = 'The user has been set to an premium user until: '.$this->input->post('premium_until');
        }else{
          //Display an error message that an invalid date has been passed
          $this->errorMessage[] = 'An invalid premium subscription end date has been passed. Premium status was not updated';
        }
      }

      //Check if the premium subscription of the user shall be terminated immediately
      if($this->input->post('deletePremium') == true){
        $update_data['premium'] = 0;
        $update_data['premium_until'] = time();
        $this->successMessage[] = 'The premium subscription of the user has been terminated';
      }

      //Update the user
      $this->authentication->update($user_id, $update_data);
      $this->successMessage[] = 'User updated successfuly!';
      $this->save_messages();
    }

    //Get all user details
    $this->template_data['user_details'] = $this->UserModel->getUser($user_id);
    //Do some formatting with the date of the premium subscription
    $this->template_data['user_details']['premium_until_formatted'] = date('m/d/Y', $this->template_data['user_details']['premium_until']);

    //Get all user groups in case we want to change that
    $this->template_data['user_groups'] = $this->UserModel->getUserGroups();

    $this->parser->parse('admin/header.php', $this->template_data);
    $this->parser->parse('admin/menu.php', $this->template_data);
    $this->parser->parse('admin/editUser.php', $this->template_data);
    $this->parser->parse('admin/footer.php', $this->template_data);
  }

  public function settings(){

    //Check if the form has been submitted
    if(isset($_POST['form_submitted'])){
      $error = false;

      $storage_capacity = $this->input->post('storage_max_capacity');
      $storage_capacity = str_replace(',', '.', $storage_capacity);
      $storage_max_files = $this->input->post('storage_max_files');
      $storage_max_folders = $this->input->post('storage_max_folders');


      //Check if storage capacity is an positive number or -1
      if($storage_capacity >= -1 && is_numeric($storage_capacity)){
        $storage_capacity = round($storage_capacity, 2);
        $this->SettingsModel->update('storage_capacity', $storage_capacity);
      }else{
        $error = true;
      }

      //Check if storage max_files is an positive integer or -1
      if($storage_max_files >= -1 && is_numeric($storage_max_files)){
        $storage_max_files = round($storage_max_files,0);
        $this->SettingsModel->update('storage_max_files', $storage_max_files);
      }else{
        $error = true;
      }

      //Check if storage max_folders is an positive integer or -1
      if($storage_max_folders >= -1 && is_numeric($storage_max_folders)){
        $storage_max_folders = round($storage_max_folders,0);
        $this->SettingsModel->update('storage_max_folders', $storage_max_folders);
      }else{
        $error = true;
      }

      //Check if wee ned to display an error
      if($error == true){
        $this->errorMessage[] = "Not all changes have been saved!";
      }else{
        $this->successMessage[] = "All changes have been saved successfuly!";
      }


    }

    $this->template_data['storage_capacity'] = $this->SettingsModel->getSetting('storage_capacity');
    $this->template_data['storage_max_folders'] = $this->SettingsModel->getSetting('storage_max_folders');
    $this->template_data['storage_max_files'] = $this->SettingsModel->getSetting('storage_max_files');

    $this->parser->parse('admin/header.php', $this->template_data);
    $this->parser->parse('admin/menu.php', $this->template_data);
    $this->parser->parse('admin/userSettings.php', $this->template_data);
    $this->parser->parse('admin/footer.php', $this->template_data);
  }

  public function view(){
    //Check if the user exists
    $user_id = $this->uri->segment(4);

    if($this->UserModel->userExists($user_id) == false){
      $this->errorMessage[] = 'Selected user could not be found';
      $this->save_messages();
      redirect('admin/user');
    }

    //Get all user details
    $this->template_data['user_details'] = $this->UserModel->getUser($user_id);

    //Do some formatting with the date of the premium subscription
    $this->template_data['user_details']['premium_until_formatted'] = date('m/d/Y', $this->template_data['user_details']['premium_until']);

    //Get all user groups so we can map them
    $this->template_data['user_groups'] = $this->UserModel->getUserGroups();

    //Get all transactions of that user
    $this->load->model('payment');
    $this->template_data['transactions'] = $this->payment->get_transactions(array('user_id' => $user_id));

    //Display the template
    $this->parser->parse('admin/header.php', $this->template_data);
    $this->parser->parse('admin/menu.php', $this->template_data);
    $this->parser->parse('admin/viewUser.php', $this->template_data);
    $this->parser->parse('admin/footer.php', $this->template_data);
  }

  public function delete(){
    $user_id = $this->uri->segment(4);

    //Check if the user exists
    if($this->authentication->checkUniqueCredentials(array('id' => $user_id)) == true){
      redirect('admin/user');
    }

    //Check that we arent deleting ourself
    if($this->authentication->uid == $user_id){
      $this->errorMessage[] = 'You can not delete your own account!';
      $this->save_messages();
      redirect('admin/user?page=1');
    }

    //Check if we have to display a warning page
    if(!$this->input->post('confirmed') == 1){
      //Display template
      $this->template_data['user_delete'] = $this->UserModel->getUser($user_id);
      $this->parser->parse('admin/header.php', $this->template_data);
      $this->parser->parse('admin/menu.php', $this->template_data);
      $this->parser->parse('admin/delete_user.php', $this->template_data);
      $this->parser->parse('admin/footer.php', $this->template_data);
    }else{
      //Delete all transactions
      $this->load->model(array('payment', 'DataModel'));
      $this->payment->delete_transaction(array('user_id' => $user_id));

      //Delete all files
      $this->deleteUserFiles($user_id);

      //Delete the user
      $this->UserModel->deleteUser($user_id);
      $this->successMessage[] = 'User deleted successfuly';
      $this->save_messages();
      redirect('admin/user');
    }
  }


  public function suspend(){
    $user_id = $this->uri->segment(4);

    //Check if the user exists
    if($this->authentication->checkUniqueCredentials(array('id' => $user_id)) == true){
      redirect('admin/user');
    }

    //Check that we arent deleting ourself
    if($this->authentication->uid == $user_id){
      $this->errorMessage[] = 'You can not suspend your own account!';
      $this->save_messages();
      redirect('admin/user?page=1');
    }

    //Check if the user is active or already suspended
    $user_details = $this->UserModel->getUser($user_id);

    if($user_details['active'] == 1){
      $this->authentication->update($user_id, array('active' => 0));
      $this->successMessage[] = 'User suspended successfully!';
    }else{
      $this->authentication->update($user_id, array('active' => 1));
      $this->successMessage[] = 'User activated successfully!';
    }
    $this->save_messages();
    redirect('admin/user?page=1');
  }


  //Duplicate function to user File.php
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
      try {
        //Delete the file
        $storage_engine[$file['storage_engine']]->delete($file['storage_name']);

        //Check if the file had a thumbnail too
        if($file['thumbnail'] == 1){
          //Delete the thumbnail
          $storage_engine[$file['storage_engine']]->delete('thumb_'.$file['storage_na,e']);
        }

      } catch (Exception $e) {
        //IDEA We should catch errors here. Maybe add a log in the admin cp or so...
        echo 'Storage Error. Please contact administrator!';
      }

      //Delete the file from the database
      $this->DataModel->delete($file['storage_name']);
    }
  }

  public function add(){

    //Get a list of all user groups
    $this->

    $this->parser->parse('admin/header.php', $this->template_data);
    $this->parser->parse('admin/menu.php', $this->template_data);
    $this->parser->parse('admin/createUser.php', $this->template_data);
    $this->parser->parse('admin/footer.php', $this->template_data);
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
