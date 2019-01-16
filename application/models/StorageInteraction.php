<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StorageInteraction extends CI_Model{

  public function __construct(){
    parent::__construct();
  }

  public function allEngines(){
    $storages = $this->db->get('storage_types')->result_array();
    //Get the current quota usage of all file systems
    foreach($storages as $key => $storage){
      $this->db->where('storage_engine', $storage['id']);
      $this->db->select('SUM(filesize / 1024 / 1024) AS total_filesize_mb');
      $result = $this->db->get('filestorage')->row_array();
      $storages[$key]['total_filesize_mb'] = $result['total_filesize_mb'];
      //Ok, now check if we have a max_quota set for this storage engine
      $this->db->where('name', $storage['library_name'].'_quota_limit');
      $result = $this->db->get('setting_items')->row_array();
      if (is_array($result)){

        if($result['content'] > 0){
          $storages[$key]['storage_usage'] = round($storages[$key]['total_filesize_mb'] / $result['content'] * 100, 2);
        }else{
          $storages[$key]['storage_usage'] = 0;
        }
        
        if($storages[$key]['storage_usage'] > 100){
          $storages[$key]['storage_usage'] = 100;
        }
      }else{
        $storages[$key]['storage_usage'] = -1;
      }
    }
    return $storages;
  }


  public function enableStorage($type){
    $this->db->where('library_name', $type);
    $this->db->update('storage_types', array('active' => 1));
    //Disable all other engines
    $this->db->where('library_name !=', $type);
    $this->db->update('storage_types', array('active' => 0));
    return true;
  }

  public function engineExists($type){
    $this->db->where('library_name', $type);
    if($this->db->count_all_results('storage_types') == 1){
      return true;
    }
    return false;
  }

  public function engineDetails($type){
    $this->db->where('library_name', $type);
    return $this->db->get('storage_types')->row_array();
  }

}
