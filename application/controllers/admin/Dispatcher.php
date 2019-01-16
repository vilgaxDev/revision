<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dispatcher extends MY_Controller {

	public function index()
	{
    //Check if the user is logged in or not
    if($this->authentication->logged_in() == TRUE){
      redirect('admin/dashboard');
    }else{
      redirect('admin/login');
    }
  }

}
