<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fileupload extends MY_Controller {
  public function __construct(){
    parent::__construct();
    if($this->authentication->logged_in() == FALSE || $this->authentication->is_admin() == FALSE){
      redirect('/login');
    }
  }
  //Display an overview page with the current file limit(s) and allowed file types
	public function index()
	{
    //Get all whitelist entrys
    $this->template_data['whitelist'] = $this->SettingsModel->all_filterTypes('whitelist');
    $this->template_data['whitelist_enabled'] = $this->SettingsModel->getSetting('whitelist_enabled');
    $this->template_data['blacklist'] = $this->SettingsModel->all_filterTypes('blacklist');
    $this->template_data['blacklist_enabled'] = $this->SettingsModel->getSetting('blacklist_enabled');
    $this->parser->parse('admin/header.php', $this->template_data);
    $this->parser->parse('admin/menu.php', $this->template_data);
    $this->parser->parse('admin/file_settings.php', $this->template_data);
    $this->parser->parse('admin/footer.php', $this->template_data);
  }

  public function settings(){
    if(!$this->input->post('type') == ''){
      switch ($this->input->post('type')) {
        case 'whitelist_item':
          //Add a new item to the whitelist
          //Get the item name
          $item_name = $this->input->post('whitelist_item');
          //Insert it into the databse if it doesn't already exists
          $this->SettingsModel->filterType_add('whitelist', $item_name);
          $this->session->successMessage[] = 'Whitelist entry successfully added!';
          $this->save_messages();
          redirect('admin/fileupload');
          break;
        case 'blacklist_item':
          //Add a new item to the whitelist
          //Get the item name
          $item_name = $this->input->post('blacklist_item');
          //Insert it into the databse if it doesn't already exists
          $this->SettingsModel->filterType_add('blacklist', $item_name);
          $this->session->successMessage[] = 'Blacklist entry successfully added!';
          $this->save_messages();
          redirect('admin/fileupload');
          break;
        default:
        redirect('admin/fileupload');
        break;
      }
    }else{
      redirect('admin/fileupload');
    }
  }

  public function enable_whitelist(){
    if($this->SettingsModel->getSetting('whitelist_enabled') == TRUE){
      $this->SettingsModel->update('whitelist_enabled',0);
    }else{
      $this->SettingsModel->update('whitelist_enabled', 1);
      $this->SettingsModel->update('blacklist_enabled', 0);
    }
  }
  public function enable_blacklist(){
    if($this->SettingsModel->getSetting('blacklist_enabled') == TRUE){
      $this->SettingsModel->update('blacklist_enabled', 0);
    }else{
      $this->SettingsModel->update('blacklist_enabled', 1);
      $this->SettingsModel->update('whitelist_enabled', 0);
    }
  }

  //Funciton to delte a whitelist or blacklist record
  public function delete(){
    $type = $this->uri->segment(4);
    //Check if we have a valid type
    if($type !== 'whitelist' && $type !== 'blacklist'){
      redirect('admin/fileupload');
    }

    //Get the whitelist / blacklist element
    $element = $this->uri->segment(5);

    //Check that it is an valid element
    if(!is_numeric($element)){
      redirect('admin/fileupload');
    }
    $element = intval($element);
    $element = round($element, 0);


    //Delete the element if it exists
    $this->SettingsModel->filterType_delete($type, $element);

    $this->successMessage[] = "Filter deleted successfuly!";
    $this->save_messages();

    redirect('admin/fileupload');

  }

}
