<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<head>
<link rel="stylesheet" href="<?php echo base_url('css/materialize.min-v1.0.0-rc.2.css') ?>">
<link rel="stylesheet" href="<?php echo base_url('css/shop.css') ?>">
<script src="<?php echo base_url('js/jquery.min.js') ?>"></script>
<script src="<?php echo base_url('js/materialize.min-v1.0.0-rc.2.js') ?>"></script>
<script src="<?php echo base_url('js/shop.js') ?>"></script>
</head>
<body>
<table>
<tr><th class="tble">Kategori</th>
    <th>ID</th>
    <th>Nama</th>
    <th>Harga</th>
    <th>Stok</th>
    <th>Deskripsi</th>
</tr>

<?php 
    $query = $this->db->query('select * from barang where id = ?', array(1));
    foreach($dataBarang as $barang) {
    

    echo '<tr class="tble">';
    
    echo "<td>$barang->kategori</td>";
    echo "<td>$barang->id</td>";
    echo "<td>$barang->nama</td>";
    echo "<td>$barang->harga</td>";
    echo "<td>$barang->stok</td>";
    echo "<td>$barang->deskripsi<button type='button' href=#>Edit</button></td>";
    echo '</tr>';


            //var_dump($barang);
} 


?></table></body></html>