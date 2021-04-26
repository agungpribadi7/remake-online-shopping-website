<div class="center-align">
	<?php if ($loggedUser->isTwoFactAuthEnabled()) { ?>
		<p>Untuk mematikan autentikasi faktor kedua, mohon masukkan kode yang dikeluarkan oleh aplikasi Anda :</p>
		<?php
			echo materialize_form_input(['id' => 'kode', 'style' => 'max-width: 100px']).
				materialize_form_button(['id' => 'kirimBtn'], 'Kirim');
		?><br /><br />
		<script>
		$("#kirimBtn").click(function() {
			var data = {
				'kode': $("#kode").val(),
			};
			$.post(location.href, data, function(ret) {
				if (ret == 'true') {
					M.toast({html: 'Autentikasi Faktor Kedua berhasil dimatikan!'});
					setTimeout(() => {
						location.href = url("akun#keamanan");
					}, 3000);
				} else {
					M.toast({html: 'Kode yang Anda masukkan tidak cocok!', 'classes': 'red'});
				}
			});
		});
		</script>
	<?php } else { ?>
		<p>Anda dapat menggunakan aplikasi authenticator Anda untuk menge-scan QR code dibawah ini</p>
		<img src="<?php echo $qrCodeUrl ?>" />
		<p>Kemudian, masukkan kode yang dikeluarkan oleh aplikasi Anda :</p>
		<?php
			echo materialize_form_input(['id' => 'kode', 'style' => 'max-width: 100px']).
				materialize_form_button(['id' => 'kirimBtn'], 'Kirim');
		?><br /><br />
		<script>
		$("#kirimBtn").click(function() {
			var data = {
				'secret': "<?php echo $secret ?>",
				'kode': $("#kode").val(),
			};
			$.post(location.href, data, function(ret) {
				if (ret == 'true') {
					M.toast({html: 'Autentikasi Faktor Kedua berhasil diaktifkan!'});
					setTimeout(() => {
						location.href = url("akun#keamanan");
					}, 2000);
				} else {
					M.toast({html: 'Kode yang Anda masukkan tidak cocok!', 'classes': 'red'});
				}
			});
		});
		</script>
	<?php } ?>
</div>
