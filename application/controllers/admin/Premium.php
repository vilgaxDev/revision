<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Premium extends MY_Controller{

  public function __construct(){
    parent::__construct();

    //Check if the user is logged in and an administrator
    if($this->authentication->logged_in() == FALSE || $this->authentication->is_admin() == FALSE){
      redirect('/');
    }
  }

  public function index(){
    //Check if changes have been made
    if(isset($_POST['form_submitted'])){
      $error = false;

      //Get data from all setting items
      $product_price = $this->input->post('product_price');
      $product_price = str_replace(',', '.', $product_price);
      $product_currency = $this->input->post('product_currency');

      $advertising_free = $this->input->post('advertising_free');

      $storage_capacity = $this->input->post('storage_max_capacity');
      $storage_capacity = str_replace(',', '.', $storage_capacity);
      $storage_max_files = $this->input->post('storage_max_files');
      $storage_max_folders = $this->input->post('storage_max_folders');

      //Verify the data if needed and update the database record
      if(is_numeric($product_price) && $product_price > 0){
        //Round the price and insert it into the database
        $product_price = round($product_price,2);
        $this->SettingsModel->update('premium_price', $product_price);
      }else{
        $error = true;
      }

      //Verify that the currency is a valid one
      if(valid_currency($product_currency)){
        $this->SettingsModel->update('premium_currency', $product_currency);
      }else{
        $error = true;
      }

      //Check if storage capacity is an positive number or -1
      if($storage_capacity >= -1 && is_numeric($storage_capacity)){
        $storage_capacity = round($storage_capacity, 2);
        $this->SettingsModel->update('premium_storage_capacity', $storage_capacity);
      }else{
        $error = true;
      }

      //Check if storage max_files is an positive integer or -1
      if($storage_max_files >= -1){
        $storage_max_files = round($storage_max_files,0);
        $this->SettingsModel->update('premium_storage_max_files', $storage_max_files);
      }else{
        $error = true;
      }

      //Check if storage max_folders is an positive integer -1
      if($storage_max_folders >= -1){
        $storage_max_folders = round($storage_max_folders,0);
        $this->SettingsModel->update('premium_storage_max_folders', $storage_max_folders);
      }else{
        $error = true;
      }

      //Check if advertising_free is set
      if($advertising_free == 1){
        $this->SettingsModel->update('premium_advertising_free', 1);
      }else{
        $this->SettingsModel->update('premium_advertising_free', 0);
      }

      //Check if wee ned to display an error
      if($error == true){
        $this->errorMessage[] = "Not all changes have been saved!";
      }else{
        $this->successMessage[] = "All changes have been saved successfuly!";
      }
    }

    //Get all settings from the database
    $this->template_data['premium_enabled'] = $this->SettingsModel->getSetting('premium_enabled');
    $this->template_data['premium_description'] = $this->SettingsModel->getSetting('premium_description');
    $this->template_data['premium_price'] = $this->SettingsModel->getSetting('premium_price');
    $this->template_data['premium_currency'] = $this->SettingsModel->getSetting('premium_currency');

    $this->template_data['premium_advertising_free'] = $this->SettingsModel->getSetting('premium_advertising_free');
    $this->template_data['premium_storage_capacity'] = $this->SettingsModel->getSetting('premium_storage_capacity');
    $this->template_data['premium_storage_max_folders'] = $this->SettingsModel->getSetting('premium_storage_max_folders');
    $this->template_data['premium_storage_max_files'] = $this->SettingsModel->getSetting('premium_storage_max_files');

    //Display the template
    $this->parser->parse('admin/header.php', $this->template_data);
    $this->parser->parse('admin/menu.php', $this->template_data);
    $this->parser->parse('admin/premiumFeatures.php', $this->template_data);
    $this->parser->parse('admin/footer.php', $this->template_data);
  }

  public function enable_premium(){
    //Get the current status of the premium feature. This does not affect users that are already premium users
    $current_status = $this->SettingsModel->getSetting('premium_enabled');

    if($current_status == 1){
      $this->SettingsModel->update('premium_enabled', 0);
    }else{
      $this->SettingsModel->update('premium_enabled', 1);
    }
    return true;
  }
}
