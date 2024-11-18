<!DOCTYPE html>

<?php
	include_once 'inc/account.php';
	include_once 'inc/blog_data.php';
	if(!isset($_SESSION["account"])){
		header('Location: login.php');
	}
	
	// Set session cookie
	// Session will expire in 24 hours
	setcookie("PHPSESSID", session_id(), time() + 86400, "/", "", true, true);


	$alphabet = getAlphabetBook(getSessionAccount()["email"]);
	$total_set = 0;
	foreach($alphabet as $entry_id){
		if($entry_id !== -1)
			$total_set = $total_set + 1;
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
					<h1 class="section-title">Alphabet Book</h1>
					<div class="inline-buttons">
						<form class="account-form" action="index">
							<input class="hidden" type="submit" id="return"/>
							<label for="return" class="form-button">Back To Blogs</label>
						</form>
					</div>
				</div>

				<div id="alphabet-container">
					<div class="progress-container">
						<div class="progress-fill" style="width: calc(100% * <?php echo $total_set;?>/26);"></div>
						<span class="progress-report">Progress: <?php echo $total_set;?> / 26</span>
					</div>
					
					<?php
						for($i = 0; $i < 26; $i++){
							if($alphabet[$i] === -1){
								printf("
									<div class='alphabet-entry-empty'>
										<a class='no-decoration' href='index'>
											<span class='alphabet-header'>%s not picked</span>
										</a>
									</div>
								", chr(ord('A') + $i));
							}else{
								$blog = getBlogById($alphabet[$i]);
								
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
									", $alphabet[$i], getBlogImage($blog), $blog["title"][0], substr($blog["title"], 1), $alphabet[$i]);
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
