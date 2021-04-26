<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Beli extends BaseController{
    public function __construct()
	{
		parent::__construct(); 
		$this->load->helper('form');
		$this->load->model('ModelRefund');
		$this->load->model('ModelReceipt');
		$this->load->helper('url');
		$this->load->library('email');
	}
}
?>