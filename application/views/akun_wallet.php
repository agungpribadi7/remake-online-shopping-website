<h4>Top up wallet</h4>
<div class="row">
	<div class="col s12 m4">
		<h5>Saldo anda</h5>
		<h4><?php echo duit($loggedUser->wallet) ?></h4>
	</div>
	<div class="col s12 m8">
		<div class="col s12 m6">
			<h5>Top up</h5>
			<div class="flex">
				<div class="input-field flex-grow">
					<input value="1" min="1" id='txtAddWallet' type="number" class="validate right-align" />
					<label for="txtAddWallet">Jumlah top up wallet</label>
				</div>
				<span style="align-self: center;">.000</span>
			</div>
		</div>
		<div class="col s12 m6">
			<h6>Bayar meggunakan</h6>
			<?php echo materialize_form_submit(
				[
					'name' => 'midtrans',
					'id' => 'btnmidtrans',
					'class' => 'tombolbayar btn-flat',
					'style' => '
						padding: 10px 30px 8px 30px;
						height: auto;
						line-height: normal;
					'
				],
				'<img src="'.base_url("img/asset/midtrans.svg").'" />'
			); ?>
		</div>
	</div>
</div>
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-0dvSs2kozHsFjvLJ"></script>
<script>
function midtrans_report(result) {
	$.post(url("Pembayaran/midtransReport"), result, function() {
		switch (result.status_code) {
			case "200":
				location.href = url("pembayaran/sukses");
				break;
			case "201":
				location.href = url("pembayaran/pending");
				break;
			default:
				location.href = url("pembayaran/error");
				break;
		}
	});
}
$('#btnmidtrans').click(function() {
	$(this).fadeTo(200, 0.3)
		.attr("disabled", true);
	$.post(url("Pembayaran/prosesMidtransWallet"), {'topup': $("#txtAddWallet").val()}, function(hasil) {
		hasil = JSON.parse(hasil);
		snap.pay(hasil.token, {
			onSuccess: midtrans_report,
			onPending: midtrans_report,
			onError: midtrans_report,
			onClose: function() {
				
			}
		});
	});
});
</script>
