<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MY_Controller{
  public function __construct(){
    parent::__construct();
    if($this->authentication->logged_in() == FALSE || $this->authentication->is_admin() == FALSE){
      redirect('/login');
    }
  }

  public function emailSettings(){
    if(isset($_POST['email_hostname'])){
      $all_settings = array('email_notifications' => '', 'email_hostname' => '', 'email_username' => '', 'email_password' => '', 'email_address' => '', 'email_display_name' => '');
      foreach($all_settings as $key => $setting){
        if(isset($all_settings[$key])){
          if(!empty($this->input->post($key))){
            $this->SettingsModel->update($key, $this->input->post($key));
          }
        }
      }
      $this->successMessage[] = 'Email settings updated successfuly!';
    }
    $this->template_data = array_merge($this->template_data, $this->SettingsModel->emailSettings());

    $this->parser->parse('admin/header.php', $this->template_data);
    $this->parser->parse('admin/menu.php', $this->template_data);
    $this->parser->parse('admin/emailSettings.php', $this->template_data);
    $this->parser->parse('admin/footer.php', $this->template_data);
  }

  public function emailEnable(){
    $notifictation = $this->SettingsModel->getSetting('email_notifications');

    if($notifictation == 1){
      $this->SettingsModel->update('email_notifications', 0);
    }else{
      $this->SettingsModel->update('email_notifications', 1);
    }
  }

  public function emailTemplates(){
    if(isset($_POST['template_update'])){
      $update_values = array();
      foreach($_POST as $key => $data){
        $update_values[$key] = $data;
      }
      $this->SettingsModel->updateEmailTemplates($update_values);
    }
    //Get all email templates
    $this->template_data['templates'] = $this->SettingsModel->emailTemplates();
    $this->parser->parse('admin/header.php', $this->template_data);
    $this->parser->parse('admin/menu.php', $this->template_data);
    $this->parser->parse('admin/emailTemplates.php', $this->template_data);
    $this->parser->parse('admin/footer.php', $this->template_data);
  }

  public function general(){

    //Check if the form got submitted
    if(isset($_POST['page_title'])){
      $page_title = $this->input->post('page_title');
      $favicon = $this->input->post('page_favicon');

      //Update the page title
      if(strlen($page_title) > 0){
        $this->SettingsModel->update('page_title', $page_title);
        $this->successMessage[] = 'Page title updated successfuly!';
      }

      if(!empty($_FILES)){
        move_uploaded_file($_FILES['page_favicon']['tmp_name'], APPPATH.'uploads/favicon.png');
        $this->successMessage[] = 'Favicon updated successfuly!';
      }

    }

    //Get the current settings
    $this->template_data['settings'] = $this->SettingsModel->getSetting(array('page_title'));

    //Try to load the favicon
    if(file_exists(APPPATH.'uploads/favicon.png')){
      $this->template_data['settings']['favicon'] = base64_encode(file_get_contents(APPPATH.'uploads/favicon.png'));
    }else{
      $this->template_data['settings']['favicon'] = '';
    }

    $this->parser->parse('admin/header.php', $this->template_data);
    $this->parser->parse('admin/menu.php', $this->template_data);
    $this->parser->parse('admin/generalSettings.php', $this->template_data);
    $this->parser->parse('admin/footer.php', $this->template_data);
  }

}
