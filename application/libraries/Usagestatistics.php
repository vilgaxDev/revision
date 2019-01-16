<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UsageStatistics{
  private $ci;
  public function __construct(){
    $this->ci =& get_instance();
  }

  //This function returns a statistics of the users current date usage
  public function getUser($user_id){
    //Get the current account usage
    $this->ci->load->model('Statistics');
    $current_usage = $this->ci->Statistics->user_statistics($user_id);

    //Check if the current user is a premium user or not to get the max allowed values
    $this->ci->load->model('UserModel');
    $user = $this->ci->UserModel->getUser($user_id);
    if($user['premium'] == 1){
      //Get the max values for premium users
      $max_values = array();
      $max_values['storage'] = $this->ci->SettingsModel->getSetting('premium_storage_capacity');
      $max_values['foldercount'] = $this->ci->SettingsModel->getSetting('premium_storage_max_folders');
      $max_values['filecount'] = $this->ci->SettingsModel->getSetting('premium_storage_max_files');
    }else{
      $max_values['storage'] = $this->ci->SettingsModel->getSetting('storage_capacity');
      $max_values['foldercount'] = $this->ci->SettingsModel->getSetting('storage_max_folders');
      $max_values['filecount'] = $this->ci->SettingsModel->getSetting('storage_max_files');
    }

    //Calculate the procentual values
    $return = array();
    $return['usage'] = $current_usage;
    $return['max'] = $max_values;
    $return['max']['total_filesize'] = $max_values['storage']*1024*1024*1024;

    //Check if not unlimited
    if($max_values['storage'] != 0 && $max_values['storage'] != -1){
      $return['usage']['percent']['total_filesize'] = $current_usage['total_filesize'] / ($max_values['storage']*1024*1024*1024) * 100;
    }elseif($max_values['storage'] == 0 || is_null($max_values['storage'])){
      $return['usage']['percent']['total_filesize'] = 0.00;
      $return['max']['total_filesize'] = '∞';
    }elseif($max_values['storage'] == -1){
      $return['usage']['percent']['total_filesize'] = 100.00;
    }

    //Check if not unlimited
    if($max_values['foldercount'] != 0 && $max_values['foldercount'] != -1){
      $return['usage']['percent']['foldercount'] = $current_usage['foldercount'] / $max_values['foldercount'] * 100;
    }elseif($max_values['foldercount'] == 0 || is_null($max_values['foldercount'])){
      $return['usage']['percent']['foldercount'] = 0.00;
      $return['max']['foldercount'] = '∞';
    }elseif($max_values['foldercount'] == -1){
      $return['usage']['percent']['foldercount'] = 100.00;
    }

    //Check if not unlimited
    if($max_values['filecount'] != 0 && $max_values['filecount'] != -1 && $max_values['filecount'] !== null){
      $return['usage']['percent']['filecount'] = $current_usage['filecount'] / $max_values['filecount'] * 100;
    }elseif($max_values['filecount'] == 0 || is_null($max_values['filecount'])){
      $return['usage']['percent']['filecount'] = 0.00;
      $return['max']['filecount'] = '∞';
    }elseif($max_values['filecount'] = -1){
      $return['usage']['percent']['filecount'] = 100.00;
    }
    return $return;
  }

}
