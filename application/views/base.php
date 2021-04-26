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
		<title><?php echo $this->config->item('site_name') . (isset($title) ? " - $title" : '') ?></title>
		<style>
		</style>
	</head>
	<body>
		<header>
			<div class="navbar-fixed">
				<nav><div class="nav-wrapper">
					<div class="hide-on-large">
						<a href="#" data-target="nav-menu" class="sidenav-trigger nav-wrapper brand-logo">
							<i class="material-icons">menu</i>
							<span><?php echo $this->config->item('site_name') ?></span>
						</a>
					</div>
					<div class="hide-on-med-and-down">
						<a href="<?php echo base_url(isAdmin() ? 'admin' : '') ?>" class="brand-logo"><?php echo $this->config->item('site_name') ?></a>
					</div>
					<?php if (!isAdmin()) { ?>
						<ul class="right">
							<?php if (isLoggedIn()) { ?>
								<li><a href="<?php echo base_url('akun') ?>">
									<i class="material-icons">account_circle</i>
								</a></li>
							<?php } else { ?>
								<li><a href="<?php echo base_url('login') ?>">
									<span class="hide-on-small-only">Login</span>
									<i class="hide-on-med-and-up material-icons">account_circle</i>
								</a></li>
								<li class="hide-on-small-only"><a href="<?php echo base_url('register') ?>">Register</a></li>
							<?php } ?>
							<li><a id="cart-navbar" href="<?php echo base_url('keranjang') ?>" data-badge="0">
								<i class="material-icons">shopping_cart</i>
							</a></li>
						</ul>
						<form id="search-form" method="get" action="<?php echo base_url('cari') ?>">
							<div class="input-field">
								<input name="q" id="search-box" type="text" placeholder="Cari barang" value="<?php echo ($this->uri->segment(1) == 'cari' ? $this->input->get('q') : '') ?>" />
								<i class="material-icons" id="search-icon">search</i>
							</div>
						</form>
					<?php } ?>
				</div></nav>
			</div>
		</header>
		<ul id="nav-menu" class="sidenav sidenav-fixed">
			<li>
				<div class="user-view">
					<div class="background"></div>
					<?php if (isLoggedIn()) { ?>
						<?php if (!isAdmin()) { ?>
							<a href="<?php echo base_url('akun') ?>">
								<div id="sidenav-foto" class="foto" style="width:60px;height:60px;background-image:url('<?php echo base_url($loggedUser->getUrlFoto()) ?>')"></div>
								<div class="detail">
									<div class="nama"><?php echo $loggedUser->nama ?></div>
									<div class="email white-text no-padding"><?php echo $loggedUser->email ?></div>
								</div>
							</a>
						<?php } else { ?>
							<div id="sidenav-foto" class="foto" style="width:60px;height:60px;background-image:url('<?php echo base_url('img/profil/no_foto.svg') ?>')"></div>
							<div class="detail">
								<div class="nama">Administrator</div>
								<div class="email white-text no-padding">admin</div>
							</div>
						<?php } ?>
						<a href="#!" class="dropdown-trigger user-dropdown" data-target="user-dropdown-content">
							<i class="material-icons waves-effect">arrow_drop_down</i>
						</a>
						<ul id="user-dropdown-content" class='dropdown-content'>
							<li><a href="<?php echo base_url('wallet') ?>"><i class="material-icons">account_balance_wallet</i>Wallet</a></li>
							<li><a href="<?php echo base_url('logout') ?>"><i class="material-icons">exit_to_app</i>Logout</a></li>
						</ul>
					<?php } ?>
				</div>
			</li>
			<?php if (!isAdmin()) { ?>
				<li><a class="waves-effect" href="<?php echo base_url('') ?>"><i class="material-icons">home</i>Beranda</a></li>
				<li><a class="waves-effect" href="<?php echo base_url('Bundle') ?>"><i class="material-icons">local_activity</i>Bundle</a></li>
				<?php if (isLoggedIn()) { ?>
					<li><a class="waves-effect" href="<?php echo base_url('wishlist') ?>"><i class="material-icons">star</i>Wishlist</a></li>
					<li><a class="waves-effect" href="<?php echo base_url('akun') ?>"><i class="material-icons">account_circle</i>Akun</a></li>
				<?php } ?>
				<li><a class="waves-effect" href="<?php echo base_url('bantuan') ?>"><i class="material-icons">help</i>Bantuan</a></li>
			<?php } else { ?>
				<li><a class="waves-effect" href="<?php echo base_url('admin') ?>"><i class="material-icons">dashboard</i>Dashboard</a></li>
				<li><a class="waves-effect" href="<?php echo base_url('admin/barang') ?>"><i class="material-icons">desktop_windows</i>Barang</a></li>
				<li><a class="waves-effect" href="<?php echo base_url('admin/MKategori') ?>"><i class="material-icons">category</i>Kategori</a></li>
				<li><a class="waves-effect" href="<?php echo base_url('admin/bundle') ?>"><i class="material-icons">local_activity</i>Bundle</a></li>
				<li><a class="waves-effect" href="<?php echo base_url('admin/user') ?>"><i class="material-icons">account_circle</i>Klien</a></li>
				<li><a class="waves-effect" href="<?php echo base_url('admin/trans') ?>"><i class="material-icons">assignment</i>Penjualan</a></li>
				<li><a class="waves-effect" href="<?php echo base_url('admin/MGrafik') ?>"><i class="material-icons">assessment</i>Laporan</a></li>
				<li><a class="waves-effect" href="<?php echo base_url('admin/refund') ?>"><i class="material-icons">assignment_late</i>Refund</a></li>
			<?php } ?>
		</ul>
		<main>
			<?php
			if (isset($useContainer) && !$useContainer)
				echo $isi;
			else echo '<div class="container">'.$isi.'</div>';
			?>
			<footer class="page-footer">
				<div class="container">
					<div class="row">
						<div class="col s12 m6">
							<h5>Tentang</h5>
							CI-Shop adalah proyek website online eksperimental yang dibuat dengan menggunakan framework CodeIgniter dan menggunakan metode pembayaran MidTrans.
						</div>
						<div class="col s12 m6">
							<h5>Bantuan</h5>
							<a href="<?php echo base_url('terms') ?>">Syarat dan Ketentuan</a><br />
							<a href="<?php echo base_url('contact') ?>">Hubungi Kami</a><br />
							<a href="<?php echo base_url('refund') ?>">Keluhan Barang</a><br />
						</div>
					</div>
				</div>
				<div class="footer-copyright"><div class="container">2018 © <?php echo $this->config->item('site_name') ?></div></div>
			</footer>
		</main>
	</body>
	<script src="<?php echo base_url('js/shop.js') ?>"></script>
	<script>
	function butuhLogin() {
		if (<?php echo (isLoggedIn() ? 'false' : 'true') ?>) {
			location.href = "<?php echo base_url('login?go='.uri_string()) ?>";
			throw new Error("User belum login! Redirect ke login...");
		}
	}
	function url($url) {
		return "<?php echo base_url() ?>" + $url;
	}
	init();
	$("a[href='<?php echo current_url() ?>']").parent().addClass("active");
	$(".sidenav li.active").parents("ul.collapsible").collapsible("open", $(".sidenav li.active").parent().parent().parent().index());
	$(".product").click(function(e) {
		var id = $(e.currentTarget).data('id');
		location.href = "<?php echo base_url('barang') ?>/" + id;
	});
	</script>
</html>
