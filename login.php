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
		
			<h1 class="section-title">Login</h1>
			<form class="login-form" method="POST">
				<label class="form-label" for="email">Email Address</label>
				<input  class="form-text" type="text" id="email" name="email" required></input>

				<label class="form-label" for="password">Password</label>
				<input class="form-text" type="password" id="password" name="password" required></input>		
				<input class="hidden" type="submit" name="submit" id="post"/>
				<label for="post" class="form-button">Login</label>
			</form>
		</div>
		<div id="footer">
			<p>Fall 2024 ICS 325-50 Team Cougars</p>
		</div>
	</body>
</html>