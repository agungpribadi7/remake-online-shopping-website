<h4>Update Barang</h4><br>
<?php
    echo form_open_multipart('admin/barang/updateBarang');
        echo form_hidden('id',$id);
        echo "Kategori : ".form_label($kategori)."<br>";
        echo "Nama Barang : <br>".form_input('nama',$nama);
        echo "<br><br>Harga Sekarang : ".$harga."<br> Update Harga : <br>";
        $data = array(
            'name' => 'harga',
            'id' => 'harga',
            'class' => 'form-control',
            'type' => 'number'      
        );
        echo "<div style='width:100px'>";
        echo form_input($data,$harga); 
        echo "</div>";
        echo form_hidden('stokx',$stok);
        echo "<br>Stok Sekarang : ".$stok."<br> Tambah Stok : <br>";
        $datax = array(
            'name' => 'stok',
            'id' => 'stok',
            'class' => 'form-control',
            'type' => 'number'     
          );          
          echo "<div style='width:100px'>";
          echo form_input($datax,0); 
          echo "</div>";
        echo "Deskripsi : <br>".form_textarea('desc',$desc,"style='height:300px'")."<br>";
        echo form_submit('update','Update Barang');
    echo form_close();
?>
