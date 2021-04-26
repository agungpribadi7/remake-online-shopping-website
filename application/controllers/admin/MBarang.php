<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MBarang extends BaseAdminController {

	public function __construct()
	{
		parent::__construct(); 
		$this->load->helper('form');
        $this->load->model('ModelBarang');
        $this->load->model('ModelKategori');
        $this->load->helper('url');
        $this->load->library('pagination');
        $this->load->model('ModelReceipt');
	}
	
    public function list($page = 0){
        $numOfRows = 10;
        $config["num_links"] 	= 3;
        $config["base_url"] 	= base_url("admin/barang/list"); 
        $config["total_rows"] 	= $this->ModelBarang->getJumlahBarang();
        $config["per_page"] 	= $numOfRows; 				
        $this->pagination->initialize($config); 
        $data["urlbottom"] 	= $this->pagination->create_links();	
        $data["q"] 			= $this->ModelBarang->getAllBarang($page,$numOfRows);
        foreach($data['q'] as $brg){
            $data['jual'][$brg->id] = $this->ModelReceipt->getPembelianBarang($brg->id);
        }
        $this->view("admin/master_barang",$data); 		
    }

    public function delete($id){
        $this->ModelBarang->deleteBarang($id);
        $this->list($page=0);
    }

    public function halTambah(){
        $qx = $this->ModelKategori->getAllKategori();
        foreach($qx->result() as $row){
            $data['q'][] = $row->nama;
        }
        $this->view('admin/tambah_barang',$data);
    }

    public function addBarang(){
        if($this->input->post('submitadd')){
            $kat = $this->input->post('kategori');
            $kategori = $this->ModelKategori->getKategoriId($kat);
            $nama = $this->input->post('nama');
            $harga = $this->input->post('harga');
            $stok = $this->input->post('stok');
            $desc = $this->input->post('desc');
            $this->ModelBarang->insBarang($kategori,$nama,$harga,$stok,$desc);
            $a = $this->ModelBarang->getidbarangbynama($nama);
            $path = "./img/barang/".$a;
            if(!is_dir($path)){
                mkdir($path, 0755, TRUE);
            }
            $config['upload_path'] = './img/barang/'.$a;
            $config['allowed_types'] = 'jpg|png';
            $config['max_size'] = '4096';
            $config['max_width']  = '1024';
            $config['max_height']  = '768';
            $this->load->library('upload',$config);
            if(!$this->upload->do_upload('foto')){
                if(strpos($this->upload->display_errors(),"filetype"==true)){
                    echo "Hanya menerima foto berjenis .jpg atau .png";
                }
                else if(strpos($this->upload->display_errors(),'larger'==true)){
                    echo "Ukuran file terlalu besar";
                }
                echo $this->upload->display_errors();
                $this->halTambah();
            }
            else{
                $datafile=$this->upload->data();
                $this->halTambah();
            }		
        }
    }
    
    public function halamanBarang($id){
        $q = $this->ModelBarang->getBarangById($id);
        $data['id'] = $id;
        $data['kategori'] = $this->ModelKategori->getKategoriById($q->kategori)->nama;
        $data['nama'] = $q->nama;
        $data['harga'] = $q->harga;
        $data['stok'] = $q->stok;
        $data['desc'] = $q->deskripsi;
        $this->view('admin/update_barang',$data);
    }
    public function updateBarang(){
        $id = $this->input->post('id');
        $nama = $this->input->post('nama');
        $harga = $this->input->post('harga');
        $stokx = $this->input->post('stokx');
        $stok = $this->input->post('stok');
        $astokx = $stokx + $stok;
        $desc = $this->input->post('desc');
        $this->ModelBarang->updateBarang($id,$nama,$harga,$astokx,$desc);
        $this->list($page=0);
    }
}
