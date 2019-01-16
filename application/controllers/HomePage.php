<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HomePage extends MY_Controller {
	public function __construct(){
		parent::__construct();
	}
	public function index()
	{
		if($this->authentication->logged_in() == TRUE){
			redirect('/dashboard');
		}else{
			redirect('/login');
		}
}
}
