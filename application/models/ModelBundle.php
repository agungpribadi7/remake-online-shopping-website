<?php
class ModelBundle extends BaseModel {
    public function getBundleCount(){
        return $this->db->count_all('h_bundle');
    }
    public function getAllBundle(){
        return $this->db->get('h_bundle');
    }
    public function getBundleDetail($id){
        return $this->db->query("SELECT * FROM d_bundle where id=$id");
    }
    public function addHBundle($nam,$disc,$gam){
        $data = array(
            'nama' => $nam,
            'harga_bundle'=>0,
            'harga_asli'=>0,
            'diskon' => $disc,
            'gambar'=>$gam
        );
        $this->db->insert('h_bundle',$data);
    }
    public function deleteBundle($id){
        $this->db->query("UPDATE h_bundle set is_deleted=1 where id=$id");
    }
    public function getHBundleById($id){
        return $this->db->query("SELECT * from h_bundle where id=$id");
    }

    public function updateBundle($id,$brg){
        $this->db->query("INSERT INTO d_bundle values($id,$brg)");
        $harga = $this->db->query("SELECT harga from barang where id=$brg")->row()->harga;       
        $diskon = $this->db->query("SELECT diskon from h_bundle where id=$id")->row()->diskon;
        $hargadiskon = $harga - ($harga * ($diskon/100));
        $q = $this->db->query("UPDATE h_bundle SET harga_asli = harga_asli + $harga, harga_bundle = harga_bundle + $hargadiskon where id = $id");
    }

    public function belibundle($user,$id){
        $data = array(
            "id_user"=>$user,
            "id_bundle"=>$id
        );
        $this->db->insert('bundle_receipt',$data);
    }

    public function getGambarBundle($id){
        return $this->db->query("SELECT gambar from h_bundle where id=$id");
    }
}

