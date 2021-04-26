<?php echo form_open("pembayaran/step2"); ?>
	<div class="row">
		<div class="col s12 m6"><?php
			echo '<h2>Pembayaran</h2><br />';
			$detailDiskon = "";
			if($this->session->userdata('besarDiskon')){
				$besarDiskon = $this->session->userdata('besarDiskon');
				echo "<center>Diskon : ".$besarDiskon."%</center>";
			}
			echo "<center>Total Belanjaan Anda : ".duit($totalHarga)."</center>";
			echo materialize_form_input(['name' => 'nama', 'placeholder' => 'Nama Penerima'], $loggedUser->nama).
				materialize_form_input(['name' => 'alamat', 'placeholder' => 'Alamat Penerima'], $loggedUser->alamat);
			$this->session->set_userdata('total',$totalHarga);
		?></div>
		<div class="col s12 m6"><?php
			echo '<br /><br /><center>'.
				'<h5>Pilih metode pembayaran</h5><br /><br />'.
				materialize_form_submit([
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
				).
				'<br /><br />'.
				materialize_form_submit([
					'name' => 'gowallet',
					'id' => 'btnwallet',
					'class' => 'btn-large tombolbayar'
				], 'CI-Comp Wallet').
			'</center>';
		?></div>
	</div>
<?php echo form_close() ?>
<script>
<?php 
	if($this->session->flashdata('errorpenerima')){
		echo "M.toast({html:'".$this->session->flashdata('errorpenerima')."', classes:'red'});";
	}
?>
</script>
