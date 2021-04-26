<h5>Riwayat Order</h5>
<ul class="collection">
    <li>
        <div class="collapsible-header flex">
            <i class="material-icons">receipt</i>
            <span style='width:83%;'>Order #<?= $detailTransaksi->id ?></span>
            <span ><?php echo $detailTransaksi->waktu; ?></span>
        </div>
        <div class="collapsible-body row no-margin" style="display: block;">
            <div class="col s12 m6 l5">
                <h6>Penerima</h6>
                <b>Nama : </b><?php echo $detailTransaksi->nama; ?><br>
                <b>Alamat : </b><?php echo $detailTransaksi->alamat; ?>
            </div>
            <div class="col s12 m6 l4">
                <h6>Pembayaran</h6>
                Anda menggunakan :<br><b><?php echo $pembayaranMelalui; ?></b>
                <br>sebagai metode pembayaran.
            </div>
			<?php if ($detailTransaksi->tipe == 0) { ?>
				<div class="col s12 m12 l3 right-align">
					<?php if ($detailTransaksi->kode_resi !== null) { ?>
						<a href="<?= base_url("akun/order/$detailTransaksi->id/track") ?>" class="btn">Track barang</a>
					<?php } else { ?>
						<a href="#" class="btn" disabled>Sedang diproses</a>
					<?php } ?>
				</div>
			<?php } ?>
			<hr class="col s12 ">
            <div class="col s12">
				<?php if (sizeof($detailBarang) > 0) { ?>
					<ul class="cart-list collection with-header">
						<li class="collection-header"><h6>Barang</h6></li>
						<?php
						foreach($detailBarang as $row) {
							echo '<li class="collection-item cart-product" style="height:150px;padding-top:20px;">'.
									'<a class="col s12 m2" href="'.base_url().'/barang/'.$row->barang_id.'">'.
										'<div class="cart-product-image" style="width:100px; height:100px;background-size:cover;background-image: url('.base_url()."img/barang/$row->barang_id/00.jpeg".');">'.
										'</div>'.
									'</a>'.
									'<div class="cart-product-details col s12 m10 row">'.
										'<span class="title col s12">'.
											'<a href="'.base_url().'/barang/'.$row->barang_id.'">'.
												$row->nama.
											'</a>'.
										'</span>'.
										'<hr class="col s12">'.
										'<span class="col s12 m6 l9">'.
											duit($row->harga).' x '.$row->jumlah.
										'</span>'.
										'<span class="col s12 m6 l3 right-align cart-product-total">'.
											duit($row->harga * $row->jumlah).
										'</span>'.
									'</div>'.
								'</li><div style="clear:both;"></div>';
						}
						?>
					</ul>
				<?php } ?>
            </div>
        </div>
    </li>            
</ul>
