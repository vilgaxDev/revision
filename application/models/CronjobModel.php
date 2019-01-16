<?php defined('BASEPATH') OR exit('No direct script access allowed');

class CronjobModel extends CI_Model{
  public function __construct(){
    parent::__construct();
  }

  public function getOldSubscriptions(){
    $this->db->where('premium_until <', time());
    $this->db->where('premium', 1);
    $this->db->select('firstname, email as receiver');
    return $this->db->get('users')->result_array();
  }

  public function updateUser($where, $update){
    $this->db->where($where);
    $this->db->update('users', $update);
    return true;
  }

  public function getAlmostOldSubscriptions(){
    $this->db->where('premium', 1);
    $this->db->where('premium_until <', time()+60*60*24*14);
    $this->db->select('firstname, email as receiver');
    return $this->db->get('users')->result_array();
  }

}
