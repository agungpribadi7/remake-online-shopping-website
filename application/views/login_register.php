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
		<title><?php echo $this->config->item('site_name').' - '.ucwords(str_replace('_', ' ',uri_string())) ?></title>
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
			<div id="login-form" class="card-content"<?php if (uri_string() !== 'login') echo ' style="display:none"' ?>>
				<a id="open-register" class="right"><h6>Register</h6></a>
				<h5>Login</h5>
				<?php echo form_open('login');
					if (isset($loginMsg))
						echo "<div class=\"msg $bgColorMsg\">$loginMsg</div>";
					if ($this->input->post_get('go'))
						echo form_hidden('go', $this->input->post_get('go'));
					if ($this->input->get('go'))
						echo '<div class="msg red">Maaf, Anda harus login terlebih dahulu</div>';
					if (isset($_GET['wawa']))
						echo form_hidden('wawa', true); //kunci buat login admin
					echo '<br />'.materialize_form_input(['name' => 'email', 'placeholder' => 'Email' ], '', 'autofocus required');
					echo materialize_form_password(['name' => 'pass', 'placeholder' => 'Kata Sandi'], '', 'required');
					echo '<a id="open-lupa-pass">Lupa password</a>';
					echo materialize_form_submit(['class' => 'right'], 'Login');
				echo form_close(); ?>
			</div>
			<div id="lupa-pass-form" class="card-content"<?php if (uri_string() !== 'lupa_pass') echo ' style="display:none"' ?>>
				<a id="back-login" class="right">Kembali ke login</a>
				<h5>Lupa password</h5>
				<?php echo form_open('lupa_pass');
					if (isset($lupaPassMsg))
						echo "<div class=\"msg $bgColorMsg\">$lupaPassMsg</div>";
					echo '<br />'.materialize_form_input(['name' => 'email', 'placeholder' => 'Email' ], '', 'autofocus required');
					echo materialize_form_submit(['class' => 'right'], 'Submit');
				echo form_close(); ?>
			</div>
			<div id="register-form" class="card-content"<?php if (uri_string() !== 'register') echo ' style="display:none"' ?>>
				<a id="open-login" class="right"><h6>Login</h6></a>
				<h5>Register</h5>
				<?php echo form_open('register');
					if (isset($registerMsg))
						echo "<div class=\"msg $bgColorMsg\">$registerMsg</div>";
					echo '<br />'.materialize_form_input(['name' => 'nama', 'placeholder' => 'Nama' ], '', 'autofocus required');
					echo materialize_form_input(['name' => 'email', 'placeholder' => 'Email'], '', 'required');
					echo materialize_form_password(['name' => 'password', 'placeholder' => 'Kata Sandi'], '', 'required');
					echo materialize_form_password(['name' => 'confirm-pass', 'placeholder' => 'Konfirmasi Kata Sandi'], '', 'required');
					echo materialize_form_submit(['class' => 'right'], 'Register');	
				echo form_close(); ?>
			</div>
		</div>
	</body>
	<script>
	$("#open-lupa-pass").click(function() {
		$("#login-form").fadeOut(300, function() {
			$("#lupa-pass-form").fadeIn(300);
		});
	});
	$("#back-login").click(function() {
		$("#lupa-pass-form").fadeOut(300, function() {
			$("#login-form").fadeIn(300);
		});
	});
	$("#open-register").click(function() {
		$("#login-form").fadeOut(300, function() {
			$("#register-form").fadeIn(300);
		});
	});
	$("#open-login").click(function() {
		$("#register-form").fadeOut(300, function() {
			$("#login-form").fadeIn(300);
		});
	});
	</script>
</html>
