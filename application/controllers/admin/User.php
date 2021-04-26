<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends BaseAdminController {

	public function __construct()
	{
		parent::__construct(); 
		$this->load->helper('form');
		$this->load->model('ModelUser');
		$this->load->helper('url');
    }
    public function index(){
        $data['q'] = $this->ModelUser->getUser();
        $this->view('admin/masteruser',$data);
    }
    public function detail($id){
        $data['DataUser'] = $this->ModelUser->find($id);
        $this->view('admin/detail_user',$data);
    }
    public function delete($id){
        $this->ModelUser->deleteUser($id);
        $data['q'] = $this->ModelUser->getUser();
        $this->view('admin/masteruser',$data);
    }
}
