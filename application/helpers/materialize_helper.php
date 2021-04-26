<?php
//fungsi helper untuk materializecss

/**
 * Membuat input,
 * untuk placeholder, masukkan kedalam $data['placeholder']
 * untuk class grid seperti "col s12", "col s6 m2", dsb, masukkan kedalam $data['width-grid']
 *
 * @param array $data
 * @param string $value
 * @param string $extra
 * @return string
 */
function materialize_form_input($data = [], $value = '', $extra = '') {
	if (!is_array($data))
		$data = ['name' => $data];
	if (isset($data['placeholder'])) {
		$placeholder = $data['placeholder'];
		unset($data['placeholder']);
	}
	if (isset($data['width-grid'])) {
		$width = $data['width-grid'];
		unset($data['width-grid']);
	}
	if (isset($width))
		$begin = "<div class=\"input-field col $width\">";
	else $begin = '<div class="input-field">';
	
	if (!isset($data['id']) && isset($data['name']))
		$data['id'] = $data['name'];

	$begin .= form_input($data, $value, $extra);

	if (isset($data['id']) && isset($placeholder))
		$begin .= form_label($placeholder, $data['id']);
		
	return $begin . '</div>';
}

/**
 * Membuat input password, cara pakainya sama dengan materialize_form_input
 *
 * @param array $data
 * @param string $value
 * @param string $extra
 * @return string
 */
function materialize_form_password($data = [], $value = '', $extra = '') {
	is_array($data) OR $data = array('name' => $data);
	$data['type'] = 'password';
	return materialize_form_input($data, $value, $extra);
}

/**
 * Membuat tombol
 *
 * @param array $data
 * @param string $content
 * @param string $extra
 * @return void
 */
function materialize_form_button($data = [], $content = '', $extra = '') {
	if (isset($data['class']))
		$data['class'] .= ' waves-effect';
	else $data['class'] = 'waves-effect';
	if (strpos($data['class'], 'btn') == false)
		$data['class'] .= ' btn';
	return form_button($data, $content, $extra);
}

/**
 * Membuat tombol, dengan tipe submit
 *
 * @param array $data
 * @param string $content
 * @param string $extra
 * @return void
 */
function materialize_form_submit($data = [], $content = '', $extra = '') {
	$data['type'] = 'submit';
	return materialize_form_button($data, $content, $extra);
}

/**
 * Membuat dropdown,
 * untuk label dimasukkan kedalam $data['label'], jadi param pertamanya gak jadi string
 * tapi jadi array. Jika array maka $data['name'] dipakai untuk menggantikan yang sebelumnya berinput
 * string. Untuk class grid seperti "col s12", "col s6 m2", dsb, masukkan kedalam $data['width-grid']
 *
 * @param array $data
 * @param array $options
 * @param array $selected
 * @param string $extra
 * @return string
 */
function materialize_form_dropdown($data = [], $options = [], $selected = [], $extra = '') {
	if (is_array($data)) {
		if (isset($data['label'])) {
			$label = $data['label'];
			unset($data['label']);
		}
		if (isset($data['width-grid'])) {
			$width = $data['width-grid'];
			unset($data['width-grid']);
		}
	}
	$select = form_dropdown($data, $options, $selected, $extra);
	if (isset($width)) {
		$return = "<div class=\"input-field col $width\">";
	} else
		$return = "<div class=\"input-field\">";
	$return .= $select;
	if (isset($label)) {
		$return .= "<label>$label</label>";
	}
	
	return $return.'</div>';
}

/**
 * Membuat checkbox
 * untuk teks disebelah bisa dimasukkan kedalam $label
 *
 * @param mixed $data Bisa berupa string atau array, kalau string akan dirubah menjadi array dengan indeks name diisi string yang Anda kirim
 * @param string $value Value yang akan anda baca di PHP
 * @param string $label Label yang menjelaskan checkbox ini
 * @param boolean $checked Sudah dicek duluan?
 * @param mixed $extra
 * @return string
 */
function materialize_form_checkbox($data = '', $value = '', $label = '', $checked = false, $extra = '') {
	$ret = '<label>'.form_checkbox($data, $value, $checked, $extra);
	$ret .= "<span>$label</span>";
	
	return $ret . '</label>';
}

/**
 * Membuat radio button
 * untuk teks disebelah bisa dimasukkan kedalam $label
 *
 * @param mixed $data Bisa berupa string atau array, kalau string akan dirubah menjadi array dengan indeks name diisi string yang Anda kirim
 * @param string $value Value yang akan anda baca di PHP
 * @param string $label Label yang menjelaskan checkbox ini
 * @param boolean $checked Sudah dicek duluan?
 * @param mixed $extra
 * @return string
 */
function materialize_form_radio($data = '', $value = '', $label = '', $checked = false, $extra = '') {
	$ret = '<label>'.form_radio($data, $value, $checked, $extra);
	$ret .= "<span>$label</span>";
	
	return $ret . '</label>';
}

/**
 * Membuat tombol dan textbox untuk upload
 *
 * @param string $data
 * @param string $extra
 * @return string
 */
function materialize_form_upload($data = '', $extra = '') {
	return '<div class="file-field input-field">'.
		'<div class="btn waves-effecct"><span>File</span>'.form_upload($data, '', $extra).'</div>'.
		'<div class="file-path-wrapper"><input class="file-path validate" type="text"></div>'.
	'</div>';
}

/**
 * Menghasilkan tag HTML untuk ikon material
 *
 * @param string $ikon nama ikon
 * @return string
 */
function material_ikon($ikon) {
	return '<i class="material-icons">'.$ikon.'</i>';
}

/**
 * Menghasilkan kartu yang berisi gambar
 *
 * @param Barang $barang
 * @param Users $user (opsional) Untuk mengetahui apakah barang ini sudah masuk di wishlistnya?
 * @return string
 */
function kartu_barang(Barang $barang, Users $user = null) {
	$url = $barang->getURLGambar()[0];
	$simbolFavorite = "favorite_border";
	if($user != null && $user->isWishlist($barang->id))
		$simbolFavorite = "favorite";
	return	'<div class="col s12 m4 l3 product" data-id="'.$barang->id.'">'.
				'<div class="card hoverable">'.
					'<div class="card-image">'.
						'<img src="'.base_url($url).'" />'.
					'</div>'.
					'<div class="card-content truncate">'.$barang->nama.'</div>'.
					'<div class="card-action">'.
						'<span class="left">'.
							materialize_form_button(['class' => 'micro', 'onclick' => "wishlistBtnClick(event, this, $barang->id)"], material_ikon($simbolFavorite)).
							materialize_form_button(['class' => 'micro', 'onclick' => "cartBtnClick(event, $barang->id)"], material_ikon('add_shopping_cart')).
						'</span>'.
						'<span class="right">'.duit($barang->harga).'</span>&nbsp;</div>'.
				'</div>'.
			'</div>';
}

/**
 * Kartu Barang untuk order_items dalam master transaksi
 *
 * @param Barang $barang
 * @return void
 */
function kartu_barang_transaksi(Barang $barang) {
	$url = $barang->getURLGambar()[0];
	return	'<div class="col s12 m4 l3 product" data-id="'.$barang->id.'">'.
				'<div class="card hoverable">'.
					'<div class="card-image">'.
						'<img src="'.base_url($url).'" />'.
					'</div>'.
					'<div class="card-content truncate">'.$barang->nama.'</div>'.
					'<div class="card-action">'.
					'<span class="right">'.duit($barang->harga).'</span>&nbsp;</div>'.
				'</div>'.
			'</div>';
}
