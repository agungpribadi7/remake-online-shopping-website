<?php
class ModelUser extends BaseModel {
	protected $namaDB = 'users';
	/**
	 * Mendapatkan data user dari $id yang diberikan
	 *
	 * @param integer $id
	 * @return Users|null
	 */
	public function getUserById(int $id) {
		return $this->find($id);
	}
	public function getUser(){
		return $this->db->get('users')->result('users');
	}
	public function getUC(){
		return $this->db->count_all('users');
	}
	public function deleteUser($id){
		$this->db->query("UPDATE users set is_banned=1 where id=$id");
	}
	/**
	 * Mendapatkan data user dari $id yang diberikan
	 *
	 * @param integer $id
	 * @return Users|null
	 */
	public function find($find) {
		$query = $this->db
			->query("SELECT * FROM users WHERE id = ?", $find);
		$user = $this->first($query, 'Users');
		$user->wishlist = $this->selectWishListByIdUser($find);
		return $user;
	}

	/**
	 * Mendapatkan data user dari $email yang diberikan
	 *
	 * @param string $email
	 * @return Users|null
	 */
	public function getUserByEmail(string $email) {
		$query = $this->db->query('SELECT * FROM users WHERE email = ?', $email);
		return $this->first($query);
	}
	public function isiwallet($email,$jumlah){
		$q = $this->db->query("UPDATE users SET wallet=(wallet+$jumlah) where email='$email'");
		return $q;
	}
	/**
	 * Untuk mendapatkan token agar user dapat mereset pass lewat email
	 *
	 * @param string $id ID user yang dimaksud
	 * @return string
	 */
	public function generateTokenLupaPass(int $id) : string {
		$token = md5(uniqid($id, true));
		$this->db->where('userID', $id)
			->where('dipake', 0)
			->update('lupa_pass', ['dipake' => 2]);
		$data = [
			'userID' => $id,
			'token' => $token
		];
		$this->db->insert('lupa_pass', $data);
		return $token;
	}

	/**
	 * Mendapatkan user dari token lupa password
	 *
	 * @param string $token
	 * @return Users|null
	 */
	public function getUserByTokenLupaPass(string $token) {
		$query = $this->db->select('users.*')
			->from('users')
			->join('lupa_pass', 'users.id = lupa_pass.userID')
			->where('token', $token)
			->where('dipake', 0)
			->where('waktu BETWEEN DATE_SUB(NOW() , INTERVAL 10 MINUTE) AND NOW()')
			->get();
		return $this->first($query);
	}

	/**
	 * Mendapatkan user dari token verifikasi email
	 *
	 * @param string $token
	 * @return Users|null
	 */
	public function getUserByTokenVerifikasi(string $token) {
		$query = $this->db->query('SELECT * FROM users WHERE validationToken = ?', $token);
		return $this->first($query);
	}

	/**
	 * Memperbarui password user di DB menggunakan token lupa password
	 *
	 * @param integer $id ID user
	 * @param string $token Token untuk mengubah password
	 * @param string $pass Password dalam bentuk plain-text (belum hash)
	 * @return void
	 */
	public function updateLupaPass(int $id, string $token, string $pass) {
		$cek = $this->db->query('SELECT COUNT(*) cek FROM lupa_pass WHERE userID = ? AND token = ?', [$id, $token]);
		if ($this->first($cek)->cek == 0)
			return;
		$hashPassword = password_hash($pass, PASSWORD_BCRYPT);
		$this->db->query('UPDATE users SET password = ? WHERE id = ?', [$pass, $id]);
		$this->db->query('UPDATE lupa_pass SET dipake = 1 WHERE userID = ? AND token = ?', [$id, $token]);
	}

	/**
	 * Memperbarui password user di DB menggunakan password nya yang sekarang di DB
	 * Mengembalikan true jika sukses, false jika gagal
	 *
	 * @param integer $id ID user
	 * @param string $old Password lama
	 * @param string $new Password baru
	 * @return bool
	 */
	public function updatePass(int $id, string $old, string $new) : bool {
		$user = $this->find($id);
		if ($user === null || !$user->cekPassword($old))
			return false;
		$hashBaru = password_hash($new, PASSWORD_BCRYPT);
		$this->db->query('UPDATE users SET password = ? WHERE id = ?', [$hashBaru, $id]);
		return true;
	}

