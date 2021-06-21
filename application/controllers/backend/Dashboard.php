<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Auth_Controller {

	function index(){
		$this->render('backend/dashboard');
	}
}
