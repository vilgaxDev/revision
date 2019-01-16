<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller{
  public function __construct(){
    parent::__construct();
    $this->load->model(array('statistics', 'StorageInteraction'));
    if($this->authentication->logged_in() == FALSE || $this->authentication->is_admin() == FALSE){
      redirect('/login');
    }
  }

  public function index(){
      //Check for warning messages that we can display
      $this->template_data['warningMessages'] = array();

      //Check if user limits are set (otherwise users aren't able to upload any files)
      $userLimits = $this->SettingsModel->getSetting(array('storage_max_files', 'storage_max_folders', 'storage_capacity'));
      if($userLimits['storage_max_files'] == '' || $userLimits['storage_max_folders'] == '' || $userLimits['storage_capacity'] == ''){
        $this->template_data['warningMessages'][] = 'Upload Limites for users not defined. <a href="'.site_url('admin/user/settings').'">Edit</a>';
      }

      //Check if email notifications are enabled but no settings are provided.
      $emailSettings = $this->SettingsModel->getSetting(array('email_notifications', 'email_hostname', 'email_username', 'email_password', 'email_address'));

      if($emailSettings['email_notifications'] == 1){
        //Notifications enabled

        //Check if all settings are configured
        if($emailSettings['email_hostname'] == '' || $emailSettings['email_username'] == ''|| $emailSettings['email_password'] == '' || $emailSettings['email_address'] == ''){
          $this->template_data['warningMessages'][] = 'Email notifications enabled but settings not defined <a href="'.site_url('admin/settings/emailSettings').'">Edit</a>';
        }
      }else{
        //No notifications
        $this->template_data['warningMessages'][] = 'Email notifications not enabled. Users wont receive e.g. registration emails. <a href="'.site_url('admin/settings/emailSettings').'">Edit</a>';
      }

      //Current statistics
      $this->template_data['files']['uploaded_today'] = $this->statistics->file_uploads('today');
      $this->template_data['files']['uploaded_yesterday'] = $this->statistics->file_uploads('yesterday');
      $this->template_data['files']['uploaded_total'] = $this->statistics->file_uploads('total');
      $this->template_data['files']['size'] = $this->statistics->total_filesize();
      $this->template_data['files']['size'] = $this->formatBytes($this->template_data['files']['size']);

      $this->template_data['storageEngines'] = $this->StorageInteraction->allEngines();

      $this->template_data['files']['uploadStats'] = $this->statistics->fileUploadChart();
      $this->template_data['files']['uploadSizeStats'] = $this->statistics->fileSizeChart();

      $this->parser->parse('admin/header.php', $this->template_data);
      $this->parser->parse('admin/menu.php', $this->template_data);
      $this->parser->parse('admin/dashboard.php', $this->template_data);
      $this->parser->parse('admin/footer.php', $this->template_data);

  }

  private function formatBytes($size, $unit = '') {
    if( (!$unit && $size >= 1<<30) || $unit == "GB")
    return number_format($size/(1<<30),2)."GB";
  if( (!$unit && $size >= 1<<20) || $unit == "MB")
    return number_format($size/(1<<20),2)."MB";
  if( (!$unit && $size >= 1<<10) || $unit == "KB")
    return number_format($size/(1<<10),2)."KB";
  return number_format($size)." bytes";
  }
}
