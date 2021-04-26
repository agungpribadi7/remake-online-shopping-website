<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MGrafik extends BaseAdminController {

	public function __construct()
	{
		parent::__construct(); 
		$this->load->helper('form');
        $this->load->helper('url');
        $this->load->model('ModelReceipt');
    }
    public function index(){
        $view = "penjualan";
        if($this->input->post('change')){
            $view = $this->input->post('kategori');
        }
        $data['info'] = "Total pendapatan uang dari penjualan tiap bulannya";
        $data['detail']=$this->ModelReceipt->getReceiptPenjualanGrafik();
        $data['bawah'] = 'bulan';
        if($view == "penjualan"){
            $data['info'] = "Total pendapatan uang dari penjualan tiap bulannya";
            $data['detail']=$this->ModelReceipt->getReceiptPenjualanGrafik();
            $data['bawah'] = 'bulan';
        }
        else if($view == "barangterjual"){
            $data['info'] = "Jumlah barang terjual terhadap Barang ID(Bagian Bawah Grafik)";
            $data['detail']=$this->ModelReceipt->getReceiptPenjualanBarangGrafik();
            $data['bawah'] = 'Barang ID';
        }
        else if($view == "pembelianuser"){
            $data['info'] = "Total uang pembelian yang dilakukan oleh User ID";
            $data['detail'] = $this->ModelReceipt->getUserBeliGrafik();
            $data['bawah'] = 'User ID';
        }
        $this->view('grafikView',$data);
    }
}
