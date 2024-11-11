<!DOCTYPE html>
<html>
	<head>
		<title>Photo ABCD</title>
		<link rel="stylesheet" type="text/css" href="styles.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	</head>
	<body>
		<div id="global-wrapper">
			<?php include "inc/header.php"; ?>
			<div id="body">
				<div class="form-content-box">
				
					<h1 class="section-title">Register</h1>
					<p id="form-result">
						<?php
							if(isset($_GET["error"])){
								switch($_GET["error"]){
									case 'email':
										echo "Please enter a valid email address.";
										break;
									case 'name':
										echo "Please enter your full name.";
										break;
									case 'password':
										echo "Passwords do not match.";
										break;
									case 'taken':
										echo "Email already registered.";
										break;
								}
							}
						?>
					</p>

					<form action="inc/signup.php" class="login-form" method="post">
						<label class="form-label" for="name">Name</label>
						<div>
							<input  class="form-text-inline" type="text" id="first-name" name="first-name" placeholder="First" required></input>
							<input  class="form-text-inline" type="text" id="last-name" name="last-name" placeholder="Last" required></input>
						</div>
						<label class="form-label" for="email">Email Address</label>
						<input  class="form-text" type="text" id="email" name="email" required></input>

						<label class="form-label" for="password">Password</label>
						<input class="form-text" type="password" id="password" name="password" required></input>

						<label class="form-label" for="password-confirm">Retype Password</label>
						<input class="form-text" type="password" id="password-confirm" name="password-confirm" required></input>		
						
						<input class="hidden" type="submit" name="submit" id="post"/>
						<label for="post" class="form-button">Register</label>
					</form>
				</div>
			</div>
			<div id="footer">
				<p>Fall 2024 ICS 325-50 Team Cougars</p>
			</div>
		</div>
	</body>
</html>