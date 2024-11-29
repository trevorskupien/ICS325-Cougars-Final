<!DOCTYPE html>

<?php
    // Include necessary files
    include 'inc/account.php';
	include_once 'inc/blog_data.php';
    include_once 'inc/book_data.php';


    // Set session cookie
    setcookie("PHPSESSID", session_id(), time() + 86400, "/", "", true, true);

    // Get the current user's account and email
	
	
	$account = isset($_GET["email"]) ? getUser($_GET["email"]) : getSessionAccount();
	$session_account = getSessionAccount();
	
	if(!$session_account){
		header('Location: login.php');
		exit;
	}
	
	if(!$account || !$session_account || ($session_account["role"] != "admin" && $account["email"] != $session_account["email"])){
		header('Location: index.php');
		exit;
	}
	
	$display = isset($_GET["display"]) ? $_GET["display"] : "info";
	
	if($display == "blogs")
		$blogs = getBlogs(null, null, $account["email"]);
	elseif($display == "books")
		$books = getBooks(null, null, $account["email"]);
	else
		$display = "info";
		$stats = getAccountStats($account["email"]);
?>

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
					<h1 class="section-title">Your Profile</h1>
					
					<div class="inline-buttons-right">
						<form class="account-form" action="">
							<?php if($account["email"] != $session_account["email"])
									printf('<input class="hidden" type="text" name="email" value="%s"/>', $account["email"]); ?>
							<input class="hidden" type="text" name="display" value="info"/>
							<input class="hidden" type="submit" id="showinfo"/>
							<label for="showinfo" class="<?php echo $display == 'info' ? 'form-button-selected' : 'form-button'; ?>">Info</label>
						</form>
						<form class="account-form" action="">
							<?php if($account["email"] != $session_account["email"])
									printf('<input class="hidden" type="text" name="email" value="%s"/>', $account["email"]); ?>
							<input class="hidden" type="text" name="display" value="blogs"/>
							<input class="hidden" type="submit" id="showblogs"/>
							<label for="showblogs" class="<?php echo $display == 'blogs' ? 'form-button-selected' : 'form-button'; ?>">Blogs</label>
						</form>
						<form class="account-form" action="">
							<?php if($account["email"] != $session_account["email"])
									printf('<input class="hidden" type="text" name="email" value="%s"/>', $account["email"]); ?>
							<input class="hidden" type="text" name="display" value="books"/>
							<input class="hidden" type="submit" id="showbooks"/>
							<label for="showbooks" class="<?php echo $display == 'books' ? 'form-button-selected' : 'form-button'; ?>">Books</label>
						</form>
					</div>
					
					<div class="form-space"></div>
					
					<div class="inline-buttons">
					<?php
						if($display == "info"){
							if(isset($_GET["confirmdelete"])){
								printf("
								<form class='account-form' action='inc/delete-user.php'>
									<input class='hidden' type='text' name='email' value='%s'/>
									<input class='hidden' type='submit' id='delete'/>
									<label for='delete' class='form-button-red'>Are you sure?</label>
								</form>", $account["email"]);
							}else{
								printf("
								<form class='account-form' action='profile.php'>
									<input class='hidden' type='text' name='confirmdelete' value='1'/>
									<input class='hidden' type='submit' id='delete'/>
									<label for='delete' class='form-button-red'>Delete Account</label>
								</form>");
							}
						}else{
						printf('	
							<form class="account-form" action="inc/update-all.php">
								<input class="hidden" type="text" name="email" value="%s"/>
								<input class="hidden" type="text" name="type" value="%s"/>
								<input class="hidden" type="text" name="privacy" value="public"/>
								<input class="hidden" type="submit" id="public"/>
								<label for="public" class="form-button">All Public</label>
							</form>
							<form class="account-form" action="inc/update-all.php">
								<input class="hidden" type="text" name="email" value="%s"/>
								<input class="hidden" type="text" name="type" value="%s"/>
								<input class="hidden" type="text" name="privacy" value="private"/>
								<input class="hidden" type="submit" id="private"/>
								<label for="private" class="form-button-red">All Private</label>
							</form>', $account["email"], $display, $account["email"], $display);
						}
					?>
					</div>
				</div>

				<p id="form-result"></p>
				
				<div id="blogs-container">
					<?php
						if($display == "info"){
							printf('
								<div class="profile-info">
									<h1 class="profile-header"> %s </h1>
									<p class="profile-sub"> %s </p>
									<hr>
									<br>
									<p class="profile-text"> Account Type: %s</p>
									<p class="profile-text"> Alphabet Coverage: %d / 26</p>
									<p class="profile-text"> Blogs: %d</p>
									<p class="profile-text"> Books: %d</p>
								</div>
							', 	$account["name"],
								$account["email"],
								$account["role"],
								$stats["coverage"],
								$stats["blog_count"],
								$stats["book_count"]);
						}
					?>
					
					<?php
						if($display == "blogs"){
							if (empty($blogs)) {
								echo "<p>No blogs found matching your criteria.</p>";
							} else {
								foreach ($blogs as $blog) {
									$name = getUser($blog["creator_email"])["name"] ?? "Unknown Author";
									$private = !strcmp($blog["privacy_filter"], "private") ? "(private)" : "";

									if ($account && $blog["creator_email"] === $account["email"]) {
										$name = "You";
									}

									// Build blog entry with a link to view details
									printf("
									<div class='blog-container'>
										<a class='no-decoration' href='%s'>
											<div class='blog-thumbnail-container'>
												<img src='images/%s' alt='Blog Image'>
											</div>
											<p class='blog-thumbnail-title'>%s</p>
											<p class='blog-thumbnail-title'>By %s %s</p>
										</a>
									</div>",
									htmlspecialchars("blog.php?id=" . $blog["blog_id"]),
									htmlspecialchars(getBlogImage($blog)),
									htmlspecialchars($blog["title"]),
									htmlspecialchars($name),
									htmlspecialchars($private));
								}
							}
						}
					?>
					
					<?php
						if($display == "books"){
							if (empty($books)) {
								echo "<p>No books found matching your criteria.</p>";
							} else {
								foreach ($books as $book) {
									$name = getUser($book["creator_email"])["name"] ?? "Unknown Author";
									$private = !strcmp($book["privacy_filter"], "private") ? "(private)" : "";

									if ($account && $book["creator_email"] === $account["email"]) {
										$name = "You";
									}

									// Build blog entry with a link to view details
									printf("
									<div class='blog-container'>
										<a class='no-decoration' href='book.php?id=%s'>
											<div class='blog-thumbnail-container'>
												<img src='images/%s' alt='Blog Image'>
											</div>
											<p class='blog-thumbnail-title'>%s</p>
											<p class='blog-thumbnail-title'>By %s %s</p>
										</a>
									</div>",
									htmlspecialchars($book["book_id"]),
									htmlspecialchars(getBookThumbnail($book["book_id"], $account)),
									htmlspecialchars($book["title"]),
									htmlspecialchars($name),
									htmlspecialchars($private));
								}
							}
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
