<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once __DIR__.'/ShoppingCart.php';

class Pembayaran extends BaseController {
	public function __construct(){
		parent::__construct();
		$this->load->model("ModelReceipt");
	}

	public function step2() {
		$dataBelanja = ShoppingCart::GetBarang($this);
		if ($dataBelanja['barang'] == [])
			redirect('/');
		if ($_POST['nama']=='' && $_POST['alamat']==''){
			$this->session->set_flashdata('errorpenerima',"Mohon masukkan nama dan tempat pengiriman");
			$data['totalHarga'] = $this->input->post('total');
			redirect('pembayaran');
		} else if($_POST['nama']==''){
			$this->session->set_flashdata('errorpenerima',"Mohon masukkan nama anda");
			$data['totalHarga'] = $this->input->post('total');
			redirect('pembayaran');
		} else if($_POST['alamat']==''){
			$this->session->set_flashdata('errorpenerima',"Mohon masukkan tempat barang akan dikirim");
			$data['totalHarga'] = $this->input->post('total');
			redirect('pembayaran');
		}
		$this->session->set_userdata('namapenerima', $_POST['nama']);
		$this->session->set_userdata('alamatpenerima', $_POST['alamat']);
		$hargaSetelahDiskon = $this->session->userdata('total');
		$data['total'] = $hargaSetelahDiskon;
			
		if (isset($_POST['midtrans'])) {
			$this->view('bayar_midtrans', $data);
		} else if(isset($_POST['gowallet'])) {
			$data['walletsaya'] = $this->loggedUser->wallet;
			$this->view('bayar_wallet', $data);
		}
	}

	public function bayar(){
		if($this->input->post('belipakaiwallet')){
			$harga = $_POST['total'];
			$kodePromo = "";
			if($this->session->userdata('kodepromo')){
				$kodePromo = $this->session->userdata('kodepromo');
				$this->session->unset_userdata('besarDiskon');
			}

			$user = $_SESSION['loggedUser'];
			$nama = $_SESSION['namapenerima']; 
			$alamat=$_SESSION['alamatpenerima'];
			$idorder = $this->ModelReceipt->insertReceipt($user,$nama,$alamat,$harga,0);
			foreach($_SESSION['barangBelanjaan'] as $idBarangSession=>$jumlahBarangSession) {
				// $totalHarga+= $this->ModelBarang->getBarangById($idBarangSession)->harga * $jumlahBarangSession;
				$hargaSekarang = $this->ModelBarang->getBarangById($idBarangSession)->harga;
				$this->ModelReceipt->insertItemReceipt($idorder,$idBarangSession,$hargaSekarang,$jumlahBarangSession);
				$this->ModelBarang->kurangiStok($idBarangSession,$jumlahBarangSession);
			}
			$this->ModelUser->pakaiWallet($_SESSION['loggedUser'],$harga);
			$dapatpoin = $harga * 0.01;
			$this->ModelUser->isiPoin($user,$dapatpoin);
			$this->session->unset_userdata('barangBelanjaan');
			$this->session->unset_userdata('namapenerima');
			$this->session->unset_userdata('alamatpenerima');
			$this->session->set_flashdata('hasil_order_id', $idorder);
			$this->ModelVoucher->expiredVoucher($kodePromo,$user);
			$this->session->unset_userdata('kodepromo');
			$this->session->unset_userdata('total');
			redirect('pembayaran/sukses');
		}
	}

	/**
	 * Fungsi untuk menginisialisasi library midtrans
	 *
	 * @return void
	 */
	public function init_midtrans() {
		$params = [
			'server_key' => 'SB-Mid-server-XYC8LOLjHP8kgG8QsiRc3bsG',
			'production' => false
		];
		$this->load->library('midtrans');
		$this->midtrans->config($params);
	}

