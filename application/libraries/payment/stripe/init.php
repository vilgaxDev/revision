<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stripe{
  public $settings, $id;
  public function __construct(){

    $this->settings = array(
      array(
        'type' => 'text',
        'name' => 'secret_key',
        'display_name' => 'Secret Key',
        'description' => 'The secret key is used to identify your account when creating the subscription. It should never be published and only be known to you!'),
      array(
        'type' => 'text',
        'name' => 'public_key',
        'display_name' => 'Public Key',
        'description' => 'The public key is used to create the token needed to then create the charge. It is published in the source code of your website.'),
      array(
        'type' => 'text',
        'name' => 'logo_url',
        'display_name' => 'Payment Engine Logo',
        'description' => 'The logo helps the customer to decide which payment gatway he\'s going to use. You can use either an external or internal url')
    );
  }

  public function init($data){
    //Save the settings to a global variable
    $this->settings = $data;
    require_once(APPPATH.'libraries/payment/stripe/stripe_lib/init.php');

    \Stripe\Stripe::setApiKey($this->settings['secret_key']);

    return true;
  }

  public function start_payment( $amount, $currency, $trasnaction_id){
    $this->amount = $amount;
    $this->currency = $currency;
    $this->transaction = $trasnaction_id;
    require_once('stripe_lib/init.php');
  }

  public function finish_payment($post, $amount, $currency, $trasnaction_id){
    $this->amount = $amount;
    $this->currency = $currency;

    try{
      $charge = \Stripe\Charge::create(array(
        'source'  => $post['reservation']['stripe_token'],
        'amount'   => $amount*100,
        'currency' => $this->currency
      ));
      //Check if the payment was successful
      if($charge->paid == TRUE){
        return array(
          'data' => $charge->id,
          'transaction_id' => $post['transaction_id'],
          'status' => 'success'
        );
      }else{
        return array(
          'transaction_id' => $post['transaction_id'],
          'status' => 'declined'
        );
      }
    }catch(Exception $e){
      return array(
        'transaction_id' => $post['transaction_id'],
        'status' => 'declined'
      );
    }
    return false;
  }

  public function refund($transaction){
    $charge_id = $transaction['data'];
    try{
      $charge = \Stripe\Refund::create(array(
        'charge'  => $charge_id,
        'amount'   => $transaction['amount']*100,
        'reason' => 'requested_by_customer'
      ));
      return true;
    }catch(Exception $e){
      return false;
    }
  }

  public function output(){
    ob_start();
    require_once(APPPATH.'libraries/payment/stripe/payment_template.php');
    $template = ob_get_contents();
    ob_get_clean();
    return $template;
  }
}

 ?>
