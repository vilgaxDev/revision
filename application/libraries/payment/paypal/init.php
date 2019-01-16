<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;

class Paypal{
  public $settings, $id;
  public function __construct(){

    $this->settings = array(
      array(
        'type' => 'text',
        'name' => 'client_id',
        'display_name' => 'Client ID',
        'description' => ''),
      array(
        'type' => 'text',
        'name' => 'secret_key',
        'display_name' => 'Secret Key',
        'description' => ''),
      array(
        'type' => 'text',
        'name' => 'logo_url',
        'display_name' => 'Payment Engine Logo',
        'description' => '')
    );
  }

  public function init($data){
    //Save the settings to a global variable
    $this->settings = $data;

    try{
      require __DIR__  . '/lib/autoload.php';

      $this->apiContext = new \PayPal\Rest\ApiContext(
      new \PayPal\Auth\OAuthTokenCredential(
          $this->settings['client_id'],     // ClientID
          $this->settings['secret_key']      // ClientSecret
      ));
      $this->apiContext->setConfig(array('mode' => 'live'));
      
    }catch(Exception $e){
      return false;
    }
    return true;

  }

  public function start_payment($amount_num, $currency, $transaction_id){
    $payer = new Payer();
    $payer->setPaymentMethod("paypal");
    // ### Itemized information
    // (Optional) Lets you specify item wise
    // information
    $item1 = new Item();
    $item1->setName('Premium Subscription')
        ->setCurrency($currency)
        ->setQuantity(1)
        ->setSku("1") // Similar to `item_number` in Classic API
        ->setPrice($amount_num);

    $itemList = new ItemList();
    $itemList->setItems(array($item1));

    // ### Amount
    // Lets you specify a payment amount.
    // You can also specify additional details
    // such as shipping, tax.
    $amount = new Amount();
    $amount->setCurrency($currency)
        ->setTotal($amount_num);

    // ### Transaction
    // A transaction defines the contract of a
    // payment - what is the payment for and who
    // is fulfilling it.
    $transaction = new Transaction();
    $transaction->setAmount($amount)
        ->setItemList($itemList)
        ->setDescription("Premium Subscription")
        ->setInvoiceNumber($transaction_id);

    // ### Redirect urls
    // Set the urls that the buyer must be redirected to after
    // payment approval/ cancellation.
    $redirectUrls = new RedirectUrls();
    $redirectUrls->setReturnUrl(site_url('premium/callback/'.$this->settings['engine_id']))
        ->setCancelUrl(site_url('premium/callback/'.$this->settings['engine_id']));
    // ### Payment
    // A Payment Resource; create one using
    // the above types and intent set to 'sale'
    $payment = new Payment();
    $payment->setIntent("sale")
        ->setPayer($payer)
        ->setRedirectUrls($redirectUrls)
        ->setTransactions(array($transaction));

        try {
    $payment->create($this->apiContext);
} catch (Exception $ex) {
  echo $ex->getCode(); // Prints the Error Code
  echo $ex->getData(); // Prints the detailed error message
}
    redirect($payment->getApprovalLink());
  }

  public function finish_payment($post, $amount, $currency, $trasnaction_id){
    echo '<pre>';
    print_r($post);exit();
  }

  public function callback(){
    if(!isset($_GET['PayerID'])){return false;}
    try{
      // Get the payment Object by passing paymentId
      // payment id was previously stored in session in
      // CreatePaymentUsingPayPal.php
      $paymentId = $_GET['paymentId'];
      $payment = Payment::get($paymentId, $this->apiContext);
      // ### Payment Execute
      // PaymentExecution object includes information necessary
      // to execute a PayPal account payment.
      // The payer_id is added to the request query parameters
      // when the user is redirected from paypal back to your site
      $execution = new PaymentExecution();
      $execution->setPayerId($_GET['PayerID']);

      try{
        // Execute the payment
        // (See bootstrap.php for more on `ApiContext`)
        $result = $payment->execute($execution, $this->apiContext);

        //Verify that the payment was successful
        if($result->state == "approved"){
          $transactions = $result->getTransactions();
          $transactions = $transactions[0];
          return array(
            'where' => array('transaction_id' => $transactions->invoice_number),
            'update' => array('data' => $result->id),
            'status' => 'success'
          );
        }
      }catch(Exception $e){
        return false;
      }
    }catch(Exception $e){
      return false;
    }
    return false;
  }

  public function output(){
    ob_start();
    require_once(APPPATH.'libraries/payment/paypal/payment_template.php');
    $template = ob_get_contents();
    ob_get_clean();
    return $template;
  }
}

 ?>
