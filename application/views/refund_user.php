<?php 
    echo "<table border=1>";
    echo "<tr>";
        echo "<th>No Nota</th>";
        echo "<th>Barang</th>";
        echo "<th>Tanggal Pembelian</th>";
        echo "<th>Action</th>";
    echo "</tr>";    
    if($q->num_rows() > 0){
        foreach($q->result() as $row){       
            $nota = $row->order_id;               
            $q2 = $this->ModelReceipt->getbarang($nota);
            foreach($q2->result() as $rowz){     
                echo '<tr>'; 
                echo form_open('Refund/refunduser');       
                echo "<td>".$nota.'</td>';
                echo form_hidden('nota',$row->order_id);    
                echo "<td>".substr($rowz->nama,0,30).".....</td>"; 
                echo "<td>".substr($row->waktu,0,10)."</td>";                  
                if($rowz->is_refunded == 0){
                    echo "<td>".form_submit('submitnota','Selengkapnya').'</td>';
                }
                else{
                    echo "<td>".form_submit('submitnota','Selengkapnya','disabled').'</td>';
                }
                echo form_close();
                echo '</tr>';
            }
            
        }
        
    }   
    echo "</table>";
?>
<script>
<?php 
	if($this->session->flashdata('pesan')){
		echo "M.toast({html:'".$this->session->flashdata('pesan')."', classes:'green'});";
	}
?>
</script>
