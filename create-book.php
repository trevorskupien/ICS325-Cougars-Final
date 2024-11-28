<!DOCTYPE html>
<?php
	include_once "inc/account.php";
	if(!isset($_SESSION["account"])){
		header('Location: login.php');
	}
	
	$blog_id = "";
	$account = "";
	$blog = "";
	$creator = "";
	$editing = false;
	
	if(isset($_GET["id"])){
		//editing existing post
		include_once "inc/blog_data.php";
		
		if(!isset($_SESSION["account"])){
			header('Location: login.php');
			exit;
		}

		if (!isset($_GET['id'])) {
			header('Location: index.php');
			exit;
		}
		
		$blog_id = $_GET['id'];
		$account = getSessionAccount();
		$blog = getBlogById($blog_id);

		if (!$blog || !$account || $account["email"] !== $blog["creator_email"]) {
			header('Location: index.php');
			exit;
		}
		
		$editing = true;
		$creator = getUser($blog["creator_email"]);
		$letter = $blog["title"][0];
		$title = substr($blog["title"], 6);
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
				<div class="form-content-box" id="content-box">
					<div class="section-header"><h1 class="section-title">Create Alphabet Book</h1></div>
					<p id="form-result">
						<?php
							if(isset($_GET["error"])){
								switch($_GET["error"]){
								}
							}
						?>
					</p>
					
					<form class="blogpost" action="inc/makebook.php" method="post" enctype="multipart/form-data">
						<?php 
						if($editing){
							echo "<input class='hidden' type='text' name='id' value='". $book_id . "'/>";
						}
						?>
						<div class="post-header">
							<input id="title" class="form-text" type="text" name="title" placeholder="Book Title" value="<?php
								//fill default if set
								if(isset($_SESSION["title"])){
									echo htmlspecialchars($_SESSION["title"]);
									unset($_SESSION["title"]);
								}elseif($editing){
									echo $title;
								}
							?>" required></input>

							<input id="visibility" class="hidden" name="public" type="checkbox" <?php 
								if($editing && $blog["privacy_filter"] != "private"){
									echo "checked";
								}
							?>></input>
							
							<label id="visibility-label" for="visibility" class="form-toggle"></label>
							
							<input class="hidden" type="submit" name="submit" id="post"/>
							<label for="post" class="form-button"><?php echo $editing ? "Update" : "Create"?></label>
						</div>
						<div class="form-break"></div>
					</form>
				</div>
			</div>
			<div id="footer">
				<p>Fall 2024 ICS 325-50 Team Cougars</p>
			</div>
		</div>
	</body>
</html>