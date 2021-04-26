<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Highlight extends BaseAdminController {

	public function __construct()
	{
		parent::__construct(); 
		$this->load->helper('form');
        $this->load->model('ModelBarang');
        $this->load->model('ModelKategori');
        $this->load->helper('url');
        $this->load->library('pagination');
        $this->load->model('ModelReceipt');
        $this->load->model('ModelHighlight');
	}
	
    public function list($page = 0){
        $numOfRows = 5;
        $config["num_links"] 	= 3;
        $config["base_url"] 	= base_url("admin/highlight/list"); 
        $config["total_rows"] 	= $this->ModelHighlight->getJumlahHighlight();
        $config["per_page"] 	= $numOfRows; 				
        $this->pagination->initialize($config); 
        $data["urlbottom"] 	= $this->pagination->create_links();	
        $data["q"] 			= $this->ModelHighlight->getAllHighlight($numOfRows,$page);
        $this->view("admin/master_highlight",$data); 				
    }
    public function tambah(){
        $this->view('admin/tambah_highlight');
    }
    public function addHighlight(){
        if($this->input->post('submitadd')){
            $config['upload_path'] = './img/promo/';
            $config['allowed_types'] = 'jpg|png';
            $config['max_size'] = '4096';
            $config['max_width']  = '1024';
            $config['max_height']  = '1024';
            $this->load->library('upload',$config);
            if(!$this->upload->do_upload('userfile')){
                if(strpos($this->upload->display_errors(),"filetype"==true)){
                    echo "Hanya menerima foto berjenis .jpg atau .png";
                }
                else if(strpos($this->upload->display_errors(),'larger'==true)){
                    echo "Ukuran file terlalu besar";
                }
                $this->list();
                echo $this->upload->display_errors();
            }
            else{
                $link = $this->input->post('toLink');
                $foto = $this->upload->data()['file_name']; 
                $this->ModelHighlight->insertHighlight($link,$foto);
                $datafile=$this->upload->data();
                $this->list();
            }		
        }
    }

    public function delete($id){
        $this->ModelHighlight->deleteHighlight($id);
        $this->list($page=0);
    }
}
