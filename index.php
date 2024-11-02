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
			<div class="content-box">
			
				<div class="section-header">
					<h1 class="section-title">Blogs</h1>
					<form action="post">
						<input class="hidden" type="submit" id="newblog"/>
						<label for="newblog" class="form-button">New Blog</label>
					</form>
				</div>
				
				<div id="blogs-container">
					<?php
						include_once "inc/blog_data.php";
						include_once "inc/account.php";

						$account = getSessionAccount();
						$email = $account ? $account["email"] : "";

						$blogs = getUserBlogs($email);

						foreach ($blogs as $blog) {
							$name = getUser($blog["creator_email"])["name"] ?? "Unknown Author";
							$private = !strcmp($blog["privacy_filter"], "private") ? "(private)" : "";

							if ($account && $blog["creator_email"] === $account["email"]) {
								$name = "You";
							}

							// Build blog entry with a link to view details
							printf("
							<div class='blog-container'>
								<a class='no-decoration' href='blog.php?id=%s'>
									<div class='blog-image-container'>
										<img src='images/%s' alt='Blog Image'>
									</div>
									<p class='blog-title'>%s</p>
									<p class='blog-title'>By %s %s</p>
								</a>
							</div>",
							htmlspecialchars($blog["blog_id"]),
							htmlspecialchars(getBlogImage($blog)),
							htmlspecialchars($blog["title"]),
							htmlspecialchars($name),
							htmlspecialchars($private));
						}
					?>
				</div>
			</div>
		</div>
		<div id="footer">
			<p>Fall 2024 ICS 325-50 Team Cougars</p>
		</div>
	</div>
</body>
</html>
