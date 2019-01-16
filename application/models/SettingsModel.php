<?php defined('BASEPATH') OR exit('No direct script access allowed');

class SettingsModel extends CI_Model{
  public function __construct(){
    parent::__construct();
    $this->load->database();
  }

  public function getSetting($data){
    //Check if we have an array
    if(is_array($data) == TRUE){
      //It is an array
      foreach($data as $item){
          $this->db->where('name', $item);
        $tmp = $this->db->get('setting_items')->row_array();
        $results[$item] = $tmp['content'];
      }

      return $results;
    }else{
      //It is not an array
      $this->db->where('name', $data);
      $result = $this->db->get('setting_items')->row_array();
      if(isset($result['content'])){
        return $result['content'];
      }else{
        return false;
      }
    }
  }

  public function create($name, $data){
    $insert = array(
      'name' => $name,
      'content' => $data
    );
    $this->db->insert('setting_items', $insert);
    return true;
  }

  public function update($name, $data){
    $this->db->where('name', $name);
    if($this->db->count_all_results('setting_items') == 1){
      $this->db->where('name', $name);
      $this->db->update('setting_items', array('content' => $data));
    }else{
      $this->db->insert('setting_items', array('name' => $name, 'content' => $data));
    }
    return true;
  }

  public function updateLibrarySettings($update_data){
    foreach($update_data as $data){
      $this->db->where('name', $data['name']);
      if(!$this->db->count_all_results('setting_items')  == 1){
        $this->db->insert('setting_items', array('name' => $data['name']));
      }
      $this->db->where('name', $data['name']);
      $this->db->update('setting_items', array('content' => $data['data']));
    }
    return true;
  }

  public function filterType_add($type, $name){
    //Check if the record already exists
    $this->db->where('name', 'contentTypeFilter_'.$type);
    $this->db->where('content', $name);
    if($this->db->count_all_results('setting_items') == 0){
      $this->db->insert('setting_items', array('name' => 'contentTypeFilter_'.$type, 'content' => $name));
      return true;
    }
      return false;
  }

  public function all_filterTypes($type){
    $this->db->where('name', 'contentTypeFilter_'.$type);
    return $this->db->get('setting_items')->result_array();
  }

  public function filterType_delete($type, $id){
    $this->db->where('name', 'contentTypeFilter_'.$type);
    $this->db->where('id', $id);
    $this->db->delete('setting_items');
    return true;
  }

  public function emailSettings(){
    $all_settings = array('email_notifications' => '', 'email_hostname' => '', 'email_username' => '', 'email_password' => '', 'email_address' => '', 'email_display_name' => '');
    $this->db->or_where('name', 'email_notifications');
    $this->db->or_where('name', 'email_hostname');
    $this->db->or_where('name', 'email_username');
    $this->db->or_where('name', 'email_password');
    $this->db->or_where('name', 'email_address');
    $this->db->or_where('name', 'email_display_name');
    $this->db->select('name, content');

    $results = $this->db->get('setting_items')->result_array();

    foreach($results as $result){
      $all_settings[$result['name']] = $result['content'];
    }
    return $all_settings;
  }

  public function emailTemplates(){
    $this->db->like('name', 'emailmsg_');
    $results = $this->db->get('setting_items')->result_array();
    $return = $this->allEmailTemplates();
    foreach($results as $key => $result ){
      $return[$result['name']] = $result['content'];
    }
    return $return;
  }

  public function updateEmailTemplates($data){
    $allowed = $this->allEmailTemplates();
    $this->db->trans_start();
    foreach($data as $key => $val){
      if(isset($allowed[$key])){
        $this->db->where('name', $key);
        if($this->db->count_all_results('setting_items') == 1){
          $this->db->where('name', $key);
          $this->db->update('setting_items', array('content' => $val));
        }else{
          $this->db->insert('setting_items', array('name' => $key, 'content' => $val));
        }
      }
    }
    $this->db->trans_complete();
  }

  private function allEmailTemplates(){
    $all_templates = array(
      'emailmsg_registration_subject',
      'emailmsg_registration_message',
      'emailmsg_account_activated_message',
      'emailmsg_account_activated_subject',
      'emailmsg_user_forgotpw_message',
      'emailmsg_user_forgotpw_subject',
      'emailmsg_folder_shared_message',
      'emailmsg_folder_shared_subject',
      'emailmsg_file_shared_message',
      'emailmsg_file_shared_subject',
      'emailmsg_subscription_renewalnotice_message',
      'emailmsg_subscription_renewalnotice_subject',
      'emailmsg_subscription_nvalid_message',
      'emailmsg_subscription_nvalid_subject',
      'emailmsg_subscription_purchased_subject',
      'emailmsg_subscription_purchased_message'
    );
    $return = array();
    foreach($all_templates as $template){
      $return[$template] = '';
    }
    return $return;
  }

}
