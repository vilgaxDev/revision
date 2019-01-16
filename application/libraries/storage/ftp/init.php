<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Ftp{

  public function __construct(){
    $this->ci =& get_instance();
    $this->ci->load->library('ftp');
    $this->ci->load->model('SettingsModel');
  }

  //Connct to the storage server
  public function connect(){
    $config['hostname'] = $this->ci->SettingsModel->getSetting('ftp_ftp_host');
    $config['username'] = $this->ci->SettingsModel->getSetting('ftp_ftp_username');
    $config['password'] = $this->ci->SettingsModel->getSetting('ftp_ftp_password');
    $this->ci->ftp->connect($config);
  }

  //Saves a file to the storage
  public function upload($file){
    $this->ci->ftp->upload($file['tmp_name'], $file['storage_name']);
    return true;
  }

  //Downloads a file from the server
  public function download($storage_name){
    $this->ci->ftp->download($storage_name, APPPATH.'tmp/'.$storage_name);
    return file_get_contents($storage_name, APPPATH.'tmp/'.$storage_name);
  }

  //Deltes a file
  public function delete($storage_name){
    $this->ci->ftp->delete_file($storage_name);
    return true;
  }

  public function allSettings(){
    return array(
      array(
        'name' => 'ftp_username',
        'label' => 'FTP Username',
        'type' => 'text'
      ),
      array(
        'name' => 'ftp_password',
        'label' => 'FTP Password',
        'type' => 'text'
      ),
      array(
        'name' => 'ftp_host',
        'label' => 'FTP Hostname',
        'type' => 'text'
      ),
      array(
        'name' => 'quota_limit',
        'label' => 'Max Storagesize in MB',
        'type' => 'text')
    );
  }
}
