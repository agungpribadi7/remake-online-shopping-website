<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MKategori extends BaseAdminController {

	public function __construct()
	{
		parent::__construct(); 
		$this->load->helper('form');
		$this->load->model('ModelKategori');
		$this->load->helper('url');
	}
	public function index(){
		$data['q'] = $this->ModelKategori->getAllKategori();
		$this->view('admin/master_kategori',$data);
	}

	public function insertKategori(){
		if($this->input->post('go')){
			$nama = $this->input->post('nama');
			if($nama==''){
				$this->session->set_flashdata('peringatanx','Masukkan Nama kategorinya');
				$this->index();
			}
			else{
				$this->ModelKategori->insert($nama);
				$this->session->set_flashdata('peringatan','Kategori berhasil ditambahkan');
				$this->index();
			}
		}
		else{
			$this->index();
		}
	}
}
