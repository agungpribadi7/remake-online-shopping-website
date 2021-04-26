<div class='row'><?php
	$arrayData = [
		'label' => 'Tampilkan Berapa Jumlah Barang',
		'width-grid' => 's12 m3',
		'name' => 'showData',
		'id' => 'showData',
		'onchange' => 'ajaxShowData()'
	];
	$jumlah_show_data = [
		'4' => '4',
		'8' => '8',
		'12' => '12',
		'100000' => 'Semua'
	];
	echo materialize_form_dropdown($arrayData, $jumlah_show_data, $_SESSION['numOfRows']);
?></div>
<div class="row" id='tampungan'><?php			
	foreach ($selectWishList as $barang)
		echo kartu_barang($barang, $loggedUser);
	echo "<div style='clear:both;' class='right'>Halaman : ".$url_dibawah."</div><br>";
?></div>
<script>
	function ajaxShowData(){
		var myurl = "<?php echo site_url('Home/showWishList'); ?>"; 
		var jumlahShowData = $("#showData").val();
		$.post(myurl,
			{param1 : jumlahShowData},function(result){ 
			$("#tampungan").html(result);
		}); 
	}
</script>
