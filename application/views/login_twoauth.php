<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?><!DOCTYPE html>
<html lang="id">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="<?php echo base_url('css/materialize.min.css') ?>" />
		<link rel="stylesheet" href="<?php echo base_url('css/material-icons.css') ?>" />
		<link rel="stylesheet" href="<?php echo base_url('css/shop.css') ?>" />
		<script src="<?php echo base_url('js/jquery.min.js') ?>"></script>
		<script src="<?php echo base_url('js/materialize.min.js') ?>"></script>
		<script src="<?php echo base_url('js/shop.js') ?>"></script>
		<title><?php echo $this->config->item('site_name').' - Autentikasi Faktor Kedua' ?></title>
		<style>
        .login-panel {
            display: flex;
            top: 100px;
            width: 600px;
            margin: auto;
        }
        .login-panel .card-content {
            width: 400px;
        }
		</style>
	</head>
	<body>
		<div class="login-panel card">
			<div class="card-image">
				<div style="height: 100%; width: 200px; background-color:#f00"></div>
			</div>
			<div class="card-content">
				<h5>Autentikasi Faktor Kedua</h5>
				<?php echo form_open();
					if (isset($msg))
						echo "<div class=\"msg $bgColorMsg\">$msg</div>";
					if ($this->input->post_get('go'))
						echo form_hidden('go', $this->input->post_get('go'));
					echo '<br />'.materialize_form_input(['name' => 'kode', 'placeholder' => 'Kode' ], '', 'autofocus required');
					echo materialize_form_submit(['class' => 'right'], 'Submit');
				echo form_close(); ?>
			</div>
			
		</div>
	</body>
	<script>
	
	</script>
</html>
