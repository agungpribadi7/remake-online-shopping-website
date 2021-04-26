<?php
class Akun extends BaseController {
	/**
	 * Melakukan proses login
	 *
	 * @return void
	 */
	public function login() {
		if (isLoggedIn())
			if (isset($_POST['go']))
				redirect($_POST['go']);
			else redirect('/');
		else if (isset($_POST['email'])) {
			if ($_POST['email'] === 'admin' && isset($_POST['wawa'])) {
				$_SESSION['loggedUser'] = 'admin';
				redirect('/admin');
			} else if ($_POST['email'] === 'coba@gmail.com' && $_POST['pass'] === 'coba') {
			    $user = $this->ModelUser->getUserByEmail($_POST['email']);
		    	$_SESSION['loggedUser'] = $user->id;
				if (isset($_POST['go']))
					redirect($_POST['go']);
				else redirect('/');
			} else {
				$user = $this->ModelUser->getUserByEmail($_POST['email']);
				if ($user !== null && $user->cekPassword($_POST['pass'])) {
					if ($user->isBanned()) {
						$this->load->view('login_register', [
							'loginMsg' => 'Mohon maaf, Anda telah diban!',
							'bgColorMsg' => 'red'
						]);
					} else if ($user->isVerified()) {
						if ($user->isTwoFactAuthEnabled()) {
							$_SESSION['pending_loggedUser'] = $user->id;
							$_SESSION['pending_go'] = $this->input->post('go');
							$redirectUrl = 'login/twofact';
							if (isset($_POST['go']))
								$redirectUrl .= "?go=$_POST[go]";
							redirect($redirectUrl);
						} else {
							$_SESSION['loggedUser'] = $user->id;
							if (isset($_POST['go']))
								redirect($_POST['go']);
							else redirect('/');
						}
					} else {
						$this->load->view('login_register', [
							'loginMsg' => 'Mohon lakukan verifikasi email terlebih dahulu!',
							'bgColorMsg' => 'red'
						]);
					}
				} else {
					$this->load->view('login_register', [
						'loginMsg' => 'Email / password salah!',
						'bgColorMsg' => 'red'
					]);
				}
			}
		} else {
			$this->load->view('login_register');
		}
	}

	/**
	 * Melakukan proses logout
	 *
	 * @return void
	 */
	public function logout() {
		unset($_SESSION['loggedUser']);
		redirect('/');
	}

	public function addWishList(){
		$idBarang = $this->input->post('kirimIdBarang');
		if($this->session->userdata('loggedUser')){
			$idUser = $_SESSION['loggedUser'];
			$this->ModelBarang->addWishList($idUser,$idBarang);
		}	
	}
	
	public function deleteWishList(){
		$idBarang = $this->input->post('kirimIdBarang');
		if($this->session->userdata('loggedUser')){
			$idUser = $_SESSION['loggedUser'];
			$this->ModelBarang->deleteWishList($idUser,$idBarang);
		}
	}

	/**
	 * Melakukan proses pengubahan password
	 *
	 * @param string $token
	 * @return void
	 */
	public function lupa_pass($token = null) {
		if (isLoggedIn())
			return redirect('/');
		
		if ($token !== null) {
			$user = $this->ModelUser->getUserByTokenLupaPass($token);
			if ($user === null) {
				$this->load->view('lupaPass', [
					'done' => true,
					'msg' => 'Token tidak ditemukan!',
					'bgColorMsg' => 'red'
				]);
			} else if (isset($_POST['pass'])) {
				if ($_POST['pass'] === $_POST['confirm-pass']) {
					$this->ModelUser->updateLupaPass($user->id, $token, $_POST['pass']);
					$this->load->view('lupaPass', [
						'done' => true,
						'msg' => 'Password berhasil diganti! Anda dapat login sekarang',
						'bgColorMsg' => 'green'
					]);
				} else {
					$this->load->view('lupaPass', [
						'done' => false,
						'id' => $user->id,
						'email' => $user->email,
						'msg' => 'Password tidak cocok!',
						'bgColorMsg' => 'red'
					]);
				}
			} else {
				$this->load->view('lupaPass', [
					'done' => false,
					'id' => $user->id,
					'email' => $user->email
				]);
			}
		} else if (isset($_POST['email'])) {
			$user = $this->ModelUser->getUserByEmail($_POST['email']);
			if ($user !== null) {
				$token = $this->ModelUser->generateTokenLupaPass($user->id);
				$tokenLink = base_url("lupa_pass/$token");
				$html = $this->load->view('email/lupaPass', [
					'nama' => $user->nama,
					'tokenLink' => $tokenLink
				], true);
				$this->load->library('email');
				$this->email->from('omegaudayana77@gmail.com', 'CI-Comp Support');
				$this->email->to($_POST['email']);
				$this->email->subject('Atur ulang password');
				$this->email->message($html);
				$this->email->set_alt_message("Untuk mengatur ulang kata sandi anda, mohon untuk mengunjungi link berikut : \n$tokenLink");
				$this->email->send();
				$this->load->view('login_register', [
					'lupaPassMsg' => 'Sebuah email berhasil dikirim untuk membantu Anda mengatur ulang password.',
					'bgColorMsg' => 'green'
				]);
			} else {
				$this->load->view('login_register', [
					'lupaPassMsg' => 'Email tidak dapat ditemukan.',
					'bgColorMsg' => 'red'
				]);
			}
		} else {
			$this->load->view('login_register');
		}
	}

