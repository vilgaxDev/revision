<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthenticationModel extends CI_Model{
  private $limit;

  public function __construct(){
    parent::__construct();
  }

  //Returns true if an user with a specific id exists
  public function reset_password($email, $reset_code){
    $this->db->where('email', $email);
    $this->db->update('users', array('pw_reset_code' => $reset_code));
    $this->db->where('email', $email);
    $this->db->select('firstname, lastname, pw_reset_code');
    return $this->db->get('users')->row_array();
  }

  //Checks if the reset code exists
  public function reset_code($code, $new_password=null){
      $this->db->where('pw_reset_code', $code);
      if($this->db->count_all_results('users') == 1){
        if(!$new_password == NULL){
          $this->db->where('pw_reset_code', $code);
          $this->db->update('users', array('pw_reset_code' => NULL, 'password' => $new_password));
          return true;
        }else{
          return true;
        }
      }
      return false;
  }

  public function session_data($sid){
    $this->db->where('sid', $sid);
    $this->db->limit(1);
    return $this->db->get('user_sessions')->row_array();
  }

  public function insertSession($data){
    $this->db->insert('user_sessions', $data);
    return true;
  }

  public function getUser($username){
    $this->db->select('email, password, email, id as uid');
    $this->db->where('email', $username);
    return $this->db->get('users')->row_array();
  }

  public function checkUniqueSid($sid){
    $this->db->select('id');
    $this->db->where('sid', $sid);
    if($this->db->count_all_results('user_sessions') == 0){
      return true;
    }
    return false;
  }

  public function deleteSession($ip){
    $this->db->where('ip_address', $ip);
    $this->db->delete('user_sessions');
    return true;
  }

  public function userInformation($uid){
    $this->db->select('users.id, email, email, firstname, lastname, users.group_id, premium, premium_until, active');
    $this->db->where('users.id', $uid);
    return $this->db->get('users')->row_array();
  }

  public function check_member_of($uid, $type){
    //Two types: customer, staff, administrative
    $this->db->where('id', $uid);
    $user = $this->db->get('users')->row_array();

    $this->db->where('id', $user['group_id']);
    $group = $this->db->get('user_groups')->row_array();

    switch ($type) {
      case 'administrative':
        if($group['admin'] == TRUE){
          return true;
        }
        break;
      case 'staff':
        if($group['admincp'] == TRUE){
          return true;
        }
        break;
    }
    return false;
  }

  public function update($uid, $data){
    if(isset($data['group'])){
      $group = $data['group'];
      unset($data['group']);
    }
    $this->db->where('id', $uid);
    $this->db->update('users', $data);
  }

  public function checkUnique($field, $data, $id=NULL){
    //If the user_id variable is present only check where the id !== $id
    if($id !== NULL && is_numeric($id)){
      $this->db->where('id !=', $id);
    }
    $this->db->select('id');
    $this->db->where($field, $data);
    if($this->db->count_all_results('users') == 0){
      return true;
    }
    return false;
  }

  public function createUser($credentials){
    $this->db->insert('users', $credentials);
    return $this->db->insert_id();
  }

  public function setUserGroup($user_id, $group){
    //Check if a database recrod already exits
      $this->db->where('id', $user_id);
      $this->db->update('users', array('group_id' => $group));
    return true;
  }

  public function delete($id){
    $this->db->where('id', $id);
    $this->db->delete('users');
    return true;
  }

}
