<h2 class="header">Keranjang Belanja</h2>
<div class='row' id='listCart'>
<?php
if($this->session->userdata('barangBelanjaan')){
?>
	<div class="col s12 m4 right">
		<div class="card">
			<div class="card-stacked">
				<div class="card-content">
					<span class="card-title">Ringkasan Pembelian</span>
					<br>
					<p>Total Harga</p>
					<div class="right top" id="labelTotalHarga">
					<?php
						echo duit($totalHarga); 
					?>
					</div>
				</div>
				<div class="card-action">
					<?php 
						$kodePromoSession = "";
						if($this->session->userdata('kodepromo') != "") 
							$kodePromoSession = $this->session->userdata('kodepromo');
						echo form_open('pembayaran');
						echo materialize_form_input(array("placeholder"=>"Kode Promo","name"=>"kodepromo"),$kodePromoSession); 
						echo materialize_form_submit(array("name"=>"menujuPembayaran"),"Menuju Pembayaran");
						echo form_close();
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
	foreach($dataBarang as $barang) {		
		$urlGambarArr = $barang->getURLGambar();
	?>
		<div class="col s12 m8 product" data-id="<?php echo $barang->id ?>">
			<div class="card horizontal">
				<div class="card-image">
					<img src="<?php echo base_url($urlGambarArr[0]); ?>" >
				</div>
				<div class="card-stacked">
					<?php 
					echo form_open("ShoppingCart/deleteBarangBelanjaan/$barang->id");
					echo materialize_form_submit(['name'=>'deletedId','class' => 'right col s3 m2'], material_ikon('cancel')); 
					echo form_close();
					?>
					<div class="card-content">	
						<div>
							<p><?php echo $barang->nama; ?></p><br />
						</div>
						<div class="flex"><?php
							echo '<span class="harga">'.duit($barang->harga).'</span>'.
							'<div class="input-field numeric">'.
								'<label class="active" id="stok">Jumlah</label>'.
								materialize_form_button(['onclick' => "kurangJumlah(event, $barang->id)", 'class' => 'up micro'], material_ikon('remove')).
								"<input id='jumlahBarang$barang->id' type='number' min='1' value='$barang->jumlah' max='$barang->stok' class='validate' onchange='cekChange($barang->id)' readonly' />".
								materialize_form_button(['onclick' => "tambahJumlah(event, $barang->id)", 'class' => 'down micro'],material_ikon('add')).
							'</div>';
						?></div>
					</div>
				</div>
			</div>
		</div>
<?php
	}
}
else{
	echo "Keranjang Belanja Anda Saat ini Kosong";
}
?>

</div>
<script>
<?php
	if($this->session->flashdata('errorkode')){
		echo "M.toast({html:'".$this->session->flashdata('errorkode')."', classes:'red'});";
	}
?>
function hapusBarang(id){
	var myurl = "<?php echo site_url('ShoppingCart/showCart'); ?>"; 
	$.post(myurl, { deletedId: id }, function(result){
		$('#listCart').html(result);
	}); 
}

function kurangJumlah(event, id){
	event.stopPropagation();
	var jumlahBarang = parseInt($("#jumlahBarang"+id).val())-1;
	var myurl = "<?php echo site_url('ShoppingCart/editJumlahItemAjax'); ?>"; 
	if(jumlahBarang-1!=-1){
		$.post(myurl, { kirimIdBarang : id, kirimJumlahBarang : jumlahBarang }, function(result){
			$("#labelTotalHarga").html(result);
			refreshCartBadge();
		}); 
		$("#jumlahBarang"+id).val(jumlahBarang);
	}
}

function tambahJumlah(event, id){
	event.stopPropagation();
	var maxJumlah = $('#jumlahBarang'+id).attr('max');
	var jumlahBarang = parseInt($("#jumlahBarang"+id).val())+1;
	var myurl = "<?php echo site_url('ShoppingCart/editJumlahItemAjax'); ?>"; 
	if(jumlahBarang<=maxJumlah){
		$.post(myurl, { kirimIdBarang : id, kirimJumlahBarang : jumlahBarang }, function(result){
			$("#labelTotalHarga").html(result);
			refreshCartBadge();
		}); 
		$("#jumlahBarang"+id).val(jumlahBarang);
	}
}
function cekChange(id){
	var jumlahBarang = $("#jumlahBarang"+id).val();
	var myurl = "<?php echo site_url('ShoppingCart/editJumlahItemAjax'); ?>"; 
	$.post(myurl, { kirimIdBarang : id, kirimJumlahBarang : jumlahBarang }, function(result){
		$("#labelTotalHarga").html(result);
	});
}

function cekJumlah(id){
	var limit = 0;
	var maxJumlah = $('#jumlahBarang'+id).attr('max');
	var jumlahBarang = $("#jumlahBarang"+id).val();
	if(parseInt(jumlahBarang)+1<=maxJumlah){
		$("#jumlahLabel").html("Jumlah");
	}
	else if(jumlahBarang!=""){
		limit = 1;
		$("#jumlahLabel").html("<small style='color:red;'>Jumlah hanya tersedia :"+maxJumlah+"!</small>");
	}
	if(jumlahBarang==""){
		$("#jumlahBarang"+id).val(0);
		jumlahBarang = 0;
	}
	if(limit==0){
		var myurl = "<?php echo site_url('ShoppingCart/editJumlahItemAjax'); ?>"; 
		$.post(myurl, { kirimIdBarang : id, kirimJumlahBarang : jumlahBarang }, function(result){
			$("#labelTotalHarga").html(result);
		}); 
	}
}
</script>
