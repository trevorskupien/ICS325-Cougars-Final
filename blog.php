<!DOCTYPE html>
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
	$author_name = $creator["name"];
	$image = getBlogImage($blog);
?>
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

			
			<div class="content-box">
				<div class="section-header">
					<h1 class="section-title"><?php echo htmlspecialchars($blog["title"])?></h1>
					<!-- Back to My Blogs Link at the Bottom -->
					<div class="inline-buttons">
						<?php
							if(isset($account) && ($account["role"] == "admin" || $account["email"] == $book["creator_email"])){
								if(isset($_GET["confirmdelete"])){
									printf("
									<form class='account-form' action='inc/deletepost.php'>
										<input class='hidden' type='text' name='id' value='%s'/>
										<input class='hidden' type='submit' id='delete'/>
										<label for='delete' class='form-button-red'>Are you sure?</label>
									</form>", $blog_id);
								}else{
									printf("
									<form class='account-form' action='blog.php'>
										<input class='hidden' type='text' name='id' value='%s'/>
										<input class='hidden' type='text' name='confirmdelete' value='1'/>
										<input class='hidden' type='submit' id='delete'/>
										<label for='delete' class='form-button-red'>Delete Blog</label>
									</form>", $blog_id);
								}
								printf("
								<form class='account-form' method='get' action='edit-blog.php'>
									<input class='hidden' type='text' name='id' value='%s'/>
									<input class='hidden' type='submit' id='edit'/>
									<label for='edit' class='form-button'>Edit Blog</label>
								</form>", $blog_id);
							}
						?>

						<form class="account-form" action="index">
							<input class="hidden" type="submit" id="return"/>
							<label for="return" class="form-button">Back To Blogs</label>
						</form>
					</div>
				</div>
				<div id="blog-content">
					<?php
						$creator = getUser($blog["creator_email"]);
						$author_name = $creator["name"];

						printf('
								<div class="blog-slide">
									<div class="blog-image-container"><img src="images/%s"></img></div>
									<h1  class="blog-title">%s</h1>
									<p 	 class="blog-details">by %s %s</p>
									<p 	 class="blog-text">%s</p>
									
								</div>',
								htmlspecialchars($blog["image"] != "" ? $blog["image"] : "default.png"),
								htmlspecialchars($blog["title"]),
								htmlspecialchars($author_name),
								htmlspecialchars($blog["event_date"]),
								nl2br(htmlspecialchars($blog["description"]))
								);
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
