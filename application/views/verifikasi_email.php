<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?><!DOCTYPE html>
<html lang="id">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="<?php echo base_url('css/materialize.min.css') ?>">
		<link rel="stylesheet" href="<?php echo base_url('css/material-icons.css') ?>">
		<link rel="stylesheet" href="<?php echo base_url('css/shop.css') ?>">
		<script src="<?php echo base_url('js/jquery.min.js') ?>"></script>
		<script src="<?php echo base_url('js/materialize.min.js') ?>"></script>
		<script src="<?php echo base_url('js/shop.js') ?>"></script>
		<title><?php echo $this->config->item('site_name').' - Verifikasi Email' ?></title>
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
			<div id="login-form" class="card-content">
				<h5>Verifikasi Email</h5>
				<?php
					if (isset($msg))
						echo "<div class=\"msg $bgColorMsg\">$msg</div>";
					echo '<br /><a href="'.base_url('login').'">Menuju ke login</a>';
				?>
			</div>
		</div>
	</body>
	<script>
	
	</script>
</html>
