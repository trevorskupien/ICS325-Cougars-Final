<!DOCTYPE html>
<html>
<head>
	<title>Photo ABCD - Blog Details</title>
	<link rel="stylesheet" type="text/css" href="styles.css"/>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>

	<?php include "inc/header.php"; ?>
	
	<div class="content-box">
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

			// Fetch the blog details
			$blog = getBlogById($blog_id);

			if (!$blog || ($blog["privacy_filter"] === "private" && (!$account || $account["email"] !== $blog["creator_email"]))) {
				echo "<p>Blog not available or access restricted.</p>";
			} else {
				// Display blog details
				$creator = getUser($blog["creator_email"]);
				$author_name = $creator ? $creator["name"] : "Unknown Author";

				echo "<h1 class='blog-title'>" . htmlspecialchars($blog["title"]) . "</h1>";
				echo "<p>By " . htmlspecialchars($author_name) . "</p>";
				echo "<p>Date: " . htmlspecialchars($blog["event_date"]) . "</p>";
				echo "<p>" . nl2br(htmlspecialchars($blog["description"])) . "</p>";
			}
		?>
	</div>

	<!-- Back to My Blogs Link at the Bottom -->
	<div class="back-link-container">
		<a href="index.php" class="back-link">Back to My Blogs</a>
	</div>

	<div id="footer">
		<p>Fall 2024 ICS 325-50 Team Cougars</p>
	</div>
</body>
</html>
