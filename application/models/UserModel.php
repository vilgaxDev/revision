<?php defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends CI_Model{
  public function __construct(){
    parent::__construct();
  }

  //Returns true if an user with a specific id exists
  public function userExists($id){
    if(is_array($id)){
      $this->db->where($id);
    }else{
      $this->db->where('id', $id);
    }
    $this->db->select('id');
    if($this->db->count_all_results('users') == 1){
      return true;
    }
    return false;
  }

  public function getUser($id){
    if(is_array($id)){
      $this->db->where($id);
    }else{
      $this->db->where('id', $id);
    }
    $this->db->select('id, firstname, lastname, email, premium, premium_until, group_id,active');
    return $this->db->get('users')->row_array();
  }

  //Deletes an user and all releated data
  public function deleteUser($user_id){
    //Delete the user
    $this->db->where('id', $user_id);
    $this->db->delete('users');
    return true;
  }


  //Check how many pages are there for the admin customer pagination
  public function total_pages_customers($search_term, $limit=15){
    //If a search term is defined
    if(!is_null($search_term)){
      $this->db->or_like('firstname', $search_term);
      $this->db->or_like('lastname', $search_term);
      $this->db->or_like('email', $search_term);
    }
    $count = ceil($this->db->count_all_results('users')/$limit);
    if($count > 0){
      return $count;
    }else{
      return 1;
    }
  }


  //List all customers
  public function listCustomers($offset, $search_term, $limit=15){
    $offset = $offset*$limit;
    if(!is_null($search_term)){
      $this->db->or_like('firstname', $search_term);
      $this->db->or_like('lastname', $search_term);
      $this->db->or_like('email', $search_term);
    }
    $this->db->offset($offset);
    $this->db->limit($limit);
    return $this->db->get('users')->result_array();
  }


  public function getUserGroups($where=null){
    if(!empty($where)){
      foreach($where as $key => $val){
        $this->db->where($key, $val);
      }
    }
    return $this->db->get('user_groups')->result_array();
  }

  public function activationCode($code){
    $this->db->where('activation_code', $code);
    if($this->db->count_all_results('users') == 1){
      return true;
    }
    return false;
  }

  public function activateUser($code){
    $this->db->where('activation_code', $code);
    $this->db->update('users', array('activation_code' => '', 'active' => 1));
    return true;
  }

  public function getUserSearch($search){
    $this->db->where($search);
    return $this->db->get('users')->row_array();
  }


}
