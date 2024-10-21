<!DOCTYPE html>
<html>
	<head>
		<title>Photo ABCD</title>
		<link rel="stylesheet" type="text/css" href="styles.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	</head>
	<body>
	
		<div id="header">
			<h1 class="title">
				<span class="red">Photo A</span>
				<span class="blue">B</span>
				<span class="yellow">C</span>
				<span class="green">D</span>
			</h1>
			<div id="account-box">
				<p>Not Signed In</p>
				<form class="account-form" action="login.php">
					<input class="hidden" type="submit" id="login"/>
					<label for="login" class="form-button">Login</label>
				</form>
				<form class="account-form" action="register.php">
					<input class="hidden" type="submit" id="register"/>
					<label for="register" class="form-button">Register</label>
				</form>
			</div>
		</div>
		
		<div class="content-box">
		
		</div>
		
		<div id="footer">
			<p>Fall 2024 ICS 325-50 Team Cougars</p>
		</div>
	</body>
</html>