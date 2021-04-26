<?php
class Master extends BaseController {
	public function index() {
		$this->view('index');
	}

	public function barang(){
		$this->load->model('ModelBarang');
		$data = $this->ModelBarang->all();
		$this->view('master/barang', ['dataBarang' => $data]);
	}

}
