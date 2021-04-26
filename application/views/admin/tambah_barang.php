<h4>Insert Barang Baru</h4><br>
<?php
    $namaKat = [];
    for($i=0;$i<count($q);$i++){
        $namaKat[$q[$i]] = $q[$i];
    }

    echo form_open_multipart('admin/barang/addBarang');
        echo "Kategori : <br>".form_dropdown('kategori',$namaKat)."<br>";
        echo "Nama Barang : <br>".form_input('nama','');
        echo "<br>Harga : <br>";
        $data = array(
            'name' => 'harga',
            'id' => 'harga',
            'class' => 'form-control',
            'type' => 'number'      
        );          
        echo form_input($data);
        echo "Stok Awal : <br>";
        $datax = array(
            'name' => 'stok',
            'id' => 'stok',
            'class' => 'form-control',
            'type' => 'number'     
          );          
        echo form_input($datax)."<br>";
        echo "Deskripsi : <br>".form_textarea('desc','',"style='height:400px'")."<br>";
        echo "Foto : ".form_upload('foto','').'<br>';
        echo form_submit('submitadd','Insert Barang Baru');
    echo form_close();
?>
