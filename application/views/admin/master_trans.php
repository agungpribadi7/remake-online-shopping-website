<script>
    $(document).ready(function() {
        $('.modal').modal();
    });
</script>
<h2><center>Transaction Administration</center></h2>
<table border=1>
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Total</th>
        <th>Tanggal</th>
        <th>Kode Resi</th>
        <th>Action</th>
    </tr>
<?php
    foreach($q as $rec){
        echo "<tr>";
            echo "<td>".$rec->id."</td>";
            echo "<td>".$rec->nama."</td>";
            echo "<td>".duit($rec->total_order)."</td>";
            echo "<td>".substr($rec->waktu,0,10)."</td>"; 
            if($rec->kode_resi == null){
                echo "<td>Tidak Ada</td>";
            }
            else echo "<td>".$rec->kode_resi."</td>";
            ?>
            <td><a class="waves-effect waves-light btn modal-trigger" 
            href="<?php echo base_url("admin/Trans/detail/".$rec->id) ?>">Detail</a> 
            <?php 
            if($rec->kode_resi == null){
                echo materialize_form_button(['name'=>'inputresi','id'=>'inputResi','onclick'=>'ajaxresi('.$rec->id.')'],"Input Resi");
            }     
            echo "</td>";
        echo "</tr>";
    }
    echo form_open('Trans/index');
        echo $urlbottom;
    echo form_close();
?>
</table>
<a href="<?php echo base_url('admin/Dashboard') ?>">← Kembali Ke Halaman Dashboard</a> 
<script>
    function ajaxresi(id){
        var resi = prompt("Masukkan Nomor Resi","");
        $.ajax({
            type:"POST",
            url:"<?php echo base_url('admin/Trans/isiResi'); ?>",
            data: {"id":id,"resi":resi},
            success: function(){
                location.reload();
            }
        });
    }
</script>