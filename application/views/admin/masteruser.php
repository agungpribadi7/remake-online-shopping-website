<script>
    $(document).ready(function() {
        $('.modal').modal();
    });
</script>
<h2><center>User Administration</center></h2>
<table class='highlight'>
    <thead>
        <th>User</th>
        <th>Detail</th>
    </thead>    
    <?php      
        foreach($q as $user){
            echo "<tr>";
                echo "<td> <div><img src='".base_url($user->getUrlFoto())."'></div><div>".$user->nama."<br>".$user->email."</div></td>";         
                ?>
                <td><a class="waves-effect waves-light btn modal-trigger" href="<?php echo '#modal'.$user->id; ?>">Detail</a> 
                <?php echo "</td>";     
            echo "</tr>";
        } 
    ?>
</table>
<?php
    foreach($q as $user){ ?>
        <div id="<?php echo 'modal'.$user->id;  ?>" class="modal">
            <div class="modal-content">
                <center><h4>Detail User : <?php echo $user->nama?></h4></center><br>
                <div style="display:flex; justify-content:center;">
                    <div id='b2'>
                        <img src=<?php echo base_url($user->getUrlFoto()) ?> style="height:200px; width:200px">
                    </div>
                    <div id='b1' style="display:inline">
                        <?php
                        echo "User ID : ".$user->id."<br>";
                        echo "Email : ".$user->email."<br>";
                        echo "Password : ".$user->password."<br>";
                        echo "Nama Lengkap : ".$user->nama."<br>";
                        echo "Alamat : ".$user->alamat."<br>";
                        echo "Tanggal Lahir : ".$user->tanggallahir."<br>";
                        if($user->jeniskelamin == 0)
                            echo "Jenis Kelamin  : Laki-Laki <br>";
                        else    
                            echo "Jenis Kelamin  : Perempuan<br>";
                        echo "Wallet         : ".$user->wallet."<br>";
                        if($user->is_banned==0){
                            echo "<span style='color:green'>User ini tidak dibanned </span><br>";
                        }
                        else{
                            echo "<span style='color:red'>User ini sudah dibanned</span> <br>";
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer"><?php
                if($user->is_banned==0){ ?>
                    <center><a href="<?php echo base_url('admin/User/delete/'.$user->id); ?>" 
                    class="modal-close waves-effect waves-green btn-flat">Ban User</a></center>
                <?php
                }       
                ?>
            </div>
        </div>
    <?php } ?>
<a href="<?php echo base_url('admin/Dashboard') ?>">← Kembali Ke Halaman Dashboard</a>
