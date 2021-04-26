<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Refund extends BaseController {

	public function __construct()
	{
		parent::__construct(); 
		$this->load->model('ModelRefund');
		$this->load->model('ModelReceipt');
		$this->load->library('email');
	}
	
	public function index()
	{
		$this->butuhLogin();
		$this->refunduser();
	}

	public function refunduser(){
		$data = [];	
		if($this->input->post('submitnota')){
			$data['nota'] = $this->input->post('nota');
			$loggeduser = $_SESSION['loggedUser'];
			$q2 = $this->ModelReceipt->getemailfromreceipt($data['nota']);
			foreach($q2->result() as $row){
				$data['email'] = $row->email;
			}
			$q3 = $this->ModelReceipt->getbarang($data['nota']);
			foreach($q3->result() as $row){
				$data['namabarang'] = $row->nama;
				$data['idbarang'] = $row->id;
			}
			$this->view('refund_user_advance',$data);
		}
		else if($this->input->post('gorefund')){
			$nota = $this->input->post('nota');
			$email = $this->input->post('email');
			$idbarang = $this->input->post('idbarang');		
			$alasan = $this->input->post('alasan');
			if($alasan==null){
				$this->session->set_flashdata('erroralasan',"Kami tidak akan memproses permintaan anda tanpa alasan");
			}
			$config['upload_path'] = './upload_refund';
			$config['allowed_types'] = 'jpg|png';
			$config['max_size'] = '4096';
			$config['max_width']  = '1024';
			$config['max_height']  = '768';
			$this->load->library('upload',$config);
			if(!$this->upload->do_upload('foto')){
				if(!strpos($this->upload->display_errors(),"filetype"==true)){
					$this->session->set_flashdata('errorupload',"Hanya menerima foto berjenis .jpg atau .png");
				}
				else if(!strpos($this->upload->display_errors(),'larger'==true)){
					$this->session->set_flashdata('errorupload',"Ukuran foto terlalu besar");
				}
				$data['nota'] = $this->input->post('nota');
				$loggeduser = $_SESSION['loggedUser'];
				$q2 = $this->ModelReceipt->getemailfromreceipt($data['nota']);
				foreach($q2->result() as $row){
					$data['email'] = $row->email;
				}
				$q3 = $this->ModelReceipt->getbarang($data['nota']);
				foreach($q3->result() as $row){
					$data['namabarang'] = $row->nama;
					$data['idbarang'] = $row->id;
				}
				$this->view('refund_user_advance',$data);
			}
			else{
				$datafile=$this->upload->data();
				$foto = $datafile['file_name'];
				$this->ModelRefund->refundrequest($nota,$email,$idbarang,$alasan,$foto);
				$this->ModelReceipt->is_refunded($nota,$idbarang);
				$this->session->set_flashdata('pesan',"Permintaan anda akan kami proses");
				$loggeduser = $_SESSION['loggedUser'];
				$data['q'] = $this->ModelReceipt->getallpembelianuser($loggeduser);
				if($data['q']->num_rows() > 0){
					foreach($data['q']->result() as $row){
						$data['nota'] = $row->order_id;
						$data['q2'] = $this->ModelReceipt->getbarang($data['nota']);
					}
				}
				$this->view('refund_user',$data);
			}		
		}
		else{
			$loggeduser = $_SESSION['loggedUser'];
			$data['q'] = $this->ModelReceipt->getallpembelianuser($loggeduser);
			$this->view('refund_user',$data);
		}
	}
}
