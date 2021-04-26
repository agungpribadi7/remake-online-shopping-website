<center><h2>Bundle yang Tersedia</h2></center><br>
<style>
    #picbundle{
        transition: opacity 0.3s;
    }
    #picbundle:hover{
        opacity:0.8;
    }
</style>
<div class='row'>
<?php 
    foreach($bundles->result() as $bundle){ ?>
        <a href="<?php echo base_url("bundle/$bundle->id"); ?>"  id="picbundle">
            <img src="<?php echo base_url("img/bundle/$bundle->gambar"); ?>"style="height:200px;width:400px;">
        </a><br>
    <?php
    }
?>
</div>
