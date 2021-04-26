<div class="row">
	<div class="col s12 m8 carousel carousel-slider main-highlight-slider">
		<?php 
			foreach($carousel as $row){
				echo '<a class="carousel-item" href="'.base_url().$row->link.'"><img src="'.base_url()."img/promo/".$row->foto.'" /></a>';
			}
		?>
	</div>
	<div class="col s12 m4 no-padding sub-highlight">
		<?php 
			foreach($carouselRandom as $row){
				echo '<a class="responsive-img" href="'.base_url().$row->link.'"><img class="responsive-img" src="'.base_url()."img/promo/".$row->foto.'" /></a>';
			}
		?>
	</div>
	<div class="col s12">
		<h5>Produk Pilihan</h5>
		<div class="scroll-horizontal"><?php
			foreach ($barangHighlight as $barang)
				echo kartu_barang($barang, $loggedUser);
		?></div>
		<h5>Produk Terbaru</h5>
		<div class="scroll-horizontal"><?php
			foreach ($barangTerbaru as $barang)
				echo kartu_barang($barang, $loggedUser);
		?></div>
	</div>
</div>

<script>
$(".main-highlight-slider").carousel({
	fullWidth: true,
	indicators: true
});
<?php 
	if($this->session->flashdata('bayarsukses')){
		echo "M.toast({html:'".$this->session->flashdata('bayarsukses')."', classes:'green'});";
	}
?>
</script>
