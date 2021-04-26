<div class="center-align flow-text" style="padding: 12px;"><br /><br />
	<i class="material-icons" style="font-size: 96px;">check_circle</i><br />
	Terima kasih telah berbelanja di CI-Comp Shop!<br />
	Barang Anda akan kami kirim secepatnya :)<br />
	Semoga hari anda menyenangkan! :D
	<?php
	echo form_open('akun/order/'.$this->session->flashdata('hasil_order_id'), 'method="GET"').
		materialize_form_submit([], 'Lihat status transaksi').
	form_close();
	?>
<br /><br /><br /></div>
