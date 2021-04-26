<?php
    echo form_open('admin/refund/refunddetail');
        foreach($q->result() as $row){
            echo form_hidden('id',$row->id_refund);
            echo form_hidden('email',$row->email);
            echo form_hidden('nota',$row->order_id);
            echo form_hidden('barang',$row->barang_id);
            echo "ID Refund : ".$row->id_refund."<br>";
            echo "Order Receipt : ".$row->order_id.'<br>';
            echo "Email Pengirim : ".$row->email.'<br>';
            foreach($q2->result() as $row2){
                 echo "Nama Barang : ".$row2->nama.'<br>';
                 echo form_hidden('nmbarang',$row2->nama);
            }
            if($row->alasan == 'rusak'){
                echo "Alasan : Rusak".'<br>';
                echo form_hidden('alasan','Alasan : Rusak');
            }            
            else{
                echo "Alasan : Barang Tidak Sesuai Harapan".'<br>';
                echo form_hidden('alasan','Alasan : Barang Tidak Sesuai Harapan');
            }
            echo "Foto Bukti : ".'<br>';
            echo "<img src='".base_url("upload_refund/".$row->foto)."'>".'<br>';
            if($row->status == 0){
                echo "Status : Belum Disetujui".'<br>';
            }            
            else if($row->status == 1){
                echo "Status : Telah Disetujui".'<br>';
            }
            else if($row->status == 2){
                echo "Status : Ditolak".'<br>';
            }
            else if($row->status == 3){
                echo "Status : Selesai <br>";
            }
            echo "Tanggal Permintaan : ".$row->tanggal_refund.'<br>';
            echo "<hr><hr>";
            echo "Persetujuan : <br>";
            echo "<label>".form_radio('tanggapan','terima')."<span>Terima</span></label> <br>";
            echo "<label>".form_radio('tanggapan','tolak')."<span>Tolak</span></label> <br>";
            echo "<label>".form_radio('tanggapan','selesai')."<span>Selesai</span></label> <br>";
            echo form_submit('kirim','Kirim');           
        }
    echo form_close();
?>
