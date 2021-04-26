<html style="margin:0;padding:0">
	<body style='margin:0; padding:0; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;'>
		<div style="background-color: #1a237e; padding: 24px; font-size: 24px; width: 100%; color: white;"><?php echo $this->config->item('site_name') ?></div><br />
        <div style="padding: 24px;">
            <?php echo str_replace("\n",'<br />', $isi); ?>
			<br /><br />
            Terima kasih,<br />
            <?php echo $this->config->item('site_name') ?> Support
        </div>
    </body>
</html>
