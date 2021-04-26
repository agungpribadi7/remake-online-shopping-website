<?php   
    echo "<table border='1'>"; 
    echo "<tr><th>ID Refund</th><th>Email Pengirim</th><th>Tanggal Permintaan</th><th>Status Refund</th><th>Detail</th></tr>";
    foreach($q->result() as $row){
        echo form_open('admin/refund/refundadmin');
        echo form_hidden('id',$row->id_refund);      
        echo "<tr>"; 
            echo "<td>".$row->id_refund."</td>"; 
            echo "<td>".$row->email."</td>";
            echo "<td>".$row->tanggal_refund.'</td>';
            if($row->status == 0){
                echo '<td>Belum Disentuh</td>';
            }
            else if($row->status == 1){
                echo '<td>Telah Diterima</td>';
            }
            else if($row->status == 2){
                echo '<td>Permintaan Ditolak</td>';
            }
            else{
                echo '<td>Refund Selesai</td>';
            }
            echo '<td>'.form_submit('lengkap','Selengkapnya').'</td>';
        echo '</tr>';
        echo form_close();
    }
    echo "</table>";      
?>
