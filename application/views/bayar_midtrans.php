<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-0dvSs2kozHsFjvLJ"></script>
<h2><center>Pembayaran : Midtrans</center></h2>
<?php
    echo '<center>'.
    	"Nama Penerima : $_SESSION[namapenerima]<br />".
    	"Alamat Penerima : $_SESSION[alamatpenerima]<br />".
    	"Total Belanjaan Anda : ".duit($total).'<br /><br />'.
		materialize_form_submit(['id' => 'bayarBtn'],"Bayar").
		'<br /><br />'.
	'</center>';
?>
<script>
function midtrans_report(result) {
    console.log(result)
    $.ajax({
      method: "POST",
      url: url("/Pembayaran/midtransReport"),
      data: result
    }).done(function( result ) {
        console.log(result)
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
$("#bayarBtn").click(function() {
	$(this).attr("disabled", true);
	$.get(url("Pembayaran/prosesMidtrans"), function(hasil) {
		hasil = JSON.parse(hasil);
		snap.pay(hasil.token, {
			onSuccess: midtrans_report,
            onPending: midtrans_report,
            onError: midtrans_report,
			onClose: function() {
				location.href = url("keranjang");
			}
		});
	});
});
</script>