	/**
	 * Verifikasi alamat email user
	 *
	 * @param integer $id
	 * @return void
	 */
	public function verifkasiUser(int $id) {
		$this->db->query('UPDATE users SET validationToken = "" WHERE id = ?', $id);
	}

	/**
	 * Membuat user baru menggunakan array $data yang diberikan
	 * ($data membutuhkan 'nama', 'email', dan 'password')
	 * kemudian mengembalikan token yang dibutuhkan user untuk melakukan validasi email
	 * mengembalikan kosong jika user dengan email yang sama ada di tabel
	 * 
	 * @param array $data
	 * @return string|null
	 */
	public function createUserBaru(array $data) {
		$apakahSudahAda = $this->getUserByEmail($data['email']);
		if ($apakahSudahAda !== null)
			return null;
		
		$token = md5(uniqid($data['nama'], true));
		$data['validationToken'] = $token;
		$data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
		$this->db->insert('users', $data);
		return $token;
	}

	/**
	 * Memperbarui biodata user dengan id $id ke array $data yang diberikan
	 *
	 * @param integer $id ID user
	 * @param array $data Data biodata
	 * @return void
	 */
	public function updateBiodata(int $id, array $data) {
		$this->db->update('users', $data, "id = $id");
	}
	
	//otw
	public function uploadFoto($id){
		if ($this->db->trans_status() === FALSE) return false;
		else return true;
	}

	/**
	 * Mendapatkan daftar wishlist berupa array id barang
	 * dari user
	 * 
	 * @param integer $iduser
	 * @return array
	 */
	public function selectWishListByIdUser(int $iduser) {
		$q = $this->db->query("select idbarang from wish_list where iduser = ?", $iduser);
		$ret = array_map(function($item) {
			return $item['idbarang'];
		}, $q->result_array());

		return $ret;
	}
	/**
	 * Mendapatkan daftar wishlist dari user
	 * dan menggunakan paging
	 * 
	 * @param integer $iduser
	 * @return array
	 */
	public function selectWishListPagingByIdUser(int $iduser,int $page, int $offset){
		$this->db->from('wish_list');
		$this->db->join('barang', 'barang.id = wish_list.idbarang');
		$this->db->where('wish_list.iduser',$iduser);
		$this->db->limit($offset,$page);
		return $this->db->get()->result('Barang');
	}
	public function getWallet($user){
		return $this->db->query("SELECT wallet from users where id=$user")->row()->wallet;
	}
	public function pakaiWallet($user,$harga){
		$this->db->query("UPDATE users SET wallet=wallet-$harga WHERE id=$user");
	}
	public function isiPoin($uname,$poin){
		$this->db->query("UPDATE users SET poin=poin+$poin where id=$uname");
	}
}

class Users {
	/**
	 * Mendapatkan URL untuk menampilkan foto profil user ini
	 *
	 * @return string
	 */	
	public function getUrlFoto() {
		$dirPath = "img/profil/$this->id";
		$ret = glob("$dirPath.*");
		if (count($ret) > 0)
			return $ret[0];
		return 'img/profil/no_foto.svg';
	}

	/**
	 * Apakah password yang diberikan cocok dengan hash di DB?
	 *
	 * @param string $string
	 * @return boolean
	 */
	public function cekPassword(string $string) {
		return password_verify($string, $this->password);
	}

	/**
	 * Apakah $idBarang yang diberikan ada didalam wishlist user ini?
	 *
	 * @param integer $idBarang
	 * @return boolean
	 */
	public function isWishlist(int $idBarang) {
		foreach ($this->wishlist as $barang) {
			if ($barang == $idBarang)
				return true;
		}
		return false;
	}

	/**
	 * Apakah email user ini terverifikasi?
	 *
	 * @return boolean
	 */
	public function isVerified() {
		return ($this->validationToken == '');
	}

	/**
	 * Apakah user ini mengaktifkan Autentikasi Faktor Kedua?
	 *
	 * @return boolean
	 */
	public function isTwoFactAuthEnabled() {
		return ($this->{'2authKey'} != '');
	}

	/**
	 * Apakah user ini diban?
	 *
	 * @return boolean
	 */
	public function isBanned() {
		return ($this->is_banned == 1);
	}
}
