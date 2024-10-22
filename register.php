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
		
			<h1 class="section-title">Register</h1>
			<p id="form-result">
				<?php
					function registerUser(){
						if(!isset($_POST["submit"])){
							return;
						}
						
						$name = $_POST["name"];
						$email = $_POST["email"];
						$password = $_POST["password"];
						$password_confirm = $_POST["password-confirm"];
						
						//Verify that data is valid
						if(!str_contains($email, "@") || !str_contains($email, ".")){
							echo "Please enter a valid email address.";
							return;
						}
						
						if(strcmp($password, $password_confirm)){
							echo "Entered passwords do not match.";
							return;
						}
						
						if(!substr_count($name, '+') === 1){
							echo "Please enter a first and last name.";
							return;
						}
						$name_tokens = explode(" ", $name);
						$first_name = $name_tokens[0];
						$last_name = $name_tokens[1];
						
						//Here goes nothin'
						$db = mysqli_connect("localhost", "root", "", "photo_cougars_db");
						if(!$db){
							die("Connection failed: " . mysqli_connect_error());
						}
						
						$stmt = mysqli_stmt_init($db);
						mysqli_stmt_prepare($stmt, "INSERT INTO users (email, first_name, last_name, password) VALUES (?, ?, ?, ?)");
						mysqli_stmt_bind_param($stmt, "ssss", $email, $first_name, $last_name, $password);
						$result = mysqli_stmt_execute($stmt);
						
						if($result){
							echo "Registration successful!";
						}else{
							echo "Database error while registering.";
						}
					}

					registerUser();
				?>
			</p>

			<form class="login-form" method="post">
				<label class="form-label" for="name">Name</label>
				<input  class="form-text" type="text" id="name" name="name" required></input>
				
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
		<div id="footer">
			<p>Fall 2024 ICS 325-50 Team Cougars</p>
		</div>
	</body>
</html>