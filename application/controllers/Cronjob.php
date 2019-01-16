<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cronjob extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}

	public function index()
	{
    $this->load->library('Communication');

    $this->load->model('cronjobModel');

    //Check for premium subscription that are no longer valid
    //Get all subscriptions that are no longer valid
    $oldSubscriptions = $this->cronjobModel->getOldSubscriptions();

    //Send a notification to all those, whose premium subscription have ran out and deactivate the subscriptions
    foreach($oldSubscriptions as $subscription){
      $this->communication->send('subscription_nvalid', $subscription);
      //Set the user to a standard user again
      $this->cronjobModel->updateUser(array('email' => $subscription['receiver']), array('premium' => 0));
    }

    //Check for subscriptions that are about to end in less then one week
    $currentSubscriptions = $this->cronjobModel->getAlmostOldSubscriptions();

    //Send a notification mail
    foreach($currentSubscriptions as $subscription){
      $this->communication->send('subscription_renewalnotice', $subscription);
    }
	}

}
