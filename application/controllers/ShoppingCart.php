<?php
class ShoppingCart extends BaseController {

	/**
	 * Melakukan tambah Item kedalam Cart secara ajax
	 * Param POST :
	 * - kirimIdBarang
	 * - kirimJumlahBarang
	 * @return void
	 */
	public function tambahItem() {
		if(isset($_POST['kirimIdBarang']) && isset($_POST['kirimJumlahBarang'])){
			$idBarang = $_POST['kirimIdBarang'];
			$jumlahBarang = $_POST['kirimJumlahBarang'];	
			if (isset($_SESSION['barangBelanjaan'][$idBarang]))
				$_SESSION['barangBelanjaan'][$idBarang] += $jumlahBarang;
			else
				$_SESSION['barangBelanjaan'][$idBarang] = $jumlahBarang;
		}
	}
	
	/**
	 * Melakukan perubahan jumlah Item
	 * Param POST :
	 * - kirimIdBarang
	 * - KirimJumlahBarang
	 * @return void
	 */
	public function editJumlahItemAjax(){
		$idBarang = $_POST['kirimIdBarang'];
		$jumlahBarang = $_POST['kirimJumlahBarang'];
		$_SESSION['barangBelanjaan']["$idBarang"] = $jumlahBarang;
		$totalHarga = 0;
		foreach($_SESSION['barangBelanjaan'] as $idBarangSession => $jumlahBarangSession){
			$data['dataBarang'][$idBarangSession] = $this->ModelBarang->getBarangById($idBarangSession);
			$totalHarga += $data['dataBarang'][$idBarangSession]->harga * $jumlahBarangSession;
		}
		echo duit($totalHarga);
	}

	/**
	 * Menghapus barang di session 
	 * 
	 * @param int id ID barang
	 */
	public function deleteBarangBelanjaan($id = null){
		if($id != null){
			unset($_SESSION['barangBelanjaan'][$id]);
		}
		redirect('keranjang');
	}
	/**
	 * Melakukan penghapusan cart dan menampilkan cart secara ajax
	 * @return void
	 */
	public function showCart(){
		if($this->input->post('deletedId')){
			$idBarang = $_POST['deletedId'];
			unset($_SESSION['barangBelanjaan'][$idBarang]);
		}
		
		$dataBelanjaan = $this->GetBarang($this);
		$data['totalHarga'] = $dataBelanjaan['total'];
		$data['dataBarang'] = $dataBelanjaan['barang'];
		
		$this->view('cart_view',$data);
	}

	public function Pembayaran() {
		$this->butuhLogin();
		$idUser = $_SESSION['loggedUser'];
		$totalHarga = 0;
		$kodepromo = $this->input->post('kodepromo');
		$data['hargaDiskon'] = 0;
		$data['hargaAwal'] = 0;
		$data['diskon'] = 0;
		$diskon = 0;
		$lanjutPembayaran = true;
		if($this->session->userdata('barangBelanjaan')){
			if($kodepromo != ""){
				$cekVoucher = $this->ModelUser->cekVoucher($idUser,$kodepromo);
				if($cekVoucher != ""){
					$diskon = $cekVoucher->tipe_voucher;
					if($diskon == 0) $diskon = 5;
					else if($diskon == 1) $diskon = 10;
					else if($diskon == 2) $diskon = 25;
				}
				else{
					$lanjutPembayaran = false;
					$this->session->unset_userdata('kodepromo');
					$this->session->unset_userdata('besarDiskon');
					$this->session->set_flashdata('errorkode','Kode Promo yang Dimasukkan Salah');
				}
				$data['diskon'] = $diskon;
			}
			else{
				$this->session->unset_userdata('kodepromo');
				$this->session->unset_userdata('besarDiskon');
			}
			foreach($_SESSION['barangBelanjaan'] as $idBarangSession=>$jumlahBarangSession) {
				$totalHarga+= $this->ModelBarang->getBarangById($idBarangSession)->harga * $jumlahBarangSession;
			}
			$totalHarga = $totalHarga - ($diskon * $totalHarga / 100);
			$data['totalHarga'] = $totalHarga;
			if($diskon!=0) {
				$data['hargaDiskon'] = $totalHarga;
				$this->session->set_userdata('kodepromo',$kodepromo);
				$this->session->set_userdata('besarDiskon',$diskon);
			}
				
		}
		if($lanjutPembayaran){
			if($diskon == 0)
				$data['totalHarga'] = $totalHarga;
			$this->view("beli_view",$data);
		}
		else{
			$this->showCart();
		}
	}

	/**
	 * Mengembalikan array asosiatif dimana,
	 * 'barang' berisi data barang belanjaan beserta jumlahnya; dan
	 * 'total' untuk mendapatkan total harga dari belanjaan
	 * 
	 * Fungsi ini bersifat statik, agar dapat dipanggil oleh controller lain,
	 * maka dari itu, perlu dikirimkan $controller nya
	 * (biasanya diisi dengan $this)
	 * 
	 * @param BaseController $controller
	 * @return array 
	 */
	public static function GetBarang($controller) {
		if (!isset($_SESSION['barangBelanjaan']) || sizeof($_SESSION['barangBelanjaan']) < 1)
			return [
				'barang' => [],
				'total' => 0
			];
		
		$kumpulanID = array_keys($_SESSION['barangBelanjaan']);
		$barangArr = $controller->ModelBarang->findMany($kumpulanID);
		$total = 0;
		foreach ($barangArr as $barang) {
			$barang->jumlah = $_SESSION['barangBelanjaan'][$barang->id];
			$total += $barang->jumlah * $barang->harga;
		}
		$ret = [
			'barang' => $barangArr,
			'total' => $total
		];
		return $ret;
	}

	/**
	 * Mendapatkan jumlah barang didalam keranjang
	 * (dipanggil secara ajax)
	 *
	 * @return void
	 */
	public function GetJumlahBarang() {
		$total = 0;
		if (isset($_SESSION['barangBelanjaan']))
			foreach ($_SESSION['barangBelanjaan'] as $jumlah)
				$total += $jumlah;
		echo $total;
	}
}

