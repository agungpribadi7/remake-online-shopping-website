<?php
abstract class BaseModel extends CI_Model {
	/**
	 * Nama tabel
	 *
	 * @var string
	 */
	protected $namaDB;
	/**
	 * Primary key dari tabel
	 *
	 * @var string
	 */
	protected $primaryKey = 'id';

	/**
	 * Untuk urusan pagination, mengatur limit dan offset
	 *
	 * @var int
	 */
	protected $limit, $offset;

	/**
	 * Apakah dalam mode pagination?
	 *
	 * @var bool
	 */
	protected $pagination;

	/**
	 * @var CI_DB
	 */
	public $db;

	public function __construct() {
		parent::__construct();
		$this->db = get_instance()->db;
	}

	/**
	 * Mengembalikan semua objek dari model ini
	 *
	 * @return array
	 */
	public function all() : array {
		return $this->db
			->query("SELECT * FROM $this->namaDB")
			->result($this->namaDB);
		
	}

	/**
	 * Mencari objek dari $find yang dimasukkan
	 *
	 * @param object $find
	 * @return object|null
	 */
	public function find($find) {
		$query = $this->db
			->query("SELECT * FROM $this->namaDB WHERE $this->primaryKey = ?", $find);
		return $this->first($query, $this->namaDB);
	}

	/**
	 * Mengembalikan banyak objek dari $find yang dimasukkan
	 *
	 * @param object[] $find
	 * @return object[]|null
	 */
	public function findMany($find) {
		return $this->db->from($this->namaDB)
			->where_in($this->primaryKey, $find)
			->get()->result($this->namaDB);
	}

	/**
	 * Mengembalikan objek pertama dari hasil yang ditemukan, jika tidak ada hasil maka kembali null
	 *
	 * @param object $query
	 * @return object|null
	 */
	protected function first($query, $type = null) {
		if ($type === null) {
			if ($this->namaDB !== '')
				$type = $this->namaDB;
			else $type = 'object';
		}
		return $query->row(0, $type);
	}

	/**
	 * Menginisialisasi pagination, dipanggil sebelum memanggil kueri
	 *
	 * @param int $limit
	 * @param int $offset
	 * @return void
	 */
	public function init_pagination(int $limit, int $offset) {
		$this->limit = $limit;
		$this->offset = $offset;
		$this->db->start_cache();
		$this->pagination = true;
	}

	/**
	 * Mengembalikan query builder dalam bentuk pagination,
	 * dalam bentuk array asosiatif berisi :
	 * 'data' => berisi data yang sudah terpotong
	 * 'total' => mendapatkan total data
	 *
	 * @param CI_DB $query Query Builder
	 * @return array
	 */
	protected function kembali_pagination($query) {
		$ret = [
			'data' => $query->limit($this->limit, $this->offset)
				->get()->result($this->namaDB),
			'total' => $query->count_all_results()
		];
		$query->stop_cache();
		$query->flush_cache();
		$this->pagination = false;
		return $ret;
	}

	/**
	 * Mengembalikan dalam bentuk array asosiatif(data, total) jika dalam mode pagination,
	 * mengembalikan dalam bentuk array berisi data jika bukan
	 * 
	 * Untuk selengkapnya tentang mode pagination, harap baca dokumentasi fungsi
	 * kembali_pagination
	 *
	 * @param CI_DB $query Query builder
	 * @return array|object[]
	 */
	protected function kembali($query) {
		if ($this->pagination)
			return $this->kembali_pagination($query);
		else return $query->get()->result($this->namaDB);
	}
}
