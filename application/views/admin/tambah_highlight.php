<h4>Insert Highlight Baru</h4><br>
<?php

    echo form_open_multipart('admin/highlight/addHighlight');
        echo "Menuju Link : <br>".materialize_form_input(['name'=>'toLink','placeholder'=>'Link']);
        echo "Foto Highlight : <br>".form_upload('userfile')."<br>";
        echo form_submit('submitadd','Insert Barang Baru');
    echo form_close();
?>