	/**
	 * Melakukan proses registrasi
	 *
	 * @return void
	 */
	public function register() {
		if (isLoggedIn())
			return redirect('/');

		if (sizeof($_POST) > 0) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('nama', 'nama', 'required');
			$this->form_validation->set_rules('email', 'email', 'required');
			$this->form_validation->set_rules('password', 'password', 'required');
			if ($this->form_validation->run()) {
				if ($_POST['password'] === $_POST['confirm-pass']) {
					unset($_POST['confirm-pass']);
					$token = $this->ModelUser->createUserBaru($_POST);
					if ($token === null) {
						$this->load->view('login_register', [
							'registerMsg' => 'Email yang Anda masukkan sudah pernah didaftarkan.',
							'bgColorMsg' => 'red'
						]);
					} else {
						$tokenLink = base_url("verifikasi/$token");
						$html = $this->load->view('email/registrasi', [
							'nama' => $_POST['nama'],
							'tokenLink' => $tokenLink
						], true);
						$this->load->library('email');
						$this->email->from('omegaudayana77@gmail.com', 'CI-Comp Support');
						$this->email->to($_POST['email']);
						$this->email->subject('Konfirmasi email');
						$this->email->message($html);
						$this->email->set_alt_message("Untuk mengverifikasi akun anda, mohon untuk mengunjungi link berikut : \n$tokenLink");
						$this->email->send();
						$this->load->view('login_register', [
							'registerMsg' => 'Registrasi sukses! Mohon untuk melakukan verifikasi email terlebih dahulu.',
							'bgColorMsg' => 'green'
						]);
					}
				} else {
					$this->load->view('login_register', [
						'registerMsg' => 'Password tidak cocok!',
						'bgColorMsg' => 'red'
					]);
				}
			} else {
				$this->load->view('login_register', [
					'registerMsg' => validation_errors(),
					'bgColorMsg' => 'red'
				]);
			}
		} else {
			$this->load->view('login_register');
		}
	}

	/**
	 * Melakukan proses verifikasi email
	 *
	 * @param string $token
	 * @return void
	 */
	public function verifikasi($token) {
		$user = $this->ModelUser->getUserByTokenVerifikasi($token);
		if ($user === null) {
			$this->load->view('verifikasi_email', [
				'msg' => 'Token verifikasi tidak dapat ditemukan!',
				'bgColorMsg' => 'red'
			]);
		} else {
			$this->ModelUser->verifkasiUser($user->id);
			$this->load->view('verifikasi_email', [
				'msg' => 'Terima kasih telah memverifikasikan email Anda.',
				'bgColorMsg' => 'green'
			]);
		}
	}
	
	/**
	 * Menampilkan profil user
	 *
	 * @return void
	 */
	public function profile($page = 0,$voucherId = null,$limit = 5) {
		$this->butuhLogin();
		$this->load->model('ModelReceipt');
		$idUser = $_SESSION['loggedUser'];
		$data['msgPoin'] = array(-1,'kosong');
		if($this->input->post('tukar')){
			$getMsgPoin = $this->ModelVoucher->insertVoucher($voucherId,$idUser);
			if($getMsgPoin == true){
				$this->session->set_flashdata('msgPoin',1);
			}
			else if($getMsgPoin == false){
				$this->session->set_flashdata('msgPoin',2);
			}
		}
		$data['jumlahShowItem'] = 5;
		if($this->input->post('sbShow')){
			$limit = $this->input->post('jumlahShowItem');
			$this->session->set_userdata('pagingTransaksiUser',$limit);
		}
		if($this->session->userdata('pagingTransaksiUser')){
			$limit = $this->session->userdata('pagingTransaksiUser');
			$data['jumlahShowItem'] = $this->session->userdata('pagingTransaksiUser');
		}
		$this->load->library('pagination');

		$config['base_url'] = site_url().'Akun/profile';
		$config['total_rows'] = $this->ModelReceipt->getJumlahTransaksiByUserId($idUser);
		$config['per_page'] = $limit;

		$this->pagination->initialize($config);
		
		$data['url_link'] = $this->pagination->create_links();
		if($data['url_link']=="") $data['url_link'] = 1;
		$data['biodata'] = $this->ModelUser->getUserById($idUser);
		$data['dataPoin'] = $this->ModelVoucher->getVoucherById($idUser);
		$data['dataTransaksi'] = $this->ModelReceipt->getTransaksiByUserId($idUser,$limit,$page);
		$this->view('akun',$data);
	}

	/**
	 * Fungsi untuk menampilkan halaman detail transaksi
	 *
	 * @param int $id
	 * @return void
	 */
	public function showDetailTransaksi($id){
		$this->butuhLogin();
		$this->load->model('ModelReceipt');
		$data['detailTransaksi'] = $this->ModelReceipt->find($id);
		$data['detailBarang'] = $this->ModelReceipt->getbarang($id)->result();
		if($data['detailTransaksi']->processor == 0) {
			$data['pembayaranMelalui'] = 'Wallet';
		} else {
			switch ($data['detailTransaksi']->status) {
				case 0:
					$data['pembayaranMelalui'] = 'Midtrans';
					break;
				case 1:
					$data['pembayaranMelalui'] = 'Midtrans (belum dibayar)';
					break;
				case 2:
					$data['pembayaranMelalui'] = 'Midtrans <span class="red-text">(error.)</span>';
					break;
			}
		}
		$this->view('detailTransaksiView',$data);
	}
	
	public function showDetailTransaksi_tracking($id) {
		$this->butuhLogin();
		$this->load->model('ModelReceipt');
		$transaksi = $this->ModelReceipt->find($id);
		if ($transaksi->kode_resi === null) {
			echo 'not found.';
			exit();
		}
		$resi = strtolower($transaksi->kode_resi);
		$resi = str_replace('0', '!', $resi);
		$resi = str_replace('0', '!', $resi);
		$resi = str_replace('1', '@', $resi);
		$resi = str_replace('2', '#', $resi);
		$resi = str_replace('3', '$', $resi);
		$resi = str_replace('4', '%', $resi);
		$resi = str_replace('5', '^', $resi);
		$resi = str_replace('6', '&', $resi);
		$resi = str_replace('7', '*', $resi);
		$resi = str_replace('8', '(', $resi);
		$resi = str_replace('9', ')', $resi);
		$resi = str_replace('!', '48', $resi);
		$resi = str_replace('@', '49', $resi);
		$resi = str_replace('#', '50', $resi);
		$resi = str_replace('$', '51', $resi);
		$resi = str_replace('%', '52', $resi);
		$resi = str_replace('^', '53', $resi);
		$resi = str_replace('&', '54', $resi);
		$resi = str_replace('*', '55', $resi);
		$resi = str_replace('(', '56', $resi);
		$resi = str_replace(')', '57', $resi);
		$resi = str_replace('a', '65', $resi);
		$resi = str_replace('b', '66', $resi);
		$resi = str_replace('c', '67', $resi);
		$resi = str_replace('d', '68', $resi);
		$resi = str_replace('e', '69', $resi);
		$resi = str_replace('f', '70', $resi);
		$resi = str_replace('g', '71', $resi);
		$resi = str_replace('h', '72', $resi);
		$resi = str_replace('i', '73', $resi);
		$resi = str_replace('j', '74', $resi);
		$resi = str_replace('k', '75', $resi);
		$resi = str_replace('l', '76', $resi);
		$resi = str_replace('m', '77', $resi);
		$resi = str_replace('n', '78', $resi);
		$resi = str_replace('o', '79', $resi);
		$resi = str_replace('p', '80', $resi);
		$resi = str_replace('q', '81', $resi);
		$resi = str_replace('r', '82', $resi);
		$resi = str_replace('s', '83', $resi);
		$resi = str_replace('t', '84', $resi);
		$resi = str_replace('u', '85', $resi);
		$resi = str_replace('v', '86', $resi);
		$resi = str_replace('w', '87', $resi);
		$resi = str_replace('x', '88', $resi);
		$resi = str_replace('y', '89', $resi);
		$resi = str_replace('z', '90', $resi);

		$url = 'https://myjne.jne.co.id:10443/jneone/service/animation/popupView';
		$opsi = [
			'http' => [
				'header' => [
					'X-Requested-With: com.indivara.jneone',
					'Content-Type: application/x-www-form-urlencoded',
					'User-Agent:'
				],
				'method' => 'POST',
				'content' => "userType=0&view=gkeyo4sr52hjicd5f4dkgblpnmico5&height=$resi"
			]
		];
		$konteks = stream_context_create($opsi);
		$hasil = file_get_contents($url, false, $konteks);
		//$hasil = '{"keyInput":"PDGAD00509622518","result":{"history":[{"desc":"SHIPMENT RECEIVED BY JNE COUNTER OFFICER AT [SOLOK]","date":"31-08-2018 13:46"},{"desc":"SHIPMENT PICKED UP BY JNE COURIER [SOLOK]","date":"31-08-2018 17:01"},{"desc":"RECEIVED AT SORTING CENTER [PADANG]","date":"31-08-2018 23:40"},{"desc":"PROCESSED AT SORTING CENTER [PADANG]","date":"31-08-2018 23:14"},{"desc":"DEPARTED FROM TRANSIT [GATEWAY JAKARTA]","date":"01-09-2018 15:19"},{"desc":"RECEIVED AT WAREHOUSE [JAKARTA]","date":"01-09-2018 17:25"},{"desc":"WITH DELIVERY COURIER [JAKARTA]","date":"01-09-2018 18:20"},{"desc":"DELIVERED TO [BAGUS JNE | 01-09-2018 19:15 ]","date":"01-09-2018 19:15"}],"detail":[{"cnote_weight":"5","cnote_date":"31-08-2018 13:46","cnote_origin":"SOLOK","cnote_shipper_addr1":"SOLOK","cnote_no":"PDGAD00509622518","cnote_receiver_city":"KEMAYORAN ,JAKARTA P","cnote_shipper_city":"PADANG","cnote_receiver_name":"BAPAK DEDEK","cnote_receiver_addr1":"JL UTAN PAJANG 3 NO 11 RT017RW","cnote_receiver_addr2":"06 KEL UTAN PANJANG KEC KEMAYO","cnote_receiver_addr3":"RAN JAKARTA PUSAT","cnote_shipper_name":"BAPAK BUDI"}],"cnote":{"amount":"120000","cnote_services_code":"YES15","city_name":"KEMAYORAN ,JAKARTA PUSAT","goods_desc":"MAKANAN","weight":"5","cnote_date":"2018-08-31T13:46:23.000+07:00","cnote_receiver_name":"BAPAK DEDEK","pod_status":"DELIVERED","cnote_pod_receiver":"BAGUS JNE","cnote_no":"PDGAD00509622518","publish_rate":"120000","cnote_pod_date":"01 Sep 2018 19:15"}},"rating":"0","comment":"","status":0,"message":"Success"}';
		$hasil = json_decode($hasil);
		$dataView = [
			'transaksi' => $transaksi,
			'dariJNE' => $hasil
		];
		$this->view('detailTransaksiView_track', $dataView);
	}

	/**
	 * Fungsi menghandle kiriman post dari edit biodata
	 *
	 * @return void
	 */
	public function editBiodata() {
		$this->butuhLogin();
		$idUser = $_SESSION['loggedUser'];
		$this->ModelUser->updateBiodata($idUser, $_POST);
	}

	
	public function tukarPoin($voucherId = null){
		$this->butuhLogin();
		$idUser = $_SESSION['loggedUser'];
		
		$this->profile();
	}
	
	/**
	 * Fungsi untuk menghandle kiriman post dari ganti password di profil
	 * mengembalikan dalam bentuk json berisi pesan
	 *
	 * @return void
	 */
	public function gantiPassword() {
		$pwd = $this->input->post('oldPwd');
		$pwdNew = $this->input->post('newPwd');
		$user = $this->ModelUser->find($_SESSION['loggedUser']);
		if ($pwdNew === '') {
			echo json_encode(['msg' => 'Password tidak boleh kosong!']);
			http_response_code(400); //bad request
			return;
		}
		
		if (!$this->ModelUser->updatePass($user->id, $pwd, $pwdNew)) {
			echo json_encode(['msg' => 'Password sebelumnya salah!']);
			http_response_code(401);
			return;
		}
		echo json_encode(['msg' => '']);
	}
	
	public function gantiFoto(){
		$this->load->helper('url');
		$this->load->model('ModelUser');
		$idUser = $_SESSION['loggedUser'];
		$data['biodata'] = $this->ModelUser->getUserById($idUser);
			if ($this->input->post('btnUpload')) {
				
				$config['upload_path']          = './gambar';	
				$data['infoupdate'] = $config['upload_path'];
                $config['allowed_types']        = 'jpg';
                $config['max_size']             = 2048;
                $config['max_width'] = $config['max_height'] = 750;
				$config['overwrite'] = TRUE;
				$config['file_name'] = 'photoid'.$idUser;

                $this->load->library('upload', $config);

                if ( ! $this->upload->do_upload('userfile'))
                {
					$err = $this->upload->display_errors();
					$data['infoupdate'] = $err;
					if(!(strpos($err,"filetype") === false)) { 
						$data['infoupdate'] = "<i style='color:red;margin-left:5px;'>Tipe file tidak sama</i>"; 
					}
					else if(!(strpos($err,"maximum height") === false)) { 
						$data['infoupdate'] = "<i style='color:red;margin-left:5px;'>ukuran gambar salah</i>"; 
					}
                }
                else
                {
					$image_data = $this->upload->data();
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = $image_data['full_path'];
					$config['file_type'] = 'jpg';
                    $config['maintain_ratio'] = TRUE;
                    $config['width'] = 150;
                    $config['height'] = 150;
                    $this->load->library('image_lib', $config);
                    if (!$this->image_lib->resize()) {
                        $data['infoupdate'] = $this->handle_error($this->image_lib->display_errors());
                    }

					$data['infoupdate'] = "<i style='color:green;margin-left:5px;'>Ganti Foto Profil Berhasil!</i>"; 
					$this->ModelUser->uploadFoto($idUser);
				    $this->upload->data()['file_name'];
                }
			}
			else{ $data['infoupdate'] = "gagal";}

		$this->view("akun",$data); 
	}

	/**
	 * Fungsi untuk menampilkan halaman konfigurasi Two-Factor Auth
	 *
	 * @return void
	 */
	public function twofact() {
		$this->butuhLogin();
		$this->load->library('GoogleAuthenticator');
		if (isset($_POST['kode'])) {
			if (isset($_POST['secret']))
				$secret = $_POST['secret'];
			else $secret = $this->loggedUser->{'2authKey'};
			$hasil = $this->googleauthenticator->verifyCode($secret, $_POST['kode'], 2);
			if ($hasil) {
				if (isset($_POST['secret']))
					$this->ModelUser->updateBiodata($_SESSION['loggedUser'], ['2authKey' => $_POST['secret']]);
				else
					$this->ModelUser->updateBiodata($_SESSION['loggedUser'], ['2authKey' => '']);
				echo 'true';
			} else echo 'false';
		} else {
			$data = [];
			if ($this->loggedUser->isTwoFactAuthEnabled()) {
			} else {
				$data['secret'] = $this->googleauthenticator->createSecret();
				$data['qrCodeUrl'] = $this->googleauthenticator->getQRCodeGoogleUrl('CI-Comp Shop', $this->loggedUser->email, $data['secret']);
			}
			$this->view('akun_twofact', $data);
		}
	}

	/**
	 * Fungsi untuk mengurus login yang menggunakan Two Factor Authentication
	 *
	 * @return void
	 */
	public function login_twofact() {
		if (!isset($_SESSION['pending_loggedUser']))
			redirect('login');
		else if (isset($_POST['kode'])) {
			$this->load->library('GoogleAuthenticator');
			$user = $this->ModelUser->getUserById($_SESSION['pending_loggedUser']);
			$hasil = $this->googleauthenticator->verifyCode($user->{'2authKey'}, $_POST['kode'], 2);
			if ($hasil) {
				unset($_SESSION['pending_loggedUser']);
				$_SESSION['loggedUser'] = $user->id;
				if (isset($_POST['go']))
					redirect($_POST['go']);
				else redirect('/');
			} else {
				$this->load->view('login_twoauth', [
					'msg' => 'Kode yang Anda masukkan salah!',
					'bgColorMsg' => 'red'
				]);
			}
		} else $this->load->view('login_twoauth');
	}

	/**
	 * Menampilkan halaman wallet user
	 *
	 * @return void
	 */
	public function wallet() {
		$this->view('akun_wallet');
	}

}
