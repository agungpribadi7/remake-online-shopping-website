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
                <td> <a class="waves-effect waves-light btn modal-trigger" href="<?php echo base_url("admin/bundle/bundle_detail/".$bundle->id) ?>">Detail</a> 
                <?php echo "</td>";                
            echo "</tr>";
        }
    }
    else{
        echo "<center><h5>Tidak ada Bundle.</h5></center>";
    }
?>
</table>
<a href="<?php echo base_url('admin/Dashboard') ?>">← Kembali Ke Halaman Dashboard</a> 