	/**
	 * Untuk midtrans, (UNTUK PEMBELIAN BARANG, BUKAN BUNDLE/WALLET)
	 * dipanggil saat user mengkonfirmasi pembelian barang via midtrans
	 * dipanggil secara ajax
	 *
	 * @return string
	 */
	public function prosesMidtrans() {
		$this->init_midtrans();
		$dataBelanja = ShoppingCart::GetBarang($this);
		$kodePromo = "";
		if($this->session->userdata('kodepromo')){
			$kodePromo = $this->session->userdata('kodepromo');
		}
		$user = $_SESSION['loggedUser'];
		$namaPenerima = $_SESSION['namapenerima']; 
		$alamat = $_SESSION['alamatpenerima'];
		$idOrder = $this->ModelReceipt->insertOrderan($user, $namaPenerima, $alamat, $dataBelanja, 1, 0);
		$detailTransaksi = [
			'order_id' => $idOrder,
			'gross_amount' => $dataBelanja['total'] * 1000
		];
		$detailItem = [];
		foreach ($dataBelanja['barang'] as $barang) {
			$detailItem[] = [
				'id' => $barang->id,
				'price' => $barang->harga * 1000,
				'quantity' => $barang->jumlah,
				'name' => substr($barang->nama, 0, 50)
			];
		}
		$namaPembeli = $this->namaDepanBelakang($this->loggedUser->nama);
		$namaPenerima = $this->namaDepanBelakang($namaPenerima);
		$detailPembeli = [
			'first_name' => $namaPembeli[0],
			'last_name' => $namaPembeli[1],
			'email' => $this->loggedUser->email,
			'shipping_address' => [
				'first_name' => $namaPenerima[0],
				'last_name' => $namaPenerima[1],
				'address' => $alamat
			]
		];
		$dataTransaksi = [
			'transaction_details' => $detailTransaksi,
			'item_details' => $detailItem,
			'customer_details' => $detailPembeli
		];
		$ret = [
			'token' => $this->midtrans->getSnapToken($dataTransaksi),
			'orderID' => $idOrder
		];
		echo json_encode($ret);
	}

	/**
	 * Dipanggil setelah user melakukan interaksi dengan snap nya midtrans
	 * (dipanggil secara AJAX)
	 *
	 * @return void
	 */
	public function midtransReport() {
		$orderID = intval($_POST['order_id']);
		$data = ['processor_receipt' => json_encode($_POST)];
		//jika transaksi bukan sukses dan bukan pending,
		if ($_POST['status_code'] == '200')
			$data['status'] = 0;
		else if ($_POST['status_code'] == '201')
			$data['status'] = 1;
		else $data['status'] = 2; //terjadi error
		
		$orderDataSebelum = $this->ModelReceipt->find($orderID);
		$this->ModelReceipt->updateOrderan($orderID, $data);
		$orderData = $this->ModelReceipt->find($orderID);
		$baruSuskses = false;
		if ($orderDataSebelum->status > 0 && $data['status'] == 0) { //jika pembayaran baru sukses (mencegah dobel topup wallet)
			$baruSuskses = true;
		}
		if ($_POST['status_code'] == '200' && $baruSuskses) {
			switch ($orderData->tipe) {
				case 0:
					$orderItems = $this->ModelReceipt->getOrderItemsById($orderID)->result();
					foreach ($orderItems as $item) {
						$this->ModelBarang->kurangiStok($item->barang_id, $item->jumlah);
					}
					$harga = intval($_POST['gross_amount']) / 1000;
					$dapatpoin = $harga * 0.01;
					$this->ModelUser->isiPoin($orderData->userID, $dapatpoin);
					break;
				case 2:
					$walletUser = $this->loggedUser->wallet;
					$walletUser += $orderData->total_order;
					$this->ModelUser->updateBiodata($orderData->userID, ['wallet' => $walletUser]);
					break;
			}
		}
		$this->session->set_flashdata('hasil_order_id', $orderID);
		$this->session->unset_userdata('barangBelanjaan');
		$this->session->unset_userdata('namapenerima');
		$this->session->unset_userdata('alamatpenerima');
	}

	/**
	 * Dipanggil oleh midtrans jika user sudah/tidak melakukan transaksi jika bukan instant pay saat halaman pembayaran
	 *
	 * @return void
	 */
	public function midtransNotification() {
		$json_result = file_get_contents('php://input');
		$result = json_decode($json_result, true);
		$_POST = $result;
		$this->midtransReport();
	}

