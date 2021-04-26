<center><h3>Bundle : <?php echo $bundle->nama; ?></h3><br>
<div>
    <img src="<?php echo base_url("img/bundle/".$bundle->gambar); ?>" style="width:500px;height:200px;">
</div>
<h5>Barang Di Dalam Bundle : </h5>
<div class="row">
    <div class="col s12">
        <div class="scroll-horizontal">
    <?php 
    foreach($isi->result() as $index=>$isibundle){ 
        echo kartu_barang($barang[$index], $loggedUser);
    }
?>
        </div>
    </div>
</div>
<hr>
Harga Normal : <?= duit($bundle->harga_asli); ?><br>
Harga Bundle : <?= duit($bundle->harga_bundle); ?><br>
Anda Menghemat : <span style="color:green"><?= duit($bundle->harga_asli-$bundle->harga_bundle); ?></span><br>
<?php 
    $cek = 0;
    echo form_open("Bundle/beli");
        echo form_hidden('id',$bundle->id).form_hidden("total",$bundle->harga_bundle);
        foreach($isi->result() as $index=>$isibundle){ 
            if($barang[$index]->stok <= 0) $cek++;
        }
        if($cek <= 0){
            echo materialize_form_submit(['name'=>'belibundle','value'=>'submit'],"Beli Bundle");
        }
        else{
            echo materialize_form_submit(['name'=>'belibundle','value'=>'submit','disabled'=>'disabled'],"Bundle Habis");
        }
    echo form_close();
?>
<br>
</center>