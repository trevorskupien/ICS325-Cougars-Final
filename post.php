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
			<h1 class="section-title">My Blog</h1>
			<form class="blogpost" method="POST">
				<div class="post-header">
					<input id="title" class="form-text" type="text" name="title" placeholder="Post Title"></input>
					
					<input id="date" class="form-date" type="text" placeholder="<?php echo date("d-m-Y") ?>" readonly="readonly"></input>
					
					<input id="visibility" class="hidden" name="public" type="checkbox"></input>
					<label id="visibility-label" for="visibility" class="form-toggle"></label>
					
					<input class="hidden" type="submit" name="submit" id="post"/>
					<label for="post" class="form-button">Post</label>
				</div>
				<div class="post-body">
					<textarea id="post-content" name="post-content"></textarea>
				</div>
			</form>
		</div>
		<div id="footer">
			<p>Fall 2024 ICS 325-50 Team Cougars</p>
		</div>
	</body>
</html>