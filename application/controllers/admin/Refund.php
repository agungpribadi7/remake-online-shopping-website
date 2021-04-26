<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Refund extends BaseAdminController {

	public function __construct()
	{
		parent::__construct(); 
		$this->load->helper('form');
		$this->load->model('ModelRefund');
		$this->load->model('ModelReceipt');
		$this->load->helper('url');
		$this->load->library('email');
	}
	
	public function index()
	{
		$this->refundadmin();
	}

	public function refundadmin(){
		if($this->input->post('lengkap')){
			$idrefund =  $this->input->post('id');
			$datarefund['q'] = $this->ModelRefund->getrefunddatabyid($idrefund);
			$datarefund['q2'] = $this->ModelRefund->getnamabarangrefund($idrefund);
			$this->view('admin/refund_advance',$datarefund);
		}
		else{
			$data['q'] = $this->ModelRefund->getrefunddata();			
			$this->view('admin/refund',$data);
		}
	}
	public function refunddetail(){
		if($this->input->post('kirim')){
			$idrefund = $this->input->post('id');
			$email = $this->input->post('email');
			$barang = $this->input->post('barang');
			$nmbarang = $this->input->post('nmbarang');
			$nota = $this->input->post('nota');
			$harga = $this->ModelReceipt->getharga($barang);
			foreach($harga->result() as $row){
				$hrg = $row->harga_barang;
			}
			if($this->input->post('tanggapan') == 'terima'){
				$status = 1;	
				$msg = "<html>Permintaan refund anda telah kami terima, berikut rinciannya <br>".
					"E-mail : $email<br>".
					"Nama Barang : $nmbarang<br>".
					"Nomor Nota : $nota<br>".
					"Kami Akan merefund uang sebesar Rp.$hrg ke wallet akun anda jika barang anda telah kami terima.<br>".
					"<br>Terima Kasih<br>CI-Comp Team</html>";
				$this->sendemail($email,'Tanggapan Permintaaan Refund', $msg);
				$this->ModelRefund->editrefundstatus($idrefund,$status);
				echo "Permintaan diterima";
				$data['q'] = $this->ModelRefund->getrefunddata();
				$this->view('admin/refund',$data);
			}
			else if($this->input->post('tanggapan') == 'tolak'){
				$status = 2;
				$msg = "<html>Permintaan refund anda telah kami tolak, berikut rinciannya <br>".
					"E-mail : $email<br>".
					"Nama Barang : $nmbarang<br>".
					"Nomor Nota : $nota<br>".
					"Dikarenakan foto dan alasan yang tidak sesuai.</html>";
				$this->sendemail($email, 'Tanggapan Permintaaan Refund', $msg);
				$this->ModelRefund->editrefundstatus($idrefund,$status);
				echo "Permintaan ditolak";
				$data['q'] = $this->ModelRefund->getrefunddata();			
				$this->view('admin/refund',$data);
			}
			else{
				$status = 3;
				$this->ModelUser->isiwallet($email,$hrg);
				$this->ModelRefund->clearrefunddata($idrefund);
				$this->ModelReceipt->is_refunded($nota,$barang);
				echo "Permintaan selesai";
				$data['q'] = $this->ModelRefund->getrefunddata();
				$this->view('admin/refund',$data);
			}
			
		}
		else{
			$this->view('admin/refund');
		}
	}
}
