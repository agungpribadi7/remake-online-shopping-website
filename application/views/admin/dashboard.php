<h2 style><center>Dashboard</center></h2>
<?php
    form_open('Dashboard/dash');
?>
<div class='row'>
<a href="<?php echo base_url('admin/barang/list')?>">
    <div class="col s3 m2">
      <div class="card orange darken-4">
        <div class="card-content white-text">
          <span class="card-title"><center>Barang <br> <?php echo $jumbarang ?> Barang </center> </span>
        </div>
      </div>
    </div>
</a>
<a href="<?php echo base_url('admin/User')?>">
    <div class="col s3 m2">
      <div class="card orange darken-4">
        <div class="card-content white-text">
          <span class="card-title"><center>User <br> <?php echo $jumuser ?> User </center> </span>
        </div>
      </div>
    </div>
</a>
<a href="<?php echo base_url('admin/Trans')?>">
    <div class="col s7 m3">
      <div class="card orange darken-4">
        <div class="card-content white-text">
          <center><span class="card-title">Transaksi <br>
          <?php echo $totaltrans ?> Transaksi</span></center>
        </div>
      </div>
    </div>
</a>
<a href="<?php echo base_url('admin/MGrafik')?>">
    <div class="col s7 m3">
      <div class="card orange darken-4">
        <div class="card-content white-text">
          <span class="card-title">Grafik Laporan<br>
          CI-Comp</span>
        </div>
      </div>
    </div>
</a>
<a href="<?php echo base_url('admin/refund/refundadmin')?>">
    <div class="col s7 m3">
      <div class="card orange darken-4">
        <div class="card-content white-text">
          <center><span class="card-title">Administrasi Refund</span>
          <p> Terdapat <?php echo $unreadrefund ?> Permintaan Refund</p></center>
        </div>
      </div>
    </div>
</a>
<a href="<?php echo base_url('admin/bundle')?>">
    <div class="col s7 m3">
      <div class="card orange darken-4">
        <div class="card-content white-text">
          <center><span class="card-title">Bundle Administration <br>
            <?php echo $totalbundle ?> Bundle </center>
          </span>
        </div>
      </div>
    </div>
</a>
<a href="<?php echo base_url('admin/MKategori')?>">
    <div class="col s3 m2">
      <div class="card orange darken-4">
        <div class="card-content white-text">
          <span class="card-title"><center>Kategori</center> </span>
        </div>
      </div>
    </div>
</a>
<a href="<?php echo base_url('admin/highlight/list')?>">
    <div class="col s3 m2">
      <div class="card orange darken-4">
        <div class="card-content white-text">
          <span class="card-title"><center>Highlight</center> </span>
        </div>
      </div>
    </div>
</a>
</div>

<?php   
    form_close();
?>
<div id='tulisan'>
    <div id="box tutupan"></div>
</div>
