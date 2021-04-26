<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MBundle extends BaseAdminController {

	public function __construct()
	{
		parent::__construct(); 
		$this->load->helper('form');
        $this->load->model('ModelBarang');
        $this->load->helper('url');
        $this->load->library('pagination');
        $this->load->model('ModelBundle');
        $this->load->library('upload');
    }

    public function index(){
        $data['q'] = $this->ModelBundle->getAllBundle();
        foreach($data['q']->result() as $row){
            $data['qd'][$row->id] = $this->ModelBundle->getBundleDetail($row->id);
            foreach($data['qd'][$row->id]->result() as $det){
                $data['qb'][$det->barang_id] = $this->ModelBarang->getBarangById($det->barang_id);
            }
        }
        $this->view("admin/master_bundle",$data); 		
    }

    public function goHBundle(){
        $this->view('admin/tambah_hbundle');
    }
    
    public function addBundle($id){
        $kat = $this->ModelKategori->all();
        $data['id'] = $id;
        foreach($kat as $row){
            $data['kat'][$row->id] = $row->nama;
        }
        $this->view("admin/tambah_bundle",$data);
    }

    public function goAddBundle(){
        if($this->input->post('submit')){
            $id = $this->input->post('idbun');
            $idbrg = $this->input->post('brg');
            $this->ModelBundle->updateBundle($id,$idbrg);
            $this->addBundle($id);
        }
    }

    public function getBarangByKategori($id){
        $barang = $this->ModelBarang->getBarangByKategori($id);
        echo json_encode($barang);
    }

    public function goAddHBundle(){
        if($this->input->post('submit')){
            $nama = $this->input->post('nama');
            $disc = $this->input->post('disc');
            $config['upload_path'] = ("./img/bundle/");
            $config['allowed_types'] = "jpg|png|jpeg";
            $config['remove_spaces'] = true;
            $config['overwrite'] = true;
            $config['file_name'] = $nama;
            $this->upload->initialize($config);
            if($this->upload->do_upload('fotobundle')){
                $info = $this->upload->data();
                $gam = $info["file_name"];
                $this->ModelBundle->addHBundle($nama,$disc,$gam);
            }
            else{
                echo $config['upload_path'];
                echo $this->upload->display_errors();
            }
            $data['q'] = $this->ModelBundle->getAllBundle();
            foreach($data['q']->result() as $row){
                $data['qd'][$row->id] = $this->ModelBundle->getBundleDetail($row->id);
                foreach($data['qd'][$row->id]->result() as $det){
                    $data['qb'][$det->barang_id] = $this->ModelBarang->getBarangById($det->barang_id);
                }
            }
            $this->view("admin/master_bundle",$data); 	
        }	
    }
    public function bundle_detail($id){
        $data['q'] = $this->ModelBundle->getBundleDetail($id);
        $this->view('bundle_detail',$data);
    }
    public function deleteBundle($id){
        $this->ModelBundle->deleteBundle($id);
        $data['q'] = $this->ModelBundle->getAllBundle();
        foreach($data['q']->result() as $row){
            $data['qd'][$row->id] = $this->ModelBundle->getBundleDetail($row->id);
        }
        $this->view("admin/master_bundle",$data); 		
    }
}
