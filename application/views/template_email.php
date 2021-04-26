<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?><!DOCTYPE html>
<html lang="id">
	<head>
		<meta charset="utf-8">
		<title><?php echo $site_name . ($title !== null ? " - $title" : '') ?></title>
		<style>
		</style>
	</head>
	<body>
		<header>
			<div class="navbar-fixed">
				<nav><div class="nav-wrapper">
					<div style="background-color:#fe8d0c;width:100%;padding:2%;color:white;">
						<span><?php echo "<b><font size='9'>CI-Comp<br></b></font>" ?></span>
						<span><?php echo "<font size='7'>".$site_name."</font>"; ?></span>
					</div>
				</div></nav>
			</div>
		</header>
		<main>
			<div style="width:100%;padding:2%;"><font size='3'><?php echo $isi ?></font></div>
			<footer class="page-footer">
				<div class="container">
					<div class="row">
						<div style="background-color:#fe8d0c;width:100%;padding:2%;color:white;">
							<div style="width:70%;">
								<font size='4'>Tentang<br></font>
								<font size='3'>CI-Shop adalah proyek website online eksperimental yang dibuat dengan menggunakan framework CodeIgniter dan menggunakan metode pembayaran MidTrans.</font>
							</div>
						</div>
					</div>
				</div>
				<div class="footer-copyright"><div class="container"><div style="background-color:#ef8711;width:100%;padding:2%;"><font size='2'> 2018 © <?php echo $site_name ?></div></div>
			</footer>
		</main>
	</body>
</html>