	/**
	 * Mendapatkan nama depan dan nama belakang (jika ada) dari string
	 *
	 * @param string $nama
	 * @return array
	 */
	private function namaDepanBelakang($nama) {
		$posSpasi = strpos($nama, ' ');
		if ($posSpasi > 0)
			return [
				substr($nama, 0, $posSpasi),
				substr($nama, $posSpasi + 1, strlen($nama))
			];
		else return [$nama, ''];
	}

	/**
	 * Menampilkan halaman pembayaran sukses
	 *
	 * @return void
	 */
	public function sukses() {
		if ($this->session->flashdata('hasil_order_id'))
			$this->view('bayar_sukses');
		else redirect('/');
	}

	/**
	 * Menampilkan halaman pembayaran sukses
	 *
	 * @return void
	 */
	public function pending() {
		if ($this->session->flashdata('hasil_order_id'))
			$this->view('bayar_pending');
		else redirect('/');
	}

	/**
	 * Menampilkan halaman pembayaran sukses
	 *
	 * @return void
	 */
	public function error() {
		$this->view('bayar_error');
	}

	/**
	 * Mengurus top up wallet menggunakan midtrans
	 * 
	 * @return void
	 */
	public function prosesMidtransWallet() {
		$this->init_midtrans();
		$user = $_SESSION['loggedUser'];
		$namaPenerima = $this->loggedUser->nama;
		$alamat = 'wallet';
		$dataBelanja = [
			'barang' => [],
			'total' => $_POST['topup']
		];
		$idOrder = $this->ModelReceipt->insertOrderan($user, $namaPenerima, $alamat, $dataBelanja, 1, 2);
		$detailTransaksi = [
			'order_id' => $idOrder,
			'gross_amount' => $_POST['topup'] * 1000
		];
		$detailItem = [[
			'price' => $_POST['topup'] * 1000,
			'quantity' => 1,
			'name' => 'Top Up Wallet '.$this->config->item('site_name')
		]];
		$namaPembeli = $this->namaDepanBelakang($this->loggedUser->nama);
		$detailPembeli = [
			'first_name' => $namaPembeli[0],
			'last_name' => $namaPembeli[1],
			'email' => $this->loggedUser->email,
		];
		$dataTransaksi = [
			'transaction_details' => $detailTransaksi,
			'item_details' => $detailItem,
			'customer_details' => $detailPembeli
		];
		$ret = [
			'token' => $this->midtrans->getSnapToken($dataTransaksi),
			'orderID' => $idOrder
		];
		echo json_encode($ret);
	}

	/**
	 * Mengurus pembelian wallet menggunakan midtrans
	 * 
	 * @return void
	 */
	public function prosesMidtransBundle() {
		$this->init_midtrans();
		$this->load->model(['ModelBundle', 'ModelReceipt']);
		$user = $_SESSION['loggedUser'];
		$namaPenerima = $_SESSION['namapenerima']; 
		$alamat = $_SESSION['alamatpenerima'];
		$idOrder = $this->ModelReceipt->insertOrderanBundle($user, $namaPenerima, $alamat, $_SESSION['idbundle'], 1);
		$dataBundle = $this->ModelBundle->getHBundleById($_SESSION['idbundle'])->row();
		$detailTransaksi = [
			'order_id' => $idOrder,
			'gross_amount' => $dataBundle->harga_bundle * 1000
		];
		$detailItem = [[
			'id' => $dataBundle->id,
			'price' => $dataBundle->harga_bundle * 1000,
			'quantity' => 1,
			'name' => "$dataBundle->nama Bundle"
		]];
		$namaPembeli = $this->namaDepanBelakang($this->loggedUser->nama);
		$namaPenerima = $this->namaDepanBelakang($namaPenerima);
		$detailPembeli = [
			'first_name' => $namaPembeli[0],
			'last_name' => $namaPembeli[1],
			'email' => $this->loggedUser->email,
			'shipping_address' => [
				'first_name' => $namaPenerima[0],
				'last_name' => $namaPenerima[1],
				'address' => $alamat
			]
		];
		$dataTransaksi = [
			'transaction_details' => $detailTransaksi,
			'item_details' => $detailItem,
			'customer_details' => $detailPembeli
		];
		$ret = [
			'token' => $this->midtrans->getSnapToken($dataTransaksi),
			'orderID' => $idOrder
		];
		echo json_encode($ret);
	}

	
}
