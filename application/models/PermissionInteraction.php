<?php defined('BASEPATH') OR exit('No direct script access allowed');

class PermissionInteraction extends CI_Model{
  public function __construct(){
    parent::__construct();
  }

  //Returns true if an user group exists
  public function groupExists($group_id){
      $this->db->where('id', $group_id);
      if($this->db->count_all_results('user_groups') == 1){
        return true;
      }
      return false;
  }

  public function groupPermissions($group_id){
    $this->db->where('id', $group_id);
    $this->db->select('*');
    return $this->db->get('user_groups')->row_array();
  }

  public function updatePermissions($group_id, $update){
    $this->db->where('id', $group_id);
    $this->db->update('user_groups', $update);
    return true;
  }

  public function defaultGroup(){
    $this->db->where('default', 1);
    $data = $this->db->get('user_groups')->row_array();
    return $data['id'];
  }

  public function setDefaultGroup($group_id){
    $this->db->where('id', $group_id);
    $data = array('default' => 1);
    $this->db->update('user_groups', $data);
    //Make sure that there are no other default groups
    $this->db->where('id !=', $group_id);
    $data = array('default' => 0);
    $this->db->update('user_groups', $data);

    return true;
  }

  public function createGroup($group_name){
    $data = array('name' => $group_name);
    $this->db->insert('user_groups', $data);
    return $this->db->insert_id();
  }

  public function deleteGroup($group_id){
    $this->db->where('id', $group_id);
    $this->db->delete('user_groups');
    return true;
  }

}
