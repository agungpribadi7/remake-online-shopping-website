<?php
class ModelReceipt extends BaseModel {
	protected $namaDB = 'order_receipt';

	public function getharga($barang){
		$q = $this->db->query("SELECT harga_barang FROM order_items where barang_id=$barang");
		return $q;
	}

	public function detailReceipt($id){
		return $this->db->query("SELECT * FROM order_receipt where id=$id");
	}

	public function getReceiptCount(){
		return $this->db->count_all('order_receipt');
	}

	public function getOrderItemsById($id)
	{
		return $this->db->query("SELECT * from order_items where order_id=$id");
	}		

	public function isiResi($id,$resi){
		$this->db->query("UPDATE order_receipt SET kode_resi='$resi' where id=$id");
	}

	/*
	/ buat grafik
	/ return total penjualan, bulan Tahun
	*/
	public function getReceiptPenjualanGrafik(){
		$this->db->select('total_order as "kiri",DATE_FORMAT(waktu,"%M, %Y") as "bawah"');
		$this->db->from('order_receipt');
		$this->db->group_by("YEAR(waktu), MONTH(waktu)");
		return $this->db->get();
	}

	public function getReceiptPenjualanBarangGrafik(){
		$q1 = $this->db->query("select b.id as 'bawah',count(*) as'kiri' from order_items o, 
		barang b where b.id = o.barang_id group by b.id union select b.id,0 from order_items o 
		right join barang b on b.id = o.barang_id where o.barang_id is null group by b.id");
		return $q1;
	}

	public function getUserBeliGrafik(){
		$q1 = $this->db->query("SELECT total_order as 'kiri',userID as 'bawah' FROM order_receipt group by userID");
		return $q1;

	}

	public function getAllReceipt($page,$numOfRows){
		$this->db->select('*')->from('order_receipt')->where('tipe',0);
		$this->db->limit($numOfRows,$page);
		return $this->db->get();
	}

	public function getPembelianBarang($id){
		return $this->db->query("SELECT sum(jumlah) as 'jum' from order_items where barang_id=$id")->row('jum');
	}

	public function getallpembelianuser($user){
		$email = $this->db->query("SELECT email from users where id=$user")->row()->email;
		$q = $this->db->query("SELECT * FROM order_items o, order_receipt r where r.userID=$user 
		AND r.id = o.order_id AND o.order_id NOT IN (SELECT order_id FROM refund where email='$email')");
		return $q;
	}

	public function getemailfromreceipt($nota){
		$sql = "SELECT u.email FROM users u,order_receipt o WHERE u.id=o.userID AND o.id = $nota";
		$q = $this->db->query($sql);
		return $q;
	}
	
	/**
	 * Untuk mendapatkan barang yang dibeli oleh pembeli dari $nota yang diberikan
	 *
	 * @param int $nota
	 * @return CI_DB_result
	 */
	public function getbarang($nota){
		$sql = "SELECT * FROM barang b, order_items o WHERE b.id=o.barang_id and
		o.order_id = $nota";
		return $this->db->query($sql);
	}

	public function is_refunded($id,$barang){
		$q = $this->db->query("UPDATE order_items SET is_refunded=1 WHERE order_id=$id AND barang_id=$barang");
	}

	public function insertReceipt($user, $nama, $alamat, $total, $cara, $tipe = 0){
		if ($cara < 0 || $cara > 1)
			throw new Exception("Cara diluar range [0: wallet, 1: midtrans]");
		$data = [
			'userID' => $user,
			'nama' => $nama,
			'alamat' => $alamat,
			'total_order' => $total,
			'processor' => $cara,
			'tipe' => $tipe
		];
		if ($cara == 1)
			$data['status'] = 1;
		$this->db->insert('order_receipt', $data);
		return $this->db->insert_id();
	}

	public function insertItemReceipt($id,$brg,$harga,$jumlah){
		$this->db->query("INSERT INTO order_items values($id,$brg,$harga,$jumlah,0)");
	}

	/**
	 * Memasukkan data orderan kedalam DB,
	 * mengembalikan id orderan yang masuk di DB
	 *
	 * @param int $user ID user pembeli
	 * @param string $nama Nama penerima
	 * @param string $alamat Alamat penerima
	 * @param array $data Data yang berasal dari ShoppingCart::GetBarang()
	 * @param int $cara 0 untuk wallet, 1 untuk midtrans
	 * @param int $tipe 0 untuk order barang, 1 untuk order bundle, 2 untuk topup wallet
	 * @return int
	 */
	public function insertOrderan($user, $nama, $alamat, $data, $cara, $tipe) {
		$orderID = $this->insertReceipt($user, $nama, $alamat, $data['total'], $cara, $tipe);
		$masukKeOrderItem = [];
		foreach ($data['barang'] as $barang) {
			$masukKeOrderItem[] = [
				'order_id' => $orderID,
				'barang_id' => $barang->id,
				'harga_barang' => $barang->harga,
				'jumlah' => $barang->jumlah
			];
			if ($cara == 0)
				$this->ModelBarang->kurangiStok($barang->id, $barang->jumlah);
		}
		if (count($masukKeOrderItem) > 0)
			$this->db->insert_batch('order_items', $masukKeOrderItem);
		return $orderID;
	}

	/**
	 * Memasukkan data orderan bundle kedalam DB,
	 * mengembalikan id orderan yang masuk di DB
	 *
	 * @param int $user ID user pembeli
	 * @param string $nama Nama penerima
	 * @param string $alamat Alamat penerima
	 * @param int $bundleID ID bundle
	 * @param int $cara 0 untuk wallet, 1 untuk midtrans
	 * @return void
	 */
	public function insertOrderanBundle($user, $nama, $alamat, $bundleID, $cara) {
		if ($cara < 0 || $cara > 1)
			throw new Exception("Cara diluar range [0: wallet, 1: midtrans]");
		$dataBundel = $this->ModelBundle->getHBundleById($bundleID)->row();
		$data = [
			'userID' => $user,
			'nama' => $nama,
			'alamat' => $alamat,
			'total_order' => $dataBundel->harga_bundle,
			'processor' => $cara,
			'tipe' => 1
		];
		if ($cara == 1)
			$data['status'] = 1;
		else if ($cara == 0) {
			$itemBundel = $this->ModelBundle->getBundleDetail($bundleID);
			foreach($itemBundel->result() as $row) {
				$this->ModelBarang->kurangiStok($row->barang_id, 1);
			}
			$this->ModelUser->pakaiWallet($user, $dataBundel->harga_bundle);
		}
		$this->db->insert('order_receipt', $data);
		$orderID = $this->db->insert_id();		
		$data = [
			'id_bundle' => $bundleID,
			'id_order' => $orderID
		];
		$this->db->insert('order_bundle', $data);
		return $orderID;
	}

	/**
	 * Memperbarui data orderan (dari midtrans)
	 *
	 * @param integer $id
	 * @param array $data
	 * @return object
	 */
	public function updateOrderan(int $id, $data) {
		$this->db->where('id', $id)->update('order_receipt', $data);
	}

	/**
	 * Mencari data resep (tanpa barang yang dibeli) dari $id yang diberikan
	 *
	 * @param integer $id
	 * @return object|null
	 */
	public function find($id) {
		return $this->db->from('order_receipt')->where('id', $id)->get()->row();
	}

	public function getJumlahTransaksiByUserId(int $idUser){
		$this->db->where('userID',$idUser);
		return $this->db->get('order_receipt')->num_rows();
	}
	public function getTransaksiByUserId(int $idUser,int $limit,$page){
		$this->db->where('userID',$idUser);
		$this->db->limit($limit,$page);
		$this->db->order_by('id', 'DESC');
		return $this->db->get('order_receipt');
	}
	public function selectDetailTransaksiById(int $id){
		$this->db->select('*, r.nama as "penerima"');
		$this->db->from('order_items o,order_receipt r,barang b');
		$this->db->where('o.order_id = r.id');
		$this->db->where('b.id = o.barang_id');
		$this->db->where('order_id',$id);
		return $this->db->get();
	}
}
