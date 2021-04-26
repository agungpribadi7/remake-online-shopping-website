<?php
//fungsi helper untuk online shop ini

/**
 * Apakah user sudah login?
 *
 * @return boolean
 */
function isLoggedIn() {
	return isset($_SESSION['loggedUser']);
}

/**
 * Apakah user adalah admin?
 *
 * @return boolean
 */
function isAdmin() {
	if (!isLoggedIn()) return false;
	return $_SESSION['loggedUser'] === 'admin';
}

/**
 * Mengembalikan harga dalam bentuk kode HTML
 *
 * @param int $duit
 * @param boolean $sub Apakah .000 dibelakang dikecilkan?
 * @return string
 */
function duit(int $duit, $sub = false) {
	if ($sub)
		return 'Rp'.number_format($duit, 0, '.', '.') . '<sub>.000</sub>';
	else
		return 'Rp'.number_format($duit, 0, '.', '.') . '.000';
}
