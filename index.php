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
			<h1 class="section-title">Blogs</h1>
			<div id="blogs-table-container">
				<table id="blogs-table">
					<thead>
						<tr>
						<th>Title</th>
						<th>Author</th>
						<th>Topic</th>
						<th>Date Created</th>
						</tr>
					</thead>
					<tbody>
						<?php
							include_once "inc/blog_data.php";
							include_once "inc/account.php";
							
							$account = getSessionAccount();
							$email = "";
							if($account)
								$email = $account["email"];
							
							$blogs = getUserBlogs($email);
							
							foreach($blogs as $blog){
								$name = getUser($blog["creator_email"])["name"];

								if($account && !strcmp($blog["creator_email"], $account["email"])){
									$name = "<b>" . $name . "</b>";
								}
								
								//build table entry
								printf("
								<tr>
									<td><a href='blog?id=%s'>%s</a></td>
									<td>%s</td>
									<td>%s</td>
									<td>%s</td>
								</tr>
								", $blog["blog_id"], $blog["title"], $name, $blog["description"], $blog["creation_date"]);
							}
						?>
					</tbody>

				</table>
			</div>
		</div>
		<div id="footer">
			<p>Fall 2024 ICS 325-50 Team Cougars</p>
		</div>
	</body>
</html>