<html style="margin:0;padding:0">
    <body style='margin:0; padding:0; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;'>
        <div style="background-color: #ec8611; padding: 24px; font-size: 24px; width: 100%; color: white;"><?php echo $this->config->item('site_name') ?></div><br />
        <div style="padding: 24px;">
            Halo <?php echo $nama; ?>,<br />
            Untuk mengatur ulang kata sandi anda, mohon untuk mengunjungi link berikut : <br />
            <a href="<?php echo $tokenLink; ?>">Link atur ulang kata sandi</a><br />
            atau salin link berikut ke address bar bila tidak bisa di klik :<br />
            <?php echo $tokenLink ?><br /><br />
            Terima kasih,<br />
            <?php echo $this->config->item('site_name') ?> Support
        </div>
    </body>
</html>
