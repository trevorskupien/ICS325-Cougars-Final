<!DOCTYPE html>
<html>
	<head>
		<title>Photo ABCD</title>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css" />
		<link rel="stylesheet" type="text/css" href="styles.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
		<script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
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
			<h1 class="section-title">Blogs</h1>
			<div id="blogs-table-container">
				<table id="blogs-table">
					<thead>
						<tr>
						<th>Title</th>
						<th>Author</th>
						<th>Topic</th>
						<th>Date Created</th>
						<th>Comments</th>
						<th>Views</th>
						</tr>
					</thead>
					<tbody>
						<?php
							//Build table from mySQL
							$db = mysqli_connect("localhost", "root", "", "photo_cougars_db");
							if(!$db){
								die("Connnection to DB failed: " . mysqli_connect_error());
							}
							
							$blogs = mysqli_query($db, "SELECT * FROM blogs WHERE privacy_filter = 'public'");
							
							if(mysqli_num_rows($blogs) > 0){
								while($blog = mysqli_fetch_assoc($blogs)){
									$id = $blog["blog_id"];
									$title = $blog["title"];
									$topic = $blog["description"];
									$created = $blog["creation_date"];
									$comments = 0; //TODO: temp
									$views = 0; //TODO: temp
									$email = $blog["creator_email"];
									
									//get user's display name from email address
									$user = mysqli_query($db, "SELECT first_name, last_name FROM users WHERE email = '". $email . "' LIMIT 1");
									$user_data = mysqli_fetch_assoc($user);
									$name = $user_data["first_name"] . " " . $user_data["last_name"];
									
									//build table entry
									printf("
									<tr>
										<td><a href='blog?id=%s'>%s</a></td>
										<td>%s</td>
										<td>%s</td>
										<td>%s</td>
										<td>%s</td>
										<td>%s</td>
									</tr>
									", $id, $title, $name, $topic, $created, $comments, $views);
								}
							}
							
							mysqli_close($db); 
						?>
					</tbody>

				</table>
			</div>
			<script>$(document).ready(function() { let table = new DataTable('#blogs-table'); });</script>
		</div>
		<div id="footer">
			<p>Fall 2024 ICS 325-50 Team Cougars</p>
		</div>
	</body>
</html>