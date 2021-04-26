<?php 
    echo "<table border=1>";
        echo "<tr>";
            echo "<th>ID Kategori</th>";
            echo "<th>Nama Kategori</th>";
        echo "</tr>";
        foreach($q->result() as $row){
            echo "<tr>";
                echo "<td>".$row->id."</td>";
                echo "<td>".$row->nama."</td>";
            echo "</tr>";
        }
    echo "</table>";
    echo "Masukkan Kategori Baru : <br>";
    echo form_open("admin/MKategori/insertKategori");
        echo materialize_form_input(["name"=>"nama","placeholder"=>"Nama Kategori"],'');
        echo materialize_form_submit(["name"=>"go","value"=>"go"],"Submit");
    echo form_close();
?>
<script>
<?php 
	if($this->session->flashdata('peringatan')){
		echo "M.toast({html:'".$this->session->flashdata('peringatan')."', classes:'green'});";
    }
	if($this->session->flashdata('peringatanx')){
		echo "M.toast({html:'".$this->session->flashdata('peringatanx')."', classes:'red'});";
    }
?>
</script>
<a href="<?php echo base_url('admin/Dashboard') ?>">← Kembali Ke Halaman Dashboard</a> 
