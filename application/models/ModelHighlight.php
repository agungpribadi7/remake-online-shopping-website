<?php
class ModelHighlight extends BaseModel {
	protected $namaDB = 'highlight';

	public function getJumlahHighlight(){
        return $this->db->get('highlight')->num_rows();
    }
    public function getAllHighlight($limit,$page){
        return $this->db->limit($limit,$page)->get('highlight')->result();
    }
    public function insertHighlight($link,$foto){
        $data = array("id"=>'',"link"=>$link,"foto"=>$foto);
        $this->db->insert('highlight',$data);
    }
    public function deleteHighlight($id){
        $this->db->where("id",$id);
        $this->db->delete("highlight");
    }
}
