<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PaymentAdmin extends MY_Controller {
  public function __construct(){
    parent::__construct();
    if($this->authentication->logged_in() == FALSE || $this->authentication->is_admin() == FALSE){
      redirect('/login');
    }
    $this->load->model('payment');
  }

  public function index(){
    //Display an overview of all payment engines available
    $this->template_data['payment_engines'] = $this->payment->getPaymentServices();

    //Get all payment durations
    $this->template_data['payment_durations'] = $this->payment->getPaymentDurations();

    //Display the admin template
    $this->parser->parse('admin/header.php', $this->template_data);
    $this->parser->parse('admin/menu.php', $this->template_data);
    $this->parser->parse('admin/paymentOverview.php', $this->template_data);
    $this->parser->parse('admin/footer.php', $this->template_data);
  }


  public function change_status(){
    //Check if the payment engine exists
    $engine = $this->uri->segment(4);
    if(!$this->payment->engineExists($engine)){
      //Set an error message and then redirect the user to the payment engine overview page
      $this->errorMessage[] = 'The selected payment engine doesn\'t exist';
      $this->finish();
      redirect('admin/payment');
    }

    //Check what status the engine is in right now
    $details = $this->payment->engineDetails($engine);
    if($details['active'] == 1){
      //IDEA Add confirmation page here
      $this->payment->updateEngine($engine, array('active' => 0));
      $this->successMessage[] = 'Engine deactived successfuly!';
    }else{
      $this->payment->updateEngine($engine, array('active' => 1));
      $this->successMessage[] = 'Engine actived successfuly!';
    }
    $this->finish();
    redirect('admin/payment');
  }

  public function settings(){
    //Check if the payment engine exists
    $engine_id = $this->uri->segment(4);
    if(!$this->payment->engineExists($engine_id)){
      //Set an error message and then redirect the user to the payment engine overview page
      $this->errorMessage[] = 'The selected payment engine doesn\'t exist';
      $this->finish();
      redirect('admin/payment');
    }

    //Load the engine to display all available settings
    $details = $this->payment->engineDetails($engine_id);

    if(file_exists(APPPATH.'libraries/payment/'.$details['library_name'].'/init.php')){
      require_once(APPPATH.'libraries/payment/'.$details['library_name'].'/init.php');
      $engine = new $details['library_name']();
    }else{
      $this->errorMessage[] = 'Unable to load library ('.APPPATH.'libraries/payment/'.$details['library_name'].'/init.php)';
      $this->finish();
      redirect('admin/payment');
    }

    //Get all settings from the engine
    $this->template_data['settings'] = $engine->settings;

    //Check if there are already database entries for these settings
    $all_settings = $this->payment->getSettings($details['library_name']);
    //Indicator if new settings have been created
    $new_settings = false;

    foreach($this->template_data['settings'] as $key => $setting){
      if(!isset($all_settings[$setting['name']])){
        //Create the setting if if doesn't exist
        $this->payment->addSetting($details['library_name'], $setting['name']);
        $new_settings = true;
      }else{
        $this->template_data['settings'][$key]['value'] = $all_settings[$setting['name']];
      }
    }

    //Check if new settings have been loaded. If so, reload the page
    if($new_settings == true){
      redirect('admin/payment/settings/'.$details['id']);
    }

    //Check if the from has been submitted
    if(isset($_POST['control'])){
      foreach($this->template_data['settings'] as $setting){
        $form[$setting['name']] = $this->input->post($setting['name']);
      }
      $this->payment->updateSettings($details['library_name'], $form);
      $this->successMessage[] = 'Settings updated successfuly!';
      $this->finish();
      redirect('admin/payment/settings/'.$details['id']);
    }


    $this->template_data['engine'] = $details;
    //Display the page
    $this->parser->parse('admin/header.php', $this->template_data);
    $this->parser->parse('admin/menu.php', $this->template_data);
    $this->parser->parse('admin/paymentSettings.php', $this->template_data);
    $this->parser->parse('admin/footer.php', $this->template_data);
  }

  public function termInfo(){
    $payment_id = $this->uri->segment(4);
    $payment_id = round($payment_id, 0);

    $payment_term = $this->payment->getPaymentTerm(array('id' => $payment_id));

    if(!empty($payment_term)){
      echo json_encode($payment_term);
    }
  }

  public function updatePaymentTerm(){
    $id = $this->input->post('duration_id');

    //Check if the payment duration exists
    if($this->payment->paymentTermExists(array('id' => $id)) == false){
      redirect('admin/payment');
    }

    //Check if the user wants to delete the term
    if($this->input->post('delete_duration') == "1"){
      $this->payment->deletePaymentTerm($id);
      $this->successMessage[] = 'Payment duration deleted successfuly';
      $this->save_messages();
      redirecT('admin/payment');
    }

    //Update the data
    $duration = round($this->input->post('duration'), 0);
    $discount = round($this->input->post('discount'), 2);

    //Verify the data ranges
    if($duration < 0 || $duration > 12){
      $this->errorMessage[] = 'Data out of range';
      $this->save_messages();
      redirect('admin/payment');
    }
    if($discount < 0 || $discount > 100){
      $this->errorMessage[] = 'Data out of range';
      $this->save_messages();
      redirect('admin/payment');
    }

    //Update the data
    $this->payment->updatePaymentTerm($id, array('months' => $duration, 'discount' => $discount));
    $this->successMessage[] = 'Payment term updated successfuly';
    $this->save_messages();
    redirect('admin/payment');
  }

  public function createPaymentTerm(){
    $duration = round($this->input->post('duration'), 0);
    $discount = round($this->input->post('discount'), 2);

    //Verify the data ranges
    if($duration < 0 || $duration > 12){
      $this->errorMessage[] = 'Data out of range';
      $this->save_messages();
      redirect('admin/payment');
    }
    if($discount < 0 || $discount > 100){
      $this->errorMessage[] = 'Data out of range';
      $this->save_messages();
      redirect('admin/payment');
    }

    //Check that there is no term with the same duration
    if($this->payment->paymentTermExists(array('months' => $duration)) == true){
      $this->errorMessage[] = 'A payment term already exists with the same duration';
      $this->save_messages();
      redirect('admin/payment');
    }

    //Create the term
    $this->payment->createPaymentTerm(array('months' => $duration, 'discount' => $discount, 'enabled' => 1));
    $this->successMessage[] = 'Payment term created successfuly.';
    $this->save_messages();
    redirect('admin/payment');
  }

  public function paymentTermStatus(){
    $id = $this->uri->segment(4);
    //Check if the term exists
    if($this->payment->paymentTermExists(array('id' => $id)) == false){
      $this->errorMessage[] = 'Payment term does not exist';
      $this->save_messages();
      redirect('admin/payment');
    }
    //Update the term
    $this->payment->changeTermStatus($id);

    $this->successMessage[] = 'Term status changed successfuly';
    $this->save_messages();
    redirect('admin/payment');
  }

  public function refund(){
    $transaction_id = $this->uri->segment(4);
    $transaction = $this->payment->get_transaction(array('id' => $transaction_id));

    //Check if the transaction exists
    if(empty($transaction)){
      redirect('admin/dashboard');
    }

    //Get the payment service
    $payment_lib = $this->payment->engineDetails($transaction['payment_service']);

    //Load the engine
      require_once(APPPATH.'libraries/payment/'.$payment_lib['library_name'].'/init.php');
      $payment = new $payment_lib['library_name']();
      $payment->init($this->payment->getSettings($payment_lib['library_name']));
      if($payment->refund($transaction) == true){
        $this->authentication->update($transaction['user_id'], array('premium' => 0));
        $this->payment->update_transaction(array('id' => $transaction['id']), array('status' => 4));
        $this->successMessage[] = 'Transaction refunded and premium status revoked successfuly';
      }else{
        $this->errorMessage[] = 'Error. Payment not refunded';
      }
      $this->save_messages();
      redirect('admin/user/view/'.$transaction['user_id']);
  }

}
