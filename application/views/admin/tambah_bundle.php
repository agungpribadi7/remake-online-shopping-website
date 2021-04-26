<center><h4>Insert Barang Untuk Bundle</h4></center><br>
<?php
    echo form_open('admin/bundle/goAddBundle');
        echo form_hidden('idbun',$id);
        echo "<center>ID Bundle : ".$id."</center><br>";
        echo form_dropdown('kat',$kat,0,"id='katSelect'");
        echo form_dropdown('brg','',0,"id='brgSelect'");
        echo form_submit('submit','Tambah Barang Ke Bundle');
    echo form_close();
?>

<script>
    function loadBarangSelect(){
        $.get("<?php echo base_url('admin/bundle/getBarangByKategori/'); ?>" + $("#katSelect").val(),
            function (hasil){
                var arrBarang = JSON.parse(hasil);           
                $("#brgSelect").html(""); 
                $.each(arrBarang, function(key, value) {
                    $("#brgSelect").append("<option value=" + value.id + ">" + value.nama + "</option>");
                    $("#brgSelect").formSelect();  
                });         
            }
        );                     
    }
    $(document).ready(function() {
        $('.modal').modal();        
        loadBarangSelect();
        $("#katSelect").change(loadBarangSelect);
    });
</script>
