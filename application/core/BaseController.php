<?php
/**
 * BaseController digunakan untuk mengimplementasikan inheritance pada view
 * @property ModelBundle $ModelBundle
 */
abstract class BaseController extends CI_Controller {
	/**
	 * @var ModelUser
	 */
	public $ModelUser;
	/**
	 * @var ModelBarang
	 */
	public $ModelBarang;
	/**
	 * @var ModelKategori
	 */
	public $ModelKategori;
	/**
	 * @var ModelReceipt
	 */
	public $ModelReceipt;

	/**
	 * User yang sedang login, null jika belum login atau admin
	 * @var Users
	 */
	public $loggedUser;

	public function __construct() {
		parent::__construct();
		if (isLoggedIn() && !isAdmin())
			$this->loggedUser = $this->ModelUser->find($_SESSION['loggedUser']);
	}

	/**
	 * Mengeload view menggunakan base view
	 *
	 * @param string $view
	 * @return void
	 */
	public function view(string $view, array $data = []) : void {
		$data['menuKategori'] = $this->ModelKategori->all();
		$data['loggedUser'] = $this->loggedUser;
		$data['isi'] = $this->load->view($view, $data, true);
		$this->load->view('base', $data);
	}

	/**
	 * Fungsi untuk melakukan redirect jika user belum melakukan login
	 *
	 * @return void
	 */
	public function butuhLogin() {
		if (isLoggedIn())
			return;

		redirect('login?go='.uri_string());
		exit();
	}

	/**
	 * Fungsi untuk menendang wong yang bukan admin.
	 *
	 * @return void
	 */
	public function butuhAdmin() {
		if (isLoggedIn() && isAdmin())
			return;
		echo 'forbidden.';
		http_response_code(403);
		exit();
	}

	/**
	 * Mengirimkan pesan email
	 * Mengembalikan boolean apakah email sukses dikirim atau gagal
	 *
	 * @param string $tujuan Tujuan alamat email
	 * @param string $subjek Subjek email
	 * @param string $pesan Isi pesan email
	 * @return bool
	 */
	public function sendEmail($tujuan, $subjek, $pesan) {
		$html = $this->load->view('email/base', [
			'isi' => $pesan
		], true);
		$this->load->library('email');
		return $this->email
            ->from($this->config->item('smtp_user'), $this->config->item('site_name').' Support')
            ->to($tujuan)
            ->bcc($this->config->item('smtp_user'))
            ->subject($subjek)
            ->message($html)
            ->send();
	}

	/**
	 * Menginisialisasi pagination dengan tema materialize
	 * Mengembalikan link pagination dalam bentuk HTML
	 *
	 * @param array $config
	 * @return string
	 */
	public function initPagination($config) {
		$this->load->library('pagination');
		$paginationConfig = [
			'full_tag_open' => '<ul class="pagination">',
			'full_tag_close' => '</ul>',
			'first_tag_open' => '<li class="waves-effect">',
			'first_tag_close' => '</li>',
			'last_tag_open' => '<li class="waves-effect">',
			'last_tag_close' => '</li>',
			'next_link' => '<i class="material-icons">chevron_right</i>',
			'next_tag_open' => '<li class="waves-effect">',
			'next_tag_close' => '</li>',
			'prev_link' => '<i class="material-icons">chevron_left</i>',
			'prev_tag_open' => '<li class="waves-effect">',
			'prev_tag_close' => '</li>',
			'cur_tag_open' => '<li class="active"><a>',
			'cur_tag_close' => '</a></li>',
			'num_tag_open' => '<li class="waves-effect">',
			'num_tag_close' => '</li>'
		];
		$paginationConfig = array_merge($paginationConfig, $config);
		$this->pagination->initialize($paginationConfig);
		return $this->pagination->create_links();
	}
}

/**
 * BaseAdminController digunakan untuk mengimplementasikan keharusan login admin
 */
abstract class BaseAdminController extends BaseController {
	public function __construct() {
		parent::__construct();
		$this->butuhAdmin();
	}
}
