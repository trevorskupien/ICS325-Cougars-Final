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
						<form class="account-form" action="index">
							<input class="hidden" type="submit" id="return"/>
							<label for="return" class="form-button">Back To Blogs</label>
						</form>
					</div>
				</div>

				<div id="alphabet-container">
									
					<div class="book-title-edit">
						<input id="title" class="form-text-stretch" type="text" name="title" placeholder="Book Title" value="<?php echo $title; ?>" required></input>

						<input id="visibility" class="hidden" name="public" type="checkbox" <?php 
							if($alphabet["privacy_filter"] != "private"){
							echo "checked";
							}
						?>></input>

						<label id="visibility-label" for="visibility" class="form-toggle"></label>

						<input class="hidden" type="submit" name="submit" id="post"/>
						<label for="post" class="form-button"> Update </label>
					</div>
					
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
												<form class='account-form' action='inc/delAB.php'>
													<input class='hidden' type='submit' id='delete'/>
													<input class='hidden' type='text' name='id' value='%d'/>
													<input class='hidden' type='text' name='return' value='alphabet'/>
													<label for='delete' class='form-button-red'>Remove</label>
												</form>
											</div>
										</a>
									</div>
								", $blog["blog_id"], getBlogImage($blog), $blog["title"][0], substr($blog["title"], 1), $blog["blog_id"]);
							}else{
								printf("
									<div class='alphabet-entry-empty'>
										<a class='no-decoration' href='index'>
											<span class='alphabet-header'>%s not picked</span>
										</a>
									</div>
								", chr(ord('A') + $i));
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
