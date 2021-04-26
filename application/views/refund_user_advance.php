<?php 
    echo form_open_multipart('Refund/refunduser');
    echo "Nota : ".form_input('nota',$nota).'<br>';
    if(isset($email)){
        echo "E-mail : ".form_input('email',$email).'<br>';
    }
    echo "Barang yang ingin direfund : ".form_input('barang',$namabarang).'<br>';
    echo form_hidden('idbarang',$idbarang);
    echo "Alasan Refund : ".'<br>';
    echo "<label>".form_radio('alasan','rusak')."<span>Barang Rusak</span></label>".'<br>';
    echo "<label>".form_radio('alasan','harapan').'<span>Barang tidak sesuai.</span></label>'.'<br>';
    echo "Foto Bukti : ".form_upload('foto','').'<br>';
    echo "Maksimal ukuran foto 1024x768 dan 4MB. Jenis foto hanya .jpg atau .png".'<br>';
    echo form_submit('gorefund','Kirim Permintaan Refund');
    echo form_close();
?>
<script>
<?php 
	if($this->session->flashdata('errorupload')){
		echo "M.toast({html:'".$this->session->flashdata('errorupload')."', classes:'red'});";
    }
    if($this->session->flashdata('erroralasan')){
		echo "M.toast({html:'".$this->session->flashdata('erroralasan')."', classes:'red'});";
	}
?>
</script>
