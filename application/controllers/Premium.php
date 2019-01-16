<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Premium extends MY_Controller {
	public function __construct(){
		parent::__construct();
    $this->load->model('payment');

		//Check if premium features are enabled
		if($this->SettingsModel->getSetting('premium_enabled') == 0){
			$this->errorMessage[] = 'Premium Features currently not available';
			$this->save_messages();
			redirect('user/account');
		}
		if($this->authentication->logged_in() == FALSE){
			redirect('/');
		}

  }

  public function buy(){
    redirect('premium/step1');
  }

  public function step1(){
		if($this->authentication->logged_in() == FALSE){
			redirect('/');
		}
    //Get all payment libraries
    $this->template_data['payment_services'] = $this->payment->getPaymentServices(array('active' => 1));

		//Get the image these payment services are using as logo
		foreach($this->template_data['payment_services'] as $key =>$payment_service){
			$this->template_data['payment_services'][$key]['logo_url'] = $this->SettingsModel->getSetting('payment_gw_'.$payment_service['library_name'].'_logo_url');
		}

		$this->template_data['payment_durations'] = $this->payment->getPaymentDurations();

		//Get all settings to display on the purchase page
		$this->template_data['features'] = $this->SettingsModel->getSetting(array('premium_storage_max_files', 'premium_storage_max_folders', 'premium_storage_capacity', 'storage_max_files', 'storage_max_folders', 'storage_capacity', 'premium_price', 'premium_currency'));
		$this->template_data['features']['premium_price'] = number_format($this->template_data['features']['premium_price'], 2, '.', '');
    $this->parser->parse('user/header_loggedin.php', $this->template_data);
		$this->parser->parse('user/payment/step1.php', $this->template_data);
		$this->parser->parse('user/footer.php', $this->template_data);
  }

	public function step2(){
		if($this->authentication->logged_in() == FALSE){
			redirect('/');
		}
		//Get all settings
		$price = $this->SettingsModel->getSetting('premium_price');
		$currency = $this->SettingsModel->getSetting('premium_currency');

		//Check that both options are set. Otherwise the payment process will not work
		if($price <= 0 || $currency == ''){
			$this->errorMessage[] = "Premium features not available at the moment. Please try again later!";
			$this->save_messages();
			redirect('premium/step1');
		}

		//Check if the payment engine exists
		$engine_id = $this->input->post('payment_service');
		$duration = $this->input->post('duration');

		if(!is_numeric($duration)){
			redirect('premium/step1');
		}
		$duration = round($duration, 0);

		if($this->payment->paymentTermExists(array('months' =>$duration)) == false){
			$duration = 1;
			$discount = 0;
		}else{
			$term = $this->payment->getPaymentTerm(array('months' => $duration));
			$duration = $term['months'];
			$discount = $term['discount'];
		}
		$price = ($price * $duration) - ($price*$duration*$discount/100);

		if(!$this->payment->engineExists($engine_id) == TRUE){
			redirect('premium/step1');
		}

		//Try to load the library
		$engine = $this->payment->engineDetails($engine_id);

		if(!file_exists(APPPATH.'libraries/payment/'.$engine['library_name'].'/init.php')){
			//IDEA add error message here
			redirect('premium/step1');
		}

		try{
			//Load the library
			require_once(APPPATH.'libraries/payment/'.$engine['library_name'].'/init.php');
			$payment = new $engine['library_name']();
		}catch(Exception $e){
			redirect('premium/step1');
		}

		//Get all engine settings
		$settings = $this->payment->getSettings($engine['library_name']);
		$settings['engine_id'] = $engine_id;
		if($payment->init($settings) == FALSE){
			redirect('premium/step1');
		}
		//Create a transaction id and save it as a pending payment
		$transaction_id = uniqid('PAY-');
		$this->payment->insert_transaction($engine_id, $price, $duration, $transaction_id, NULL, 0, $this->authentication->uid);
		//Start the payment process
		$payment->start_payment($price, $currency, $transaction_id);

		//Allow the payment engine to output the necessary details
		$this->template_data['payment_service'] = $payment->output();
		$this->parser->parse('user/header_loggedin.php', $this->template_data);
		$this->parser->parse('user/payment/step2.php', $this->template_data);
		$this->parser->parse('user/footer.php', $this->template_data);
	}

	public function step3(){
		if($this->authentication->logged_in() == FALSE){
			redirect('/');
		}

		//Get all settings
		$price = $this->SettingsModel->getSetting('premium_price');
		$currency = $this->SettingsModel->getSetting('premium_currency');

		$transaction_id = $this->input->post('transaction_id');
		$transaction = $this->payment->get_transaction(array('transaction_id' => $transaction_id));

		$price = $transaction['duration'] * $price;
		//Check if there is a payment duration available
		$duration = $this->payment->getPaymentTerm(array('months' => $transaction['duration']));
		$price = $price - ($price*$duration['discount']/100);

		$engine_id = $transaction['payment_service'];


		//Check if the payment service exists
		if(!$this->payment->engineExists($engine_id)){
			redirect('premium/step1');
		}

		//Try to load the library
		$engine = $this->payment->engineDetails($engine_id);

		if(!file_exists(APPPATH.'libraries/payment/'.$engine['library_name'].'/init.php')){
			//IDEA add error message here
			redirect('premium/step1');
		}

		try{
			//Load the library
			require_once(APPPATH.'libraries/payment/'.$engine['library_name'].'/init.php');
			$payment = new $engine['library_name']();
		}catch(Exception $e){
			redirect('premium/step1');
		}

		//Get all engine settings
		$settings = $this->payment->getSettings($engine['library_name']);
		$settings['engine_id'] = $engine_id;
		if($payment->init($settings) == FALSE){
			redirect('premium/step1');
		}

		$result =$payment->finish_payment($_POST, $price, $currency, $this->authentication->uid);
		if(is_array($result)){
			switch($result['status']){
				case "success":
					//Payment succeeded
					//Try to update the transaction
					if($this->payment->update_transaction(
						array('user_id' => $this->authentication->uid, 'transaction_id' => $result['transaction_id']), //Where clause
						array('status' => 1, 'transaction_id' => $result['transaction_id'], 'data' => $result['data']))){ //Update data
							//Update successful
							//Set the user to a premium user

							//Check if the user already is a premium user
							if($this->authentication->premium == true){
								//Update the premium_until coloumn so that we "renew" his subscription
								$premium_left = $this->authentication->premium_until - time();
								if($premium_left > 0){
									$this->authentication->update($this->authentication->uid, array('premium' => 1, 'premium_until' => time()+$premium_left+(30*24*60*60*$transaction['duration'])));
								}else{
									$this->authentication->update($this->authentication->uid, array('premium' => 1, 'premium_until' => time()+(30*24*60*60*$transaction['duration'])));
								}
							}else{
								//Make him a premium user
								$this->authentication->update($this->authentication->uid, array('premium' => 1, 'premium_until' => time()+(30*24*60*60*$transaction['duration'])));
							}
							//Send the user a success mail
							$this->load->library('Communication');
							$email_data = array(
								'receiver' => $this->authentication->email,
								'firstname' => $this->authentication->firstname
							);
							$this->communication->send('subscription_purchased', $email_data);
							redirect('premium/success');
						}
					break;

				case "declined":
					//Payment failed
					//Try to update the transaction
					$this->payment->update_transaction(
						array('transaction_id' => $result['transaction_id'], 'user_id' => $this->authentication->uid), //Where clause
						array('status' => 3)); //Update data
							//Update successful
							//Set the user to a premium user
							redirect('premium/failed');
						break;

				case "callback":
					//PayPal for example verifies the payment using a callback rather than a direct response

					break;

			}
			redirect('premium/failed');
		//Result has to be an array. Otherwise the payment has failed
		}else{
			//Payment failed
			redirect('premium/failed');
		}

	}

	public function callback(){
		//Verify the payment response
		//This is the only function in this clas which doesn't require a logged in user

		//Get the payment library
		$engine_id = $this->uri->segment(3);

		//Check if the payment service exists
		if(!$this->payment->engineExists($engine_id)){
			redirect('premium/step1');
		}

		//Load the library details
		$engine = $this->payment->engineDetails($engine_id);

		//Try to load the library
		try{
			//Load the library
			require_once(APPPATH.'libraries/payment/'.$engine['library_name'].'/init.php');

			//Create a new instance of that library
			$payment = new $engine['library_name']();

			//Get all engine settings
			$settings = $this->payment->getSettings($engine['library_name']);
			//Pass the engine id just in case
			$settings['engine_id'] = $engine_id;
			//Run the init function of the library. Without that it won't work
			$payment->init($settings);

			//Try to call the callback function of the library and save the result in the variable
			$result = $payment->callback();


			//Verify what result we've got
			if(is_array($result)){

				//Check what result we've got
				switch ($result['status']) {
					//Transaction can be marked as payed
					case 'success':
					$result['update']['status'] = 1;
						$this->payment->update_transaction($result['where'], $result['update']);
						$transaction = $this->payment->get_transaction($result['where']);
						//The user is a premium user now
						$this->authentication->update($this->authentication->uid, array('premium' => 1, 'premium_until' => time()+(30*24*60*60*$transaction['duration'])));
						break;

				}
				//Redirect the user to the dasboard and set a success message
				$this->successMessage[] = "The payment was successful. You're now a premium user!";
				redirect('/');
			}else{
				redirect('premium/step1');
			}
		}catch(Exception $e){
			//If there was some kind of exception during the process
			redirect('premium/step1');
		}

	}

	public function success(){
		$this->successMessage[] = 'Premium subscription was activated successfully for your account!';
		$this->save_messages();
		redirect('user/account');
	}

}
