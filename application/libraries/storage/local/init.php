<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Local{

  public function __construct(){
    $this->storagePath = APPPATH.'uploads/';
  }

  //Connct to the storage server
  public function connect(){
    //This is the library for the local storage. We don't need to conncet here
  }

  //Saves a file to the storage
  public function upload($file){
    copy($file['tmp_name'], $this->storagePath.$file['storage_name']);
    return true;
  }

  //Downloads a file from the server
  public function download($storage_name){
    return file_get_contents($this->storagePath.$storage_name);
  }

  //Deletes a file
  public function delete($storage_name){
    unlink($this->storagePath.$storage_name);
    return true;
  }
  public function allSettings(){
    return array(array(
      'name' => 'quota_limit',
      'label' => 'Max Storagesize in MB',
      'type' => 'text'));
  }
}
