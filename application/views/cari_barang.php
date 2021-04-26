<div class="container">
	<div class="row"><?php
		echo form_open('cari', ['method' => 'get']).
			materialize_form_input(['name' => 'q', 'width-grid' => 's12 m3', 'placeholder' => 'Cari barang'], $this->input->get('q')).
			materialize_form_dropdown(['name' => 'k', 'label' => 'Kategori', 'width-grid' => 's12 m3', 'onchange' => 'this.form.submit()'], $dropdownKategori, $this->input->get('k')).
			materialize_form_dropdown(['name' => 'o', 'label' => 'Urutkan', 'width-grid' => 's12 m3', 'onchange' => 'this.form.submit()'], $dropdownOrderBy, $this->input->get('o')).
			materialize_form_dropdown(['name' => 't', 'label' => 'Jumlah per halaman', 'width-grid' => 's12 m3', 'onchange' => 'this.form.submit()'], $dropdownJumlah, $this->input->get('t')).
			form_submit('', '', 'style=display:none').
		form_close();
	?></div>
	<div class="row">
		<?php foreach ($arrBarang as $barang)
			echo kartu_barang($barang);
		?>
	</div>
	<?php echo $pagination_links ?>
</div>
