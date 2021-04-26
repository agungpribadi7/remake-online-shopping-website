<?php
$route['default_controller'] = 'Home';
$route['404_override'] = 'ErrorHandler/NotFound';
$route['translate_uri_dashes'] = FALSE;

//AKUN
$route['login'] = 'Akun/login';
$route['register'] = 'Akun/register';
$route['login/twofact'] = 'Akun/login_twofact';
$route['logout'] = 'Akun/logout';
$route['akun'] = 'Akun/profile';
$route['akun/order/(:num)'] = 'Akun/showDetailTransaksi/$1';
$route['akun/order/(:num)/track'] = 'Akun/showDetailTransaksi_tracking/$1';
$route['wallet'] = 'Akun/wallet';
$route['wishlist'] = 'Home/showwishlist';
$route['wishlist/(:num)'] = 'Home/showwishlist/$1';
$route['lupa_pass'] = 'Akun/lupa_pass';
$route['lupa_pass/(.*)'] = 'Akun/lupa_pass/$1';
$route['verifikasi/(.*)'] = 'Akun/verifikasi/$1';

//BARANG
$route['cari'] = 'Home/cariBarang';
$route['cari/(:num)'] = 'Home/cariBarang/$1';
$route['barang/(.*)'] = 'Home/barang/$1';

//BUNDLE
$route['bundle/(:num)'] = 'Bundle/detail/$1';

//BELANJA, KERANJANG, PEMBELIAN
$route['keranjang'] = 'ShoppingCart/showCart';
$route['pembayaran'] = 'ShoppingCart/Pembayaran';

//HALAMAN STATIC LAINNYA
$route['terms'] = 'Terms/showTerm';

//bagian admin
$route['admin'] = 'admin/Dashboard/index';
$route['admin/barang'] = 'admin/MBarang/list';
$route['admin/barang/(.*)'] = 'admin/MBarang/$1';
$route['admin/bundle'] = 'admin/MBundle/index';
$route['admin/bundle/(.*)'] = 'admin/MBundle/$1';

//titiw
$route['bantuan'] = 'Home/bantuan';
