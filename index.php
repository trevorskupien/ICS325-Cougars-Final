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
			<div id="blogs-container">
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
								$private = "";
								if($account && !strcmp($blog["creator_email"], $account["email"])){
									$name = "You";
								}
								
								if(!strcmp($blog["privacy_filter"], "private"))
									$private = "(private)";
								
								//build blog entry
								printf("
								<div class='blog-container'>
									<a class='no-decoration' href='blog?id=%s'>
										<div class='blog-image-container'>
											<img src='images/%s'></img>
										</div>
										<p class='blog-title'>%s</p>
										<p class='blog-title'>By %s %s</p>
									</a>
								</div>",
								$blog["blog_id"], getBlogImage($blog), $blog["title"], $name, $private);
							}
						?>
			</div>
		</div>
		<div id="footer">
			<p>Fall 2024 ICS 325-50 Team Cougars</p>
		</div>
	</body>
</html>