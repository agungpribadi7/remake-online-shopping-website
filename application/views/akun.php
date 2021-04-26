<div class="row">
	<div class="col s12 m6 l5"><?php
	if ($loggedUser->isTwoFactAuthEnabled())
		$twoFactBtn = materialize_form_submit(['class' => 'btn-small green'], 'Aktif').
			'<br />klik untuk mematikan faktor kedua.';
	else $twoFactBtn = materialize_form_submit(['class' => 'btn-small red'], 'Nonaktif').
		'<br />klik untuk mengaktifkan faktor kedua.';
	echo '<div class="card"><br />'.
			'<div class="foto-profil besar circle" style="background-image: url('.$biodata->getUrlFoto().')"></div>'.
			'<br />'.
			'<ul class="tabs tabs-fixed-width">'.
				'<li class="tab"><a href="#biodata">Biodata</a></li>'.
				'<li class="tab"><a href="#keamanan">Keamanan</a></li>'.
			'</ul>'.
			'<div class="card-content">'.
				'<div id="biodata">'.
					materialize_form_input(['id' => 'email', 'placeholder' => 'Email'], $biodata->email, 'readonly').
					materialize_form_input(['id' => 'nama', 'placeholder' => 'Nama'], $biodata->nama).
					materialize_form_input(['id' => 'alamat', 'placeholder' => 'Alamat'], $biodata->alamat).
					materialize_form_input(['id' => 'tglLahir', 'placeholder' => 'Tanggal Lahir', 'class' => 'datepicker'], $biodata->tanggallahir).
					form_label('Jenis Kelamin', '', ['class' => 'active']).'<br />'.
						materialize_form_radio(['name' => 'jenisKelamin'], '0', 'Tidak diketahui', $biodata->jeniskelamin == 0).
						materialize_form_radio(['name' => 'jenisKelamin'], '1', 'Laki-laki', $biodata->jeniskelamin == 1).
						materialize_form_radio(['name' => 'jenisKelamin'], '2', 'Perempuan', $biodata->jeniskelamin == 2).
					'<br /><br />'.
					materialize_form_submit(['id' => 'updateBiodataBtn'],'Perbarui biodata').
				'</div>'.
				'<div id="keamanan">'.
					'<div id="passMsg" class="msg" style="display:none"></div>'.
					materialize_form_password(['id' => 'oldPwd', 'placeholder' => 'Password sekarang']).
					materialize_form_password(['id' => 'newPwd', 'placeholder' => 'Password baru']).
					materialize_form_password(['id' => 'newPwd2', 'placeholder' => 'Konfirmasi password baru']).
					materialize_form_submit(['id' => 'updatePassBtn'], 'Ubah password').'<br /><br /><hr />'.
					form_open('akun/twofact', 'method="GET"').
						'Anda juga dapat memperkuat keamanan akun Anda dengan menambahkan faktor kedua untuk autentikasi<br />'.
						'<b>Status</b> : '.$twoFactBtn.
					form_close().
				'</div>'.
			'</div>'.
		'</div>';
	?></div>
	<div class="col s12 m6 l7">
	<?php
	$showItem = array("5"=>"5","10"=>"10","15"=>"15");
	echo '<div class="card">'.
			'<ul class="tabs tabs-fixed-width">'.
				'<li class="tab"><a href="#riwayat">Riwayat Pembelian</a></li>'.
				'<li class="tab"><a href="#voucherSaya">Voucher Saya</a></li>'.
				'<li class="tab"><a href="#voucherBeli">Beli Voucher</a></li>'.
			'</ul>'.
			'<div class="card-content">'.
				'<div id="riwayat">'.
					form_open('Akun/profile').
						'<div class="col s8 m4"><br>Tampilkan Item Sebanyak</div>'.
						'<div class="col s8 m4">'.
							materialize_form_dropdown(['name'=>'jumlahShowItem'],$showItem,$jumlahShowItem).
						'</div>'.
						'<div class="col s8 m4"><br>'.
							materialize_form_submit(['name'=>'sbShow','value'=>'sbShow'],'Submit').
						'</div>'.
					form_close().
					'<table>'.
						'<thead>'.
							'<tr>'.
								'<th>ID</th>'.
								'<th>Total Belanja</th>'.
								'<th>Tanggal Transaksi</th>'.
								'<th>Aksi</th>'.
							'</tr>'.
						'</thead>'.
						'<tbody>';
							foreach($dataTransaksi->result() as $row){
							echo '<tr>'.
									'<td>'.$row->id.'</td>'.
									'<td>'.duit($row->total_order).'</td>'.
									'<td>'.$row->waktu.'</td>'.
									'<td>'.
										form_open('akun/order/'.$row->id).
											materialize_form_submit([],'Detail').
										form_close().
									'</td>'.
								'</tr>';
							}
					echo '</tbody>'.
					'</table>Halaman : '.
					$url_link.
				'</div>'.
				'<div id="voucherSaya">'.
					'<div class="responsive-table table-status-sheet">'.
						'<table class="bordered" style="width:100%;">'.
							'<thead style="display:table;width:calc(100% - 1em);table-layout:fixed;">'.
								'<tr>'.
									'<th class="center">Tipe Voucher</th>'.
									'<th class="center">Kode Voucher</th>'.
								'</tr>'.
							'</thead>'.
							'<tbody style="display:block;height:120px;overflow:auto;">';
								foreach($dataPoin->result() as $row){
									echo '<tr style="display:table;width:100%;table-layout:fixed;">';
										switch($row->tipe_voucher) {
											case 0:
												echo '<td>Diskon 5%</td>';
												break;
											case 1:
												echo '<td>Diskon 10%</td>';
												break;
											case 2:
												echo '<td>Diskon 25%</td>';
												break;
										}	
										echo '<td>'.$row->key_voucher.'</td>'.
									'</tr>';
								}
					echo '</tbody>'.
						'</table>'.
					'</div>'.
				'</div>'.
				'<div id="voucherBeli">';
				echo '<center><h5>Poin : '. $biodata->poin.'</h5></center><br>';
					for($i = 0;$i < 3;$i++){
						$hargaVoucher = 0;
						$diskon = 0;
						echo '<center><div style ="'."background-image: url('".base_url()."img/diskon/$i.jpg"."');width:50%;height:140px;background-repeat:no-repeat;".'background-size:100% 100%"></div>'.
							'<br /><br />Beli Voucher ';
							if($i ==0 ) {
								$hargaVoucher = 100;
								$diskon = 5;
							}
							else if($i ==1 ) {
								$hargaVoucher = 175;
								$diskon = 10;
							}
							else if($i ==2 ) {
								$hargaVoucher = 300;
								$diskon = 25;
							}
						echo "($hargaVoucher) Poin";
						echo '&nbsp;&nbsp;&nbsp;<button data-target="modal'.$i.'" class="btn modal-trigger">Tukar</button>'.
							'<div id="modal'.$i.'" class="modal">'.
								'<div class="modal-content">'.
									'<h4>Konfirmasi Penukaran</h4>'.
									"<p>Apakah kamu ingin menukarkan $hargaVoucher Poin dengan voucher diskon $diskon%?</p>".
								'</div>'.
								'<div class="modal-footer">'.
									form_open("Akun/profile/0/$i").
										'<a href="#" class="waves-effect waves-red btn-flat" onclick="tutupModal()">BATAL</a>'.
										materialize_form_submit(['class'=>'waves-effect waves-green btn-flat','name'=>'tukar','value'=>'tukar'],'tukar').
									form_close().
							'</div></center><br>';
					}
			echo '</div>'.
			'</div>'.
		'</div>';
	?></div>
</div>
<script>  
<?php
	if($this->session->flashdata('msgPoin') == 1){
		echo "M.toast({html:'Poin Berhasil Ditukarkan', classes:'green'});";
	}
	else if($this->session->flashdata('msgPoin') == 2){
		echo "M.toast({html:'Poin Tidak Mencukupi', classes:'red'});";
	}
?>
$('.modal').modal();

function tutupModal(){
	$('#modal0, #modal1, #modal2').modal('close');
}
$("#updateBiodataBtn").click(function() {
	var btn = $(this);
	btn.addClass("disabled");
	var data = {
		id: "<?php echo $biodata->id ?>",
		nama: $("#nama").val(),
		alamat: $("#alamat").val(),
		tanggallahir: $("#tglLahir").val(),
		jeniskelamin: $("input[name='jenisKelamin']:checked").val()
	};
	$.post("<?php echo base_url('akun/editBiodata') ?>", data, function(ret) {
		btn.removeClass("disabled");
		M.toast({html: 'Biodata berhasil diperbarui!'});
	}).fail(ajaxError);
});
$("#updatePassBtn").click(function() {
	var btn = $(this);
	btn.addClass("disabled");
	var newPass = $("#newPwd").val();
	var newPassConfirm = $("#newPwd2").val();
	if (newPass != newPassConfirm) {
		$("#passMsg").removeClass("green")
			.addClass("red")
			.text("Password baru dan konfirmasi password tidak cocok!")
			.slideDown(300);
		btn.removeClass("disabled");
		return;
	}
	$data = {
		oldPwd: $("#oldPwd").val(),
		newPwd: newPass
	};
	$.post("<?php echo base_url('akun/gantiPassword') ?>", $data, function(ret) {
		$("#passMsg").removeClass("red")
			.addClass("green")
			.text("Password sukses diubah!")
			.slideDown(300)
			.delay(5000)
			.slideUp(300);
		$("#oldPwd, #newPwd, #newPwd2").val("");
	}).always(function() {
		btn.removeClass("disabled");
	}).fail(function(xhr) {
		var json = JSON.parse(xhr.responseText);
		$("#passMsg").removeClass("green")
			.addClass("red")
			.text(json.msg)
			.slideDown(300);
	});
});
var tabelTerakhir2 = -1;
var klikPassword = 0;
var klikFoto = 0;
var klikEmail = 0;
var waktu = 0;
var timer = "";
function gantiFoto(){
	klikFoto++;
	var muncul = 0;
	if(klikFoto % 2 ==0 || tabelTerakhir2 !=-1){
		document.getElementById("tableAccount").deleteRow(tabelTerakhir2);
		if(tabelTerakhir2!=3) muncul = 1;
		tabelTerakhir2 = -1;
		klikAlamat = klikTanggal = klikKelamin = klikPassword = klikFoto = 0;
	}
	else {
		muncul = 1;
	}
	if(muncul==1){
		var table = document.getElementById("tableAccount");
		var row = table.insertRow(3);
		tabelTerakhir2 = 3;
		var cell1 = row.insertCell(0);
		cell1.colSpan = 3;
		cell1.innerHTML = '<ul class="collection"><form action="<?php echo site_url('Akun/gantiFoto');?>"enctype="multipart/form-data" method="post" accept-charset="utf-8"><li class="collection-item">Pilih Foto Profile Baru <input type="file" name="userfile"></li>'+
		'<li class="collection-item" style="color:red;"><small>Untuk mengubah foto profile, terdapat beberapa rekomendasi yaitu : <br>Ukuran File Maximal 2Mb<br>Ukuran Foto harus sama sisi atau berupa rectangle<br>Ukuran foto maximal 750 pixel x 750 pixel</small></li><input type="submit" name="btnUpload" class="waves-effect waves-light left btn" value="Ubah"></form>';
	}
}

</script>
