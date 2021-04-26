<?php
class ModelVoucher extends BaseModel {
	protected $namaDB = 'voucher';

    public function getVoucherById(int $idUser){
		$this->db->where('tanggal_redeem is null');
		return $this->db->where('user_id',$idUser)->get('voucher');
	}
	public function cekVoucher(int $idUser,string $kodeVoucher){
		$this->db->where('tanggal_redeem is null');
		$this->db->where('user_id',$idUser)->where('key_voucher',$kodeVoucher);
		return $this->db->get('voucher')->row();
	}
	public function expiredVoucher(string $kodeVoucher,int $idUser){
        echo 'aasdfsadfasdfasdfasdfasdfasdf '.$kodeVoucher.' - '.$idUser;
		$this->db->where('user_id',$idUser);
		$this->db->where('key_voucher',$kodeVoucher);
		$this->db->set('tanggal_redeem',date('Y-m-d'));
		$this->db->update('voucher');
	}
	public function insertVoucher(int $tipeVoucher,int $idUser){
		$queryUser = $this->db->where('id',$idUser)->get('users')->row();
		$hargaPoin = 0;
		if($tipeVoucher == 0) $hargaPoin = 100;
		else if($tipeVoucher == 1) $hargaPoin = 175;
		else if($tipeVoucher == 2) $hargaPoin = 300;
		$poinBaru = $queryUser->poin - $hargaPoin;
		if($poinBaru < 0){
			return false;
		}
		else{
			$keyVoucher = "";
			$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
			$max = count($characters) - 1;
			for ($i = 0; $i < 9; $i++) {
				$rand = mt_rand(0, $max);
				$keyVoucher .= $characters[$rand];
			}
			$data = array(
				"id_voucher"=>"",
				"tipe_voucher"=>$tipeVoucher,
				"key_voucher"=>$keyVoucher,
				"user_id"=>$idUser,
				"tanggal_beli"=>date('Y-m-d'));
			$this->db->insert("voucher",$data);
			$queryUser = $this->db->where('id',$idUser)->get('users')->row();
			
			$this->db->where('id',$idUser);
			$this->db->set('poin',$poinBaru);
			$this->db->update("users");
			return true;
		}
    }
}