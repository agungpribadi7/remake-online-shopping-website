<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once __DIR__.'/Pembayaran.php';

class Bundle extends BaseController {
	public function __construct() {
		parent::__construct();
		$this->load->model('ModelBundle');
    }

    public function index() {
		$data['bundles'] = $this->ModelBundle->getAllBundle();
        $this->view("bundle_view",$data);
    }

    public function detail($id) {
        $data['isi'] = $this->ModelBundle->getBundleDetail($id);
        foreach($data['isi']->result() as $row){
            $data['barang'][] = $this->ModelBarang->getBarangById($row->barang_id);
        }
        $data['bundle'] = $this->ModelBundle->getHBundleById($id)->row();
        $this->view("bundle_detail",$data);
    }
	
	public function beli() {
		$this->butuhLogin();
        if($this->input->post('belibundle')){
            $this->session->set_userdata('idbundle',$this->input->post('id'));
            $this->session->set_userdata('totalHarga',$this->input->post('total'));
            $data['total'] = $_SESSION['totalHarga'];
            $this->view("beli_bundle",$data);
		} else
			redirect('bundle');
	}
	
    public function beli_confirm() {
		$data['total'] = $_SESSION['totalHarga'];
        if($_POST['nama'] == '' && $_POST['alamat'] == '') {
			$this->session->set_flashdata('errorpenerima', "Mohon masukkan nama dan tempat pengiriman");
			$this->view('beli_bundle', $data);
		} else if($_POST['nama'] == '') {
			$this->session->set_flashdata('errorpenerima', "Mohon masukkan nama anda");
			$this->view('beli_bundle', $data);
		} else if($_POST['alamat'] == '') {
			$this->session->set_flashdata('errorpenerima', "Mohon masukkan tempat barang akan dikirim");
			$this->view('beli_bundle', $data);
		}
		$this->session->set_userdata('namapenerima', $_POST['nama']);
		$this->session->set_userdata('alamatpenerima', $_POST['alamat']);
		if ($this->input->post('midtrans')) {
			$this->view('bayar_midtrans_bundle', $data);
		} else if ($this->input->post('gowallet')) {
			$user = $_SESSION['loggedUser'];
			$data['walletsaya'] = $this->ModelUser->getWallet($user);
			$this->view('bayar_wallet_bundle',$data);
		}
	}
	
    public function bayar(){
        if ($this->input->post('belipakaiwallet')) {
            $harga = $_SESSION['totalHarga'];
			$user = $_SESSION['loggedUser'];
			$nama = $_SESSION['namapenerima']; 
            $alamat=$_SESSION['alamatpenerima'];
            $this->load->model(['ModelReceipt', 'ModelBundle']);
			//isi db beli bundle
			$orderID = $this->ModelReceipt->insertOrderanBundle($user, $nama, $alamat, $_SESSION['idbundle'], 0);
			$dapatpoin = $harga * 0.01;
			$this->ModelUser->isiPoin($user,$dapatpoin);
			$this->session->unset_userdata('namapenerima');
            $this->session->unset_userdata('alamatpenerima');
            $this->session->unset_userdata('totalHarga');
			$this->session->set_flashdata('hasil_order_id', $orderID);
			redirect('pembayaran/sukses');
		} else redirect('bundle');
    }
}
?>
