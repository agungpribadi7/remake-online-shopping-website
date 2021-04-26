<script>
    $(document).ready(function() {
        $('.modal').modal();
    });
</script>
<h2><center>Highlight Administration</center></h2>
<center>
<a class="waves-effect waves-light btn modal-trigger" href="<?php echo base_url('admin/highlight/tambah') ?>">Insert Highlight Baru</a> 
</center>
<a href="<?php echo base_url('admin/Dashboard') ?>">← Kembali Ke Halaman Dashboard</a> 
<table border=1>
    <tr>
        <th>ID</th>
        <th>Menuju Link</th>
        <th>Gambar</th>
        <th>Detail</th>
    </tr>
<?php
    foreach($q as $highlight){
        echo "<tr>";
            echo "<td>".$highlight->id."</td>";           
            echo "<td>".$highlight->link."</td>"; 
            echo "<td>"?><img class="materialboxed responsive-img" src="<?php echo base_url()."img/promo/".$highlight->foto;?>" width="300px" height="200px"><?php echo "</td>";?>
            <td><a class="waves-effect waves-light btn modal-trigger" href="<?php echo '#modal'.$highlight->id; ?>">Detail</a> 
                <?php echo "</td>";     
        echo "</tr>";
    }
?>
</table>
<?php 
    foreach($q as $highlight){  ?>
        <div id="<?php echo 'modal'.$highlight->id;  ?>" class="modal">
            <div class="modal-content">
                <center><h4>Detail Highlight : <?php echo $highlight->id; ?></h4></center><br>
                <div style="display:flex; justify-content:center;">
                    <div id='b2'>
                        <img src=<?php echo base_url()."img/promo/".$highlight->foto; ?> style="height:300px; width:300px">
                    </div>
                    <div id='b1' style="display:inline;padding-left:20px;">
                        <?php
                            echo "ID Highlight : ".$highlight->id."<br>";
                            echo "Menuju Link : ".$highlight->link."<br>";
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <center>
                    <a href="<?php echo base_url('admin/highlight/delete/'.$highlight->id); ?>" 
                    class="modal-close waves-effect waves-green btn-flat">Delete Highlight</a>  
                </center>
            </div>
        </div> <?php
    } 
    echo $urlbottom;
?>
<script>
$('.materialboxed').materialbox();
</script>
