<script>
    $(document).ready(function() {
        $('.modal').modal();
    });
</script>
<h2><center>Tambah Bundle</center></h2>
<?php 
    echo form_open_multipart('admin/bundle/goAddHBundle');
        echo "Nama Bundle : <br>".form_input('nama','')."<br>";
        echo "Diskon Bundle  : <br>";
        echo "<div style='width:75px'><input type='number' name='disc' min=1 max=75></div>";
        echo "<br>".form_upload('fotobundle','')."<br>";    
        echo form_submit('submit','Tambah');
    echo form_close(); ?>
<br>
<a href="<?php echo base_url('admin/Dashboard') ?>">← Kembali Ke Halaman Dashboard</a> 
