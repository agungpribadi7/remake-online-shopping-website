<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends BaseAdminController {

	public function __construct()
	{
		parent::__construct(); 
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->model('ModelBarang');
		$this->load->model('ModelUser');
		$this->load->model('ModelRefund'); 
		$this->load->model('ModelReceipt');
		$this->load->model('ModelBundle');
		$this->load->model('ModelHighlight');
    }
    public function index(){
		$data['jumbarang'] = $this->ModelBarang->getJumlahBarang();
		$data['jumuser'] = $this->ModelUser->getUC();
		$data['unreadrefund'] = $this->ModelRefund->getunreadrefund();
		$data['totaltrans'] = $this->ModelReceipt->getReceiptCount();
		$data['totalbundle']= $this->ModelBundle->getBundleCount();
        $this->view('admin/dashboard',$data);
	}
}
