<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Trans extends BaseAdminController {

	public function __construct()
	{
		parent::__construct(); 
		$this->load->helper('form');
        $this->load->model('ModelBarang');
        $this->load->model("ModelBundle");
        $this->load->helper('url');
        $this->load->library('pagination');
        $this->load->model('ModelReceipt');
    }
    public function index($page=0){
        $numOfRows = 10;
        $config["num_links"] 	= 3;
        $config["base_url"] 	= base_url("admin/Trans/index"); 
        $config["total_rows"] 	= $this->ModelReceipt->getReceiptCount();
        $config["per_page"] 	= $numOfRows; 				
        $this->pagination->initialize($config); 
        $data["urlbottom"] 	= $this->pagination->create_links();	
        $data['q'] 			= $this->ModelReceipt->getAllReceipt($page,$numOfRows)->result();    
        $this->view("admin/master_trans",$data); 		
    }
    public function detail($id){
        $data['qq'] = $this->ModelReceipt->detailReceipt($id);
        if($data['qq']->row()->tipe==0){
            $data['q'] = $this->ModelReceipt->getOrderItemsById($id);
            foreach($data['q']->result() as $row){
                $data['qb'][$row->barang_id] = $this->ModelBarang->getBarangById($row->barang_id);
            }
        }
        else if($data['qq']->row()->tipe == 1){
            $data['qbun'] = $this->ModelBundle->getGambarBundle()->row()->gambar;
        }
        $this->view('admin/Receipt_Detail',$data);
    }
    public function isiResi(){
        $resi = $_POST['resi'];
        $id = $_POST['id'];
        $this->ModelReceipt->isiResi($id,$resi);
        $this->index();
    }
}
