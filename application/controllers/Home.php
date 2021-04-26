<?php
class Home extends BaseController {

	public function index() {
		
		$this->view('home', [
			'useContainer' => false,
			'carousel' => $this->ModelBarang->getCarousel(),
			'carouselRandom' => $this->ModelBarang->getCarouselRandom(),
			'barangHighlight' => $this->ModelBarang->getRandomBarang(10),
			'barangTerbaru' => $this->ModelBarang->getBarangTerbaru(10)
		]);
	}

	/**
	 * Abaikan fungsi ini, atau anda dapat pake ini buat ngedebug sesuatu seh.......
	 *
	 * @return void
	 */
	public function debug() {
		$this->sendEmail('spam@smankusors.com', 'coba', 'uji coba');
	}
	
	/**
	 * Menampilkan view barang (detail, foto barang)
	 *
	 * @param int $idBarang
	 * @return void
	 */
	public function barang(int $idBarang) {
		$data['databarang'] = $this->ModelBarang->getBarangById($idBarang);
		if (isLoggedIn()) {
			$idUser = $_SESSION['loggedUser'];
			$data['apakahBarangFavorite'] = $this->loggedUser->isWishlist($idBarang);
		} else $data['apakahBarangFavorite'] = false;
		$this->load->library('markdown');
		$this->view('barangview',$data);
	}
	
	public function addCart(){
		$idBarang = $this->input->post('kirimIdBarang');
		$stok = $this->input->post('kirimStok');
		
	}
	
	/**
	 * Menampilkan view wish list berupa kartu barang
	 * dan menggunakan paging ajax
	 *
	 * @param int $pageNum
	 * @return void
	 */
	 public function showWishList(int $pageNum = 0,int $numOfRows = 0){	
		if(isset($_POST['param1'])){
			$numOfRows = $_POST['param1'];
			$_SESSION['numOfRows'] = $_POST['param1'];
		}
		else if(isset($_SESSION['numOfRows'])){
			$numOfRows = $_SESSION['numOfRows'];
		}
		else{
			$numOfRows 				= 4; 
			$_SESSION['numOfRows']	= 4;
		}
		$idUser = $_SESSION['loggedUser'];
				
		$this->load->library("pagination"); 
		$config["num_links"] 	= 2;
		$config["base_url"] 	= base_url("wishlist"); 
		$config["total_rows"] 	= count($this->ModelUser->selectWishListByIdUser($idUser)); 	
		$config["per_page"] 	= $numOfRows; 				
		$this->pagination->initialize($config); 
		$data["url_dibawah"] 	= $this->pagination->create_links();	
		$data['selectWishList'] = $this->ModelUser->selectWishListPagingByIdUser($idUser,$pageNum,$numOfRows);
		if(isset($_POST['param1'])){
			foreach($data['selectWishList'] as $barang){
				echo kartu_barang($barang);
			}
			echo "<div style='clear:both;' class='right'>".$data['url_dibawah']."</div>";
		}
		else{
			$this->view('wish_list_view',$data);
		}
	}

	/**
	 * Fungsi untuk menghandle pencarian barang
	 *
	 * @return void
	 */
	public function cariBarang($offset = 0) {
		$arrKategori = $this->ModelKategori->all();
		$data['dropdownKategori'] = [null => 'Semua Kategori'];
		foreach ($arrKategori as $kategori) 
			$data['dropdownKategori'][$kategori->id] = $kategori->nama;
		$data['dropdownOrderBy'] = ['Nama A-Z', 'Nama Z-A', 'Termurah', 'Termahal', 'Penjualan'];
		$data['dropdownJumlah'] = ['8', '16', '32', '64', '128'];
		
		$this->load->library('pagination');
		$limit = $this->input->get('t');
		if ($limit == null)
			$limit = 8;
		else $limit = $data['dropdownJumlah'][$limit];
		$this->ModelBarang->init_pagination($limit, $offset);
		$dataBarang = $this->ModelBarang->findBarang($_GET['q'], $this->input->get('k'), $this->input->get('o'));
		$paginationConfig = [
			'base_url' => base_url('cari'),
			'total_rows' => $dataBarang['total'],
			'per_page' => $limit,
			'reuse_query_string' => true,
		];
		$data['arrBarang'] = $dataBarang['data'];
		$data['pagination_links'] = $this->initPagination($paginationConfig);
        $this->view('cari_barang', $data);
	}
	
	public function bantuan(){
		$this->view("help");
	}

}
