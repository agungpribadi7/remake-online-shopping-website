<?php
class ModelKategori extends BaseModel {
	protected $namaDB = 'kategori';

	/**
	 * Mengembalikan data kategori dari $id yang diberikan
	 *
	 * @param integer $id
	 * @return object|null
	 */
	public function getKategoriById(int $id) {
		return $this->find($id);
	}
	public function getAllKategori(){
		return $this->db->query("SELECT * FROM kategori");
	}
	public function getKategoriId($nama){
		return $this->db->query("SELECT * FROM kategori where nama='$nama'")->row('id');
	}
	public function insert($nama){
		$this->db->query("INSERT INTO kategori values(null,'$nama')");
	}
}

class Kategori {

}
