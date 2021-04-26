<?php
class ModelBarang extends BaseModel {
	protected $namaDB = 'barang';

	/**
	 * Mengembalikan data barang dari $id yang diberikan
	 *
	 * @param int $id
	 * @return Barang|null
	 */
	public function getBarangById(int $id) {
		return $this->find($id);
	}
	public function addStok($id,$num){
		$this->db->query("UPDATE barang SET stok=stok+$num where id=$id");
	}
	public function insBarang($kat,$nam,$har,$stok,$desc){
		$data = array(
			'kategori' => $kat,
			'nama' => $nam,
			'harga' => $har,
			'stok' => $stok,
			'deskripsi' => $desc
		);
		$this->db->insert('barang',$data);
	}
	public function updateBarang($id,$nam,$har,$stok,$desc){
		$this->db->query("UPDATE barang set nama='$nam', harga=$har, stok=$stok, deskripsi='$desc' where id=$id");
	}
	/**
	 * deprecated.
	 *
	 * @param string $parameter
	 * @return void
	 */
	public function getidbarangbynama($parameter){
		$q = $this->db->query("SELECT id FROM BARANG WHERE nama='$parameter'");
		return $q->row('id');
	}

	/**
	 * Mendapatkan sebagian barang dari DB,
	 * menggunakan $offset dan $jumlah
	 * NOTE: Tidak termasuk barang yang dihapus!
	 *
	 * @param int $offset
	 * @param int $jumlah
	 * @return void
	 */
	public function getAllBarang(int $offset, int $jumlah){
		$q = $this->db->from('barang')
			->order_by('kategori','asc')
			->limit($jumlah, $offset);
		return $q->get()->result('barang');
	}
	/**
	 * Mencari barang, digunakan untuk user dalam mencari barang
	 *
	 * @param string $nama
	 * @param integer $kategori
	 * @param integer $urutan
	 * @return Barang[]
	 */
	public function findBarang(string $nama, $kategori, $urutan = 0) {
		$q = $this->db->from('barang')
			->like('nama', $nama);
		if ($kategori != null)
			$q->where('kategori', $kategori);
		if ($urutan == null) $urutan = 0;
		switch ($urutan) {
			case 0: //Nama A-Z
				$q->order_by('nama ASC');
				break;
			case 1: //Nama Z-A
				$q->order_by('nama DESC');
				break;
			case 2: //Termurah
				$q->order_by('harga ASC');
				break;
			case 3: //Termahal
				$q->order_by('harga DESC');
				break;
			case 4:
				$q->order_by('(SELECT SUM(jumlah) FROM order_items WHERE barang_id = barang.id) DESC');
				break;
		}
		return $this->kembali($q);
	}
	/**
	 * Mencari data barang menggunakan $nama yang diberikan
	 *
	 * @param string $nama
	 * @param bool $termasukDeskripsi apakah deskripinya ikut dikembalikan?
	 * @param bool $termasukHapus apakah barang yang terhapus ikut ditampilkan?
	 * @return Barang[]
	 */
	public function findBarangByNama(string $nama, bool $termasukDeskripsi = false, bool $termasukHapus = false) : array {
		$query = $this->db->from('barang')
			->where('nama LIKE', "%$nama%");
		if (!$termasukDeskripsi)
			$query = $query->select('kategori, id, nama, harga, stok, hapus');
		if (!$termasukHapus)
			$query = $query->where('hapus', 0);
		return $query->get()->result('Barang');
	}

	public function kurangiStok($id,$jumlah){
		$this->db->query("UPDATE barang SET stok=stok-$jumlah where id=$id");
	}

	/**
	 * Mendapatkan data barang yang termasuk dalam $id kategori yang diberikan
	 *
	 * @param integer $id Kategori dalam bentuk Id
	 * @param bool $termasukDeskripsi apakah deskripinya ikut dikembalikan?
	 * @param bool $termasukHapus apakah barang yang terhapus ikut ditampilkan?
	 * @return Barang[]
	 */
	public function getBarangByKategori(int $id, bool $termasukDeskripsi = false, bool $termasukHapus = false) : array {
		$query = $this->db->from('barang')
			->where('kategori', $id);
		if (!$termasukDeskripsi)
			$query = $query->select('kategori, id, nama, harga, stok, hapus');
		if (!$termasukHapus)
			$query = $query->where('hapus', 0);
		return $query->get()->result('Barang');
	}

	/**
	 * Mendapatkan jumlah barang yang ada di DB
	 *
	 * @param bool $termasukHapus hitung juga barang yang dihapus
	 * @return void
	 */
	public function getJumlahBarang($termasukHapus = false){
		$q = $this->db;
		if (!$termasukHapus)
			$q = $q->where('hapus', 0);
		return $q->count_all('barang');
	}
	
	/**
	 * Menghapus barang dengan $id yang diberikan
	 * NOTE: Barang tidak dihapus di DB, hanya set flag.
	 *
	 * @param integer $id
	 * @return void
	 */
	public function deleteBarang(int $id){
		$this->db->query("UPDATE barang SET hapus = 1 where id=$id");
	}

	/**
	 * Mengembalikan data barang dengan id acak sebanyak $n
	 *
	 * @param integer $n jumlah data
	 * @return Barang[]
	 */
	public function getRandomBarang(int $n) {
		$query = $this->db->query("SELECT * FROM barang WHERE hapus = 0 ORDER BY rand() LIMIT $n");
		return $query->result('Barang');
	}

	/**
	 * Mengembalikan data barang terbaru sebanyak $n
	 *
	 * @param integer $n jumlah data
	 * @return Barang[]
	 */
	public function getBarangTerbaru(int $n) {
		$query = $this->db->query("SELECT * FROM barang WHERE hapus = 0 ORDER BY id DESC LIMIT $n");
		return $query->result('Barang');
	}
	
	/**
	 * Tambah row wish_list
	 */
	public function addWishList(int $idUser,int $idBarang){
		$this->db->query("insert into wish_list values($idUser,$idBarang)");
	}
	
	/**
	 * Delete row wish_list
	 */
	public function deleteWishList(int $idUser,int $idBarang){
		$this->db->query("delete from wish_list where idbarang = '$idBarang' and iduser = '$idUser'");
	}

	public function getCarousel(){
		return $this->db->get('highlight')->result();
	}
	public function getCarouselRandom(){
		$query = $this->db->query("SELECT * FROM highlight where id = 1 or id = 2");
		return $query->result();
	}
	
}

class Barang {
	/**
	 * Mengembalikan array berisi kumpulan gambar untuk barang ini
	 *
	 * @return array
	 */
	function getURLGambar() {
		$dirPath = "img/barang/$this->id/";
		$ret = [];
		if (file_exists($dirPath)) {
			$ret = glob("$dirPath*.*");
		}
		if (count($ret) < 1)
			$ret = ['img/no_image.svg'];
		return $ret;
	}

	/**
	 * Apakah barang ini terhapus?
	 *
	 * @return boolean
	 */
	function isDeleted() {
		return ($this->hapus == 1);
	}
}
