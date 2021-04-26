<script>
    $(document).ready(function() {
        $('.modal').modal();
    });
</script>
<h2><center>Item Administration</center></h2>
<center>
<a class="waves-effect waves-light btn modal-trigger" href="<?php echo base_url('admin/barang/halTambah') ?>">Insert Barang Baru</a> 
</center>
<a href="<?php echo base_url('admin/Dashboard') ?>">← Kembali Ke Halaman Dashboard</a> 
<table border=1>
    <tr>
        <th>Nama</th>
        <th>Stok</th>
        <th>Details</th>
    </tr>
<?php
    foreach($q as $barang){
        echo "<tr>";
            echo "<td>".substr($barang->nama,0,110)."</td>";           
            echo "<td>".$barang->stok."</td>"; ?>
            <td><a class="waves-effect waves-light btn modal-trigger" href="<?php echo '#modal'.$barang->id; ?>">Detail</a> 
                <?php echo "</td>";     
        echo "</tr>";
    }
?>
</table>
<?php 
    foreach($q as $barang){  ?>
        <div id="<?php echo 'modal'.$barang->id;  ?>" class="modal">
            <div class="modal-content">
                <center><h4>Detail Barang : <?php echo substr($barang->nama,0,25)."..."; ?></h4></center><br>
                <div style="display:flex; justify-content:center;">
                    <div id='b2'>
                        <img src=<?php echo base_url($barang->getURLGambar()[0]) ?> style="height:300px; width:300px">
                    </div>
                    <div id='b1' style="display:inline">
                        <?php
                            echo "Kategori : ".$barang->kategori."<br>";
                            echo "ID Barang : ".$barang->id."<br>";
                            echo "Nama Barang : ".substr($barang->nama,0,20)."....<br>";
                            echo "Harga : ".duit($barang->harga)."<br>";
                            echo "Stok : ".$barang->stok."<br>"; 
                            $tempdesc = explode("* ", $barang->deskripsi);
                            echo "Gambar : <br>";
                            
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <center> <?php 
                    if($barang->hapus==0){ ?>
                        <a href="<?php echo base_url('admin/barang/delete/'.$barang->id); ?>" 
                        class="modal-close waves-effect waves-green btn-flat">Delete Barang</a>
                        <a href="<?php echo base_url('admin/barang/halamanBarang/'.$barang->id); ?>" 
                        class="modal-close waves-effect waves-green btn-flat">Update Barang</a>
                    <?php
                    } ?>                   
                    
                </center>
            </div>
        </div> <?php
    } 
?>

<?php
    echo form_open('MBarang/index');
    echo $urlbottom;
    echo form_close();
?>
