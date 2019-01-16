<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends CI_Model{
  public function __construct(){
    parent::__construct();
  }

  public function getPaymentServices($filter = null){
    if(is_array($filter)){
      $this->db->where($filter);
    }
    return $this->db->get('payment_services')->result_array();
  }

  public function engineExists($engine){
    $this->db->where('id', $engine);
    if($this->db->count_all_results('payment_services') == 1){
      return true;
    }
    return false;
  }

  public function engineDetails($engine){
    $this->db->where('id', $engine);
    return $this->db->get('payment_services')->row_array();
  }

  public function updateEngine($engine, $update){
    $this->db->where('id', $engine);
    $this->db->update('payment_services', $update);
    return true;
  }

  public function getSettings($library){
    $this->db->like('name', 'payment_gw_'.$library.'_', 'after');
    $result = $this->db->get('setting_items')->result_array();
    $return = array();
    foreach($result as $res){
      $return[str_replace('payment_gw_'.$library.'_', '' ,$res['name'])] = $res['content'];
    }
    return $return;
  }

  public function addSetting($library, $setting){
    $this->db->insert('setting_items', array('name' => 'payment_gw_'.$library.'_'.$setting, 'content' => ''));
    return true;
  }

  public function updateSettings($library, $settings){
    foreach($settings as $key => $setting){
      $this->db->where('name', 'payment_gw_'.$library.'_'.$key);
      $this->db->update('setting_items', array('content' => $setting));
    }
    return true;
  }

  public function insert_transaction($engine_id, $amount, $duration, $transaction_id, $data, $status, $uid){
    $data = array(
      'user_id' => $uid,
      'payment_service' => $engine_id,
      'amount' => $amount,
      'data' => $data,
      'duration' => $duration,
      'status' => $status,
      'transaction_id' => $transaction_id,
      'time' => time()
    );
    $this->db->insert('payment_transactions', $data);
    return true;
  }

  public function update_transaction($where, $update){
    $this->db->where($where);
    if($this->db->count_all_results('payment_transactions') == 1){
      $this->db->where($where);
      $this->db->update('payment_transactions', $update);
      return true;
    }
    return false;
  }

  public function get_transaction($where){
    $this->db->where($where);
    return $this->db->get('payment_transactions')->row_array();
  }

  public function get_transactions($where){
    $this->db->where($where);
    $this->db->join('payment_services', 'payment_services.id = payment_transactions.payment_service');
    $this->db->select('payment_transactions.id, time, amount, payment_transactions.status, payment_services.display_name as payment_method_name');
    $this->db->order_by('payment_transactions.id', 'DESC');
    return $this->db->get('payment_transactions')->result_array();
  }

  public function last_transaciton_detail($uid){
    $this->db->where('user_id', $uid);
    $this->db->where('payment_transactions.status', 1);
    $this->db->join('payment_services', 'payment_services.id = payment_transactions.payment_service');
    $this->db->select('transaction_id, payment_services.public_display_name as payment_method_display_name, time,');
    $this->db->order_by('payment_transactions.id', 'DESC');
    $this->db->limit(1);
    return $this->db->get('payment_transactions')->row_array();
  }

  public function delete_transaction($where){
    $this->db->where($where);
    $this->db->delete('payment_transactions');
    return true;
  }

  public function getPaymentDurations(){
    $this->db->order_by('months');
    return $this->db->get('payment_durations')->result_array();
  }

  public function getPaymentTerm($where){
    $this->db->where($where);
    return $this->db->get('payment_durations')->row_array();
  }

  public function updatePaymentTerm($id, $data){
    $this->db->where('id', $id);
    $this->db->update('payment_durations', $data);
    return true;
  }

  public function deletePaymentTerm($id){
    $this->db->where('id', $id);
    $this->db->delete('payment_durations');
    return true;
  }

  public function paymentTermExists($where){
    $this->db->where($where);
    if($this->db->count_all_results('payment_durations') == 1){
      return true;
    }
    return false;
  }

  public function createPaymentTerm($data){
    $this->db->insert('payment_durations', $data);
    return true;
  }

  public function changeTermStatus($id){
    $this->db->where('id', $id);
    $result = $this->db->get('payment_durations')->row_array();
    if($result['enabled'] == 1){
      $update = array('enabled' => 0);
    }else{
      $update = array('enabled' => 1);
    }
    $this->db->where('id', $id);
    $this->db->update('payment_durations', $update);
    return true;
  }

}
