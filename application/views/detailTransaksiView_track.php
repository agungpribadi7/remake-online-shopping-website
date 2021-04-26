<h5>Riwayat Order</h5>
<ul class="collection">
    <li>
        <div class="collapsible-header flex">
            <i class="material-icons">receipt</i>
            <span style='width:83%;'>Order #<?= $transaksi->id ?></span>
            <span ><?php echo $transaksi->waktu; ?></span>
        </div>
        <div class="collapsible-body row no-margin" style="display: block;">
			<a href="<?= base_url("akun/order/$transaksi->id") ?>">&lt; Kembali ke detail</a><br />
			<b>Nomor resi : </b><?= $transaksi->kode_resi ?><br />
			<ul class="collection">
			<?php foreach ($dariJNE->result->history as $history) {
				echo '<li class="collection-item">'.
					$history->desc.
					'<span class="right">'.$history->date.'</span>'.
				'</li>';
			} ?>
			</ul>
        </div>
    </li>            
</ul>
