<!DOCTYPE html>

<?php
	include_once 'inc/account.php';
	include_once 'inc/book_data.php';
	include_once 'inc/blog_data.php';
	
	if(!isset($_SESSION["account"])){
		header('Location: login.php');
		exit;
	}

	
	if (!isset($_GET['id'])) {
		header('Location: login.php');
		exit;
	}
	
	// Set session cookie
	// Session will expire in 24 hours
	setcookie("PHPSESSID", session_id(), time() + 86400, "/", "", true, true);


	$alphabet = getBookById($_GET['id']);
	$title = $alphabet["title"];
		
	if($_SESSION["account"]["email"] != $alphabet["creator_email"])
	{
		header('Location: login.php');
		exit;
	}
	
	//add new blog
	if(isset($_GET["add-blog"])){
		$blog = getBlogById($_GET["add-blog"]);
		$letter = $blog["title"][0];
		setBookSlot($alphabet["book_id"], $blog["blog_id"], $letter);
		
		//hacky way to refresh from db
		$alphabet = getBookById($_GET['id']);
	}
	
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
					<h1 class="section-title">Edit Alphabet Book</h1>
				
					<div class="inline-buttons">
						<form class="account-form" action="book">
							<input class="hidden" type="submit" id="return"/>
							<input class="hidden" type="text" name="id" value="<?php echo $_GET['id'] ?>"/>
							<label for="return" class="form-button">Back To Book</label>
						</form>
					</div>
				</div>

				<div id="alphabet-container">
									
					<form class="book-title-edit" action="inc/makebook.php" method="post" enctype="multipart/form-data">
						<input class='hidden' type='text' name='id' value='<?php echo $alphabet["book_id"] ?>'/>
						<span class="form-span"> Title: </span>
						<input id="title" class="form-text-stretch" type="text" name="title" placeholder="Book Title" value="<?php echo $title; ?>" required></input>

						<input id="visibility" class="hidden" name="public" type="checkbox" <?php 
							if($alphabet["privacy_filter"] != "private"){
							echo "checked";
							}
						?>></input>

						<label id="visibility-label" for="visibility" class="form-toggle"></label>

						<input class="hidden" type="submit" name="submit" id="post"/>
						<label for="post" class="form-button"> Update </label>
					</form>
					
					<div class="progress-container">
						<div class="progress-fill" style="width: calc(100% * <?php echo $alphabet["total"];?>/26);"></div>
						<span class="progress-report">Progress: <?php echo $alphabet["total"];?> / 26</span>
					</div>
					
					<?php
						for($i = 0; $i < 26; $i++){
							$blog = getBlogById($alphabet["list"][$i]);
							if($blog){
								printf("
									<div class='alphabet-entry'>
										<a class='no-decoration' href='blog?id=%d'>
											<div class='alphabet-image-container'>
												<img class='alphabet-image' src='images/%s'></img>
											</div>
											<span class='alphabet-header'><b>%s</b> %s</span>
											<div class='inline-buttons' style='margin-right:5px'>
												<form class='account-form' action='inc/remove-book-entry.php'>
													<input class='hidden' type='submit' id='delete%d'/>
													<input class='hidden' type='text' name='book_id' value='%d'/>
													<input class='hidden' type='text' name='blog_id' value='%d'/>
													<input class='hidden' type='text' name='return' value='edit-book?id=%d'/>
													<label for='delete%d' class='form-button-red'>Remove</label>
												</form>
											</div>
										</a>
									</div>
								", $blog["blog_id"], getBlogImage($blog), $blog["title"][0], substr($blog["title"], 1), $i, $alphabet["book_id"], $blog["blog_id"], $alphabet["book_id"], $i);
							}else{
								printf("
									<div class='alphabet-entry-empty'>
										<a class='no-decoration' href='index?add-to=%s&search=%s'>
											<span class='alphabet-header'>%s not picked</span>
										</a>
									</div>
								", $alphabet["book_id"],chr(ord('A') + $i), chr(ord('A') + $i));
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
