<center>
    ID Receipt : <?php echo $qq->row()->id; ?><br>
    Nama Pembeli : <?php echo $qq->row()->nama; ?><br>
    Alamat Pembeli : <?php echo $qq->row()->alamat; ?><br>
    Total : <?php echo duit($qq->row()->total_order); ?><br>
    <?php 
        if($qq->row()->processor == 0){
            echo "Jenis Pembayaran : Wallet <br>";
        }
        else if($qq->row()->processor==1){
            echo "Jenis Pembayaran : MidTrans<br>";
        }
        if($qq->row()->tipe==0){ 
    ?>
    <hr>
    Daftar Barang <br>
    <div class='container'>
        <div class='row'>
            <?php 
                foreach($qb as $daftar){
                    echo kartu_barang_transaksi($daftar);
                }
            ?>
        </div>
    </div>
    <?php } 
        else if($qq->row()->tipe==1){ ?>
            <hr>
            <img src="<?php echo base_url("img/bundle/".$qbun); ?>" style="width:200px;height:200px"><br>
        <?php }
        else{
            echo "ini beli wallet";
        }
    ?>
</center>
<a href="<?php echo base_url('admin/Trans') ?>">← Kembali Ke Halaman Master Transaksi</a> <br>
<a href="<?php echo base_url('admin/Dashboard') ?>">← Kembali Ke Halaman Dashboard</a> 