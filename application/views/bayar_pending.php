<div class="center-align flow-text" style="padding: 12px;"><br /><br />
	<i class="material-icons" style="font-size: 96px;">payment</i><br />
	Terima kasih telah berbelanja di CI-Comp Shop!<br />
	Mohon segera selesaikan pembayaran Anda agar barang yang anda pesan dapat segera dikirim :)<br />
	Semoga hari anda menyenangkan! :D<br /><br />
	<?php
	echo form_open('akun/order/'.$this->session->flashdata('hasil_order_id'), 'method="GET"').
		materialize_form_submit([], 'Lihat status transaksi').
	form_close();
	?>
<br /><br /><br /></div>
