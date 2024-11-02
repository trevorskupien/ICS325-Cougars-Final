<!DOCTYPE html>
<html>
<head>
	<title>Photo ABCD - Blog Details</title>
	<link rel="stylesheet" type="text/css" href="styles.css"/>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
	<div id="global-wrapper">
		<?php include "inc/header.php"; ?>
		<div id="body">
			<?php
				include_once "inc/blog_data.php";
				include_once "inc/account.php";
				// Check if blog ID is set in the URL
				if (!isset($_GET['id'])) {
					echo "<p>Blog not found.</p>";
					exit;
				}
				$blog_id = $_GET['id'];
				$account = getSessionAccount();
				$blog = getBlogById($blog_id);
				if (!$blog || ($blog["privacy_filter"] === "private" && (!$account || $account["email"] !== $blog["creator_email"]))) {
					echo "<p>Blog not available or access restricted.</p>";
					exit;
				}
				$creator = getUser($blog["creator_email"]);
				$author_name = $creator ? $creator["name"] : "Unknown Author";
				$image = getBlogImage($blog);
			?>
			
			<div class="content-box">
				<div class="section-header">
					<h1 class="section-title"><?php echo htmlspecialchars($blog["title"])?></h1>
					<!-- Back to My Blogs Link at the Bottom -->
					<form class="account-form" action="index">
						<input class="hidden" type="submit" id="return"/>
						<label for="return" class="form-button">Back To Blogs</label>
					</form>
				</div>
				
				<div id="blog-content">
					<?php
						echo "<img class='blog-image-large' src='images/" . $image . "'></img>";
						echo "<p>By " . htmlspecialchars($author_name) . "</p>";
						echo "<p>Date: " . htmlspecialchars($blog["event_date"]) . "</p>";
						echo "<p>" . nl2br(htmlspecialchars($blog["description"])) . "</p>";
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
