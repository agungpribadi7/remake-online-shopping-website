<?php $urlGambarArr = $databarang->getURLGambar(); ?>
<div class='row'>
	<div class="col s12 m4 left">
		<div class="material-placeholder">
			<img id="product-main-image" class="materialboxed responsive-img" class="col s12 m2" src="<?php echo base_url($urlGambarArr[0]);?>" />
		</div>
		<div class="product-images scroll-horizontal"><?php
			foreach ($urlGambarArr as $urlGambar)
				echo '<img class="col s6 m4 product-image-thumb" src="'.base_url($urlGambar).'" />';
		?></div>
	</div>
	<div class="col s12 m8" >
		<h5><?php echo $databarang->nama; ?></h5>
		<div class="flex">
			<span class="harga"><?php echo duit($databarang->harga); ?></span>
			<div class="input-field numeric">
				<label class="active" id='stok'>Jumlah</label>
				<?php echo materialize_form_button(['onclick'=>'kurangJumlah()', 'class' => 'up micro'], material_ikon('remove')); ?>
				<input id="jumlahBarang" type="number" min="1" value="1" max='<?php echo $databarang->stok; ?>' class="validate" onkeyup="cekJumlah()" />
				<?php echo materialize_form_button(['onclick'=>'tambahJumlah()', 'class'=>'down micro'],material_ikon('add')); ?>
			</div>
			<span>Sisa stok : <?php echo $databarang->stok; ?></span>
		</div>
		<?php
			echo '<br />';
			$simbolFavorite = "favorite";
			if($apakahBarangFavorite==null)
				$simbolFavorite = "favorite_border";
			echo materialize_form_button(['onclick' => "wishlistBtnClick(event, this, $databarang->id)"], material_ikon($simbolFavorite));
			echo materialize_form_button(['onclick' => "cartBtnClick(event, $databarang->id)"], material_ikon('add_shopping_cart'));
		?>
	</div>
	<div style='clear:both;'></div>
	<hr>
	<div id='deskripsi'>
		<big>Deskripsi Produk :</big> <br />
		<?php 
			echo $this->markdown->parse($databarang->deskripsi); 
			echo "<div class='col s3 m4 l6'>";
			if(strpos(strtoupper($databarang->nama),'SUPPLY') != ""){
				echo $this->markdown->parse("<img src='".base_url('/img/fotodeskripsi/7.jpg')."'>");
			}
			else if(strpos(strtoupper($databarang->nama),'CASE') != ""){
				echo "<img src='".base_url('/img/fotodeskripsi/6.jpg')."'>";
			}
			else if(strpos(strtoupper($databarang->nama),'RAM') != ""){
				echo "<img src='".base_url('/img/fotodeskripsi/5.jpg')."'>";
			}
			else if(strpos(strtoupper($databarang->nama),'PAD') != ""){
				echo "<img src='".base_url('/img/fotodeskripsi/4.jpg')."'>";
			}
			else if(strpos(strtoupper($databarang->nama),'MOUSE') != ""){
				echo "<img src='".base_url('/img/fotodeskripsi/3.jpg')."'>";
			}
			else if(strpos(strtoupper($databarang->nama),'DRIVE') != ""){
				echo "<img src='".base_url('/img/fotodeskripsi/2.jpg')."'>";
			}
			else if(strpos(strtoupper($databarang->nama),'CORE') != ""){
				echo "<img src='".base_url('/img/fotodeskripsi/1.jpg')."'>";
			}
			else if(strpos(strtoupper($databarang->nama),'HEADSET') != ""){
				echo "<img src='".base_url('/img/fotodeskripsi/8.jpg')."'>";
			}
			
			echo "</div>";
		?>
	</div>
</div>
<script>
$('.materialboxed').materialbox();
$(".product-image-thumb").first().addClass("active");
$(".product-image-thumb").click(function(e) {
	$('.product-image-thumb.active').removeClass("active");
	$(e.target).addClass("active");
	$('#product-main-image').attr("src", e.target.src);
});
$("#deskripsi ul").addClass("browser-default");
var maxStok = <?php echo $databarang->stok; ?>;
var ctrMasukWishList = 0;
function kurangJumlah(){
	var jum = $("#jumlahBarang").val();
	if(jum > 1)
		$("#jumlahBarang").val(parseInt(jum) - 1);
	else $("#jumlahBarang").val(0);
}

function tambahJumlah(){
	var jum = $("#jumlahBarang").val();
	if(jum < maxStok)
		$("#jumlahBarang").val(parseInt(jum) + 1);
	else $("#jumlahBarang").val(maxStok);
}

function cekJumlah(){
	var jum = $("#jumlahBarang").val();
	if(parseInt(jum)+1<=maxStok){
		$("#stok").html("Jumlah");
	}
	else if(jum!=""){
		$("#stok").html("<small style='color:red;'>Stok hanya tersedia :"+maxStok+"!</small>");
	}
	if(jum==""){$("#jumlahBarang").val(0);}
}

</script>
