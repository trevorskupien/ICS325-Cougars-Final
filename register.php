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
		</div>
		<div class="content-box">
		
			<h1 class="section-title">Register</h1>
			
			<form class="login-form" method="POST" enctype="multipart/form-data">
				<label class="form-label" for="name">Name</label>
				<input  class="form-text" type="text" id="name" name="name" required></input>
				
				<label class="form-label" for="email">Email Address</label>
				<input  class="form-text" type="text" id="email" name="email" required></input>

				<label class="form-label" for="password">Password</label>
				<input class="form-text" type="password" id="password" name="password" required></input>

				<label class="form-label" for="password-confirm">Retype Password</label>
				<input class="form-text" type="password" id="password-confirm" name="password-confirm" required></input>		
				
				<input class="hidden" type="submit" name="submit" id="register"/>
				<label for="register" class="form-button">Register</label>
			</form>
		</div>
		<div id="footer">
			<p>Fall 2024 ICS 325-50 Team Cougars</p>
		</div>
	</body>
</html>