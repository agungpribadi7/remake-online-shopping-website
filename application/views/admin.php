
<html>
	<head>
		<title>Form Login</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<link rel="shortcut icon" href="img/logo.png"/>
		<link rel="stylesheet" href="viewlog/css/menu.css"/>
		<link rel="stylesheet" href="viewlog/css/main.css"/>
		<link rel="stylesheet" href="viewlog/css/bgimg.css"/>
		<link rel="stylesheet" href="viewlog/css/font.css"/>
		<link rel="stylesheet" href="viewlog/css/font-awesome.min.css"/>
		<script type="text/javascript" src="viewlog/js/jquery-1.12.4.min.js"></script>
		<script type="text/javascript" src="viewlog/js/main.js"></script>
	</head>
<body>
	
	<div class="background"></div>
	<div class="backdrop"></div>
	<div class="login-form-container" id="login-form">
		<div class="login-form-content">
			<div class="login-form-header">
				<div class="logo">
					<img src="viewlog/img/logo.png"/>
				</div>
				<h3>Login to your account</h3>
			</div>
			<form action="<?php echo base_url('login/act_login'); ?>" class="login-form" method="post">
				<div class="input-container">
					<i class="fa fa-envelope"></i>
					<input type="text" class="input" name="username" placeholder="Username"/>
				</div>
				<div class="input-container">
					<i class="fa fa-lock"></i>
					<input type="password"  id="login-password" class="input" name="password" placeholder="Password"/>
					<i id="show-password" class="fa fa-eye"></i>
				</div>
				<div class="rememberme-container">
					<input type="checkbox" name="rememberme" id="rememberme"/>
					<label for="rememberme" class="rememberme"><span>Keep me login</span></label>
					<a class="forgot-password" href="#">Forget Password?</a>
				</div>
				<input type="submit" name="login" value="Login" class="button"/>
				<a href="#" class="register">Register</a>
			</form>
			
		</div>
	</div>
</body>
</html>