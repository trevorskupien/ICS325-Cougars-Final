<!DOCTYPE html>
<html>
	<head>
		<title>Photo ABCD</title>
		<link rel="stylesheet" type="text/css" href="styles.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	</head>
	<body>
	
		<?php include "inc/header.php"; ?>
		
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