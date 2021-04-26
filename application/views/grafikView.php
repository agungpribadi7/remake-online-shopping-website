<!DOCTYPE html>
<html>
<head>
	<title>Grafik</title>

	<?php
        foreach($detail->result() as $data){
            $total[] = $data->kiri;
            $tanggal[] = $data->bawah;
        }
    ?>
</head>
<body>
    <?php 
        $option = array('penjualan'=>'Penjualan Bulanan','barangterjual'=>"Penjualan Barang","pembelianuser"=>"Pembelian User");
        echo form_open('admin/MGrafik');
        echo "<div class='container'><div class='row'>";
        echo materialize_form_dropdown(['width-grid'=>'200px','label'=>'Pilih Kategori','name'=>'kategori'
        ,'class'=>'col s12 m4'],$option);
        echo materialize_form_submit(['name'=>'change','value'=>'change'],'Ganti');
        echo "</div></div>";
        echo form_close();
        echo "<h5>".$info."</h5>";
    ?>

	<canvas id="canvas" width="1200px" height="1200px"></canvas>

	<!--Load chart js-->
	<script type="text/javascript" src="<?php echo base_url().'js/Chart.bundle.js'?>"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/utils.js'?>"></script>
	<script>
            var config = {
			type: 'line',
			data: {
				labels: <?php echo json_encode($tanggal); ?>,
				datasets: [{
					label: <?php echo json_encode($bawah); ?>,
					backgroundColor: window.chartColors.red,
					borderColor: window.chartColors.red,
					data: <?php echo json_encode($total); ?>,
					fill: false,
				}]
			}
		};

		window.onload = function() {
			var ctx = document.getElementById('canvas').getContext('2d');
			window.myLine = new Chart(ctx, config);
		};    
   	</script>
</body>
</html>
