<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Statistics extends CI_Model{

  public function __construct(){
    parent::__construct();
  }

  public function file_uploads($timeframe){
    switch ($timeframe) {
      case 'today':
        $this->db->where('created >', strtotime('today midnight'));
        break;
      case 'yesterday':
        $this->db->where('created >', strtotime('yesterday midnight'));
        $this->db->where('created <', strtotime('today midnight'));
        break;
      default:
        $this->db->where('created >', 0);
        break;
    }
    return $this->db->count_all_results('filestorage');
  }

  public function total_filesize($gateway=false){
    if($gateway == TRUE){
      $this->db->group('storage_engine');
    }
    $this->db->select('SUM(filesize) as size');
    $return = $this->db->get('filestorage')->row_array();
    return $return['size'];
  }

  public function fileUploadChart(){
    $this->db->limit(14);
    $this->db->select('COUNT(id) as uploads, created');
    $this->db->group_by('DAY(FROM_UNIXTIME(created))');
    $this->db->order_by('created', 'DESC');
    return $this->db->get('filestorage')->result_array();
  }

  public function fileSizeChart(){
    $this->db->limit(14);
    $this->db->select('SUM(filesize) as filesize, created');
    $this->db->group_by('DAY(FROM_UNIXTIME(created))');
    $this->db->order_by('created', 'DESC');
    return $this->db->get('filestorage')->result_array();
  }

  public function user_statistics($uid){
    //Check how many files the user has
    $sql = "SELECT COUNT(filestorage.id) as filecount, (SELECT SUM(filesize) FROM filestorage WHERE user_id = ?) as total_filesize, (SELECT COUNT('id') FROM folders WHERE user_id = ?) as foldercount FROM `filestorage` WHERE user_id = ?";
    $result =  $this->db->query($sql, array($uid,$uid,$uid))->row_array();
    return $result;
  }
}
