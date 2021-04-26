<?php
	class ModelRefund extends CI_Model {
		public function __construct()
		{
			parent::__construct();
			$this->load->helper('form');
			$this->load->database();
        }
        
        public function refundrequest($p1,$p2,$p3,$p4,$p5){
            $data = array (
				'order_id' => $p1,
				'email'=>$p2,
				'barang_id'=>$p3,
				'alasan'=>$p4,
				'foto'=>$p5
			);
			$this->db->insert('refund',$data);
		}

		public function ceknota($nota){
			$q = $this->db->query("SELECT id FROM order_receipt WHERE id = '$nota'");
			return $q;
		}
		
		public function getrefunddata(){
			$q = $this->db->get('refund');
			return $q;
		 }      
		 public function getrefunddatabyid($id){
			 $q = $this->db->query("SELECT * FROM refund WHERE id_refund=$id");
			 return $q;
		 }
		 public function getnamabarangrefund($id){
			$q = $this->db->query("SELECT b.nama FROM REFUND r, barang b WHERE b.id=r.barang_id and id_refund=$id");
			return $q;
		 }
		 public function editrefundstatus($id,$status){
			 $this->db->query("UPDATE refund SET status=$status WHERE id_refund=$id");		
		 }
		 public function clearrefunddata($id){
			$this->db->query("DELETE FROM refund WHERE id_refund=$id");
		 }
		 public function getunreadrefund(){
			 return $this->db->query("SELECT count(*) as 'total' from refund where status=0")->row()->total;
		 }
	}


?>