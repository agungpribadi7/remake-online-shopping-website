<script>
    $(document).ready(function() {
        $('.modal').modal();
    });
</script>
<h2><center>Bundle Administration</center></h2>
<center>
<a class="waves-effect waves-light btn modal-trigger" href="<?php echo base_url('admin/bundle/goHBundle') ?>">Buat Bundle Baru</a> 
</center>
<?php 
    if($q->num_rows() > 0){ ?>
    <table border=1>
        <tr>
            <th>ID</th>
            <th>Nama Bundle</th>
            <th>Detail</th>
        </tr>
    <?php
        foreach($q->result() as $bundle){
            echo "<tr>";
                echo "<td>".$bundle->id."</td>";
                echo "<td>".$bundle->nama."</td>"; ?>
                <td> <a class="waves-effect waves-light btn modal-trigger" href="<?php echo '#modal'.$bundle->id; ?>">Detail</a> 
                <?php echo "</td>";                
            echo "</tr>";
        }
    }
    else{
        echo "<center><h5>Tidak ada Bundle.</h5></center>";
    }
?>
</table>
<?php 
    foreach($q->result() as $bundle){  ?>
        <div id="<?php echo 'modal'.$bundle->id;  ?>" class="modal">
            <div class="modal-content">
                <center><h4>Detail Bundle : <?php echo $bundle->nama; ?></h4></center><br>
                <div style="display:flex; justify-content:center;">      
                <div id='b2'>
                    <img src=<?php echo base_url("img/bundle/".$bundle->gambar) ?> style="height:200px; width:250px">
                </div>            
                    <div id='b1' style="display:inline">
                        <?php
                            if($bundle->is_deleted==0){
                                echo "<span style='color:green'>Bundle ini masih tersedia</span><br>";
                            }
                            else{
                                echo "<span style='color:red'>Bundle ini telah kadaluarsa</span><br>";
                            }
                            echo "ID Bundle : ".$bundle->id."<br>";
                            echo "Nama Bundle : ".$bundle->nama."<br>";
                            echo "Harga Asli : ".duit($bundle->harga_asli)."<br>";
                            echo "Harga Bundle : ".duit($bundle->harga_bundle)."<br>"; 
                            echo "Isi Bundle : <br>";
                            if($qd[$bundle->id]->num_rows() <= 0){
                                echo "Bundle ini tidak memiliki barang<br>";
                            }
                            else{
                                foreach($qd[$bundle->id]->result() as $detail){
                                    echo "-".$qb[$detail->barang_id]->nama."<br>";
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <center><?php
                    if($bundle->is_deleted==0){ ?>
                        <a href="<?php echo base_url('admin/bundle/deleteBundle/'.$bundle->id); ?>" 
                        class="modal-close waves-effect waves-green btn-flat">Delete Bundle</a>
                        <?php 
                    } ?>
                    
                    <a href="<?php echo base_url('admin/bundle/addBundle/'.$bundle->id); ?>" 
                    class="modal-close waves-effect waves-green btn-flat">Update Bundle</a>
                </center>
            </div>
        </div> <?php
    } 
?>
<a href="<?php echo base_url('admin/Dashboard') ?>">← Kembali Ke Halaman Dashboard</a> 


