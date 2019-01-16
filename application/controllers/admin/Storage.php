<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Storage extends MY_Controller{
  public function __construct(){
    parent::__construct();
    if($this->authentication->logged_in() == FALSE || $this->authentication->is_admin() == FALSE){
      redirect('/login');
    }
    $this->load->model(array('StorageInteraction', 'SettingsModel'));
  }

  public function index(){
    //Check if the user is logged in
      //Display all storage engines in an overview page
      $this->template_data['storage_engines'] = $this->StorageInteraction->allEngines();
      $this->parser->parse('admin/header.php', $this->template_data);
      $this->parser->parse('admin/menu.php', $this->template_data);
      $this->parser->parse('admin/storage_overview.php', $this->template_data);
      $this->parser->parse('admin/footer.php', $this->template_data);
  }

  public function enableStorageEngine(){
      //Check what engine we need to enable
      $engine = $this->uri->segment(4);
      //Enable it
      $this->StorageInteraction->enableStorage($engine);
      //Display a success message
      $this->session->set_userdata('success', 'Storage engine successfully switched!');

      //redirect to the overview page
      redirect('admin/storage');
  }

  public function settings(){
      //Check for what engine we need to load the settings
      $engine = $this->uri->segment(4);
      //Check if the engine exists
      if($this->StorageInteraction->engineExists($engine) == TRUE){
        $engine = $this->StorageInteraction->engineDetails($engine);
        $this->template_data['engine'] = $engine;

        //Get all setting items
        require_once(APPPATH.'libraries/storage/'.$engine['library_name'].'/init.php');
        $engine = new $engine['library_name']();

        $allSettings = array();
        foreach($engine->allSettings() as $key => $setting){
          $allSettings[] = $setting['name'];
        }

        //Check if the form has been submitted
        if(isset($_POST)){
          $update = array();
          //Check if we need to update an item
          foreach($_POST as $key => $post){
            if(in_array($key, $allSettings)){
              $update[] = array(
                'name' => $this->template_data['engine']['library_name'].'_'.$key,
                'data' => $post
              );
            }
          }
          //Update all submitted data in the database
          if(!empty($update)){
            //Check that the max storage size is not null
            $this->session->set_userdata('success', 'Settings successfully updated!');
            $this->SettingsModel->updateLibrarySettings($update);
          }
        }

        $this->template_data['engine_settings'] = $engine->allSettings();

        foreach($this->template_data['engine_settings'] as $key => $setting){
          $this->template_data['engine_settings'][$key]['value'] = $this->SettingsModel->getSetting($this->template_data['engine']['library_name'].'_'.$setting['name']);
        }

        $this->parser->parse('admin/header.php', $this->template_data);
        $this->parser->parse('admin/menu.php', $this->template_data);
        $this->parser->parse('admin/storage_settings.php', $this->template_data);
        $this->parser->parse('admin/footer.php', $this->template_data);
      }
  }
}
