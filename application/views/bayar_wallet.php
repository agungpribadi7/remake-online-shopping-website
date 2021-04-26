<h2><center>Pembayaran : Wallet</center></h2>
<?php
    echo "<center>";
    echo "Nama Penerima : ".$_SESSION['namapenerima']."<br>";
    echo "Alamat Penerima : ".$_SESSION['alamatpenerima']."<br>";
    echo "Total Belanjaan Anda : ".duit($total)."<br>";
    echo "Wallet Anda : ".duit($walletsaya)."</br>";
    echo "</center>";
    echo form_open("Pembayaran/bayar");
        echo form_hidden("total",$total);
        if($total > $walletsaya){
            echo "<center>".materialize_form_button(array("disabled"=>'disabled',"name"=>"gobeli"),"Wallet Tidak Cukup")."</center>";
        }
        else{
            echo "<center>".materialize_form_submit(["name"=>"belipakaiwallet","value"=>"Bayar"],"Bayar")."</center>";
        }
        echo "<br>";
        
    echo form_close();
?>
