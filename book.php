<!DOCTYPE html>
<?php
	include_once "inc/book_data.php";
	include_once "inc/blog_data.php";
	include_once "inc/account.php";
	// Check if blog ID is set in the URL
	if (!isset($_GET['id'])) {
		echo "<p>Book not found.</p>";
		exit;
	}
	$book_id = $_GET['id'];
	$account = getSessionAccount();
	$book = getBookById($book_id);
	if (!$book || ($book["privacy_filter"] === "private" && (!$account || $account["email"] !== $book["creator_email"]))) {
		echo "<p>Book not available or access restricted.</p>";
		exit;
	}
	$creator = getUser($book["creator_email"]);
	$author_name = $creator["name"];
	
	$blogs = [];
	for($i = 0; $i < 26; $i++){
		$blog = getBlogById($book["list"][$i]);
		
		if($blog){
			
			//skip private blogs added to public alphabet book
			if($blog["privacy_filter"] == "private" && (!$account || $blog["creator_email"] != $account["email"])){
				$book["total"]--;
				continue;
			}
			$blogs[] = $blog;
		}
			
	}
?>
<html>
<head>
	<title>Photo ABCD - Book Details</title>
	<link rel="stylesheet" type="text/css" href="styles.css"/>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
	<div id="global-wrapper">
		<?php include "inc/header.php"; ?>
		<div id="body">

			
			<div class="content-box">
				<div class="section-header">
					<h1 class="section-title"><?php echo htmlspecialchars($book["title"])?></h1>
					<!-- Back to My Blogs Link at the Bottom -->
					<div class="inline-buttons">
						<?php
							if(isset($account) && ($account["role"] == "admin" || $account["email"] == $book["creator_email"])){
								if(isset($_GET["confirmdelete"])){
									printf("
									<form class='account-form' action='inc/delete-book.php'>
										<input class='hidden' type='text' name='id' value='%s'/>
										<input class='hidden' type='submit' id='delete'/>
										<label for='delete' class='form-button-red'>Are you sure?</label>
									</form>", $book_id);
								}else{
									printf("
									<form class='account-form' action='book.php'>
										<input class='hidden' type='text' name='id' value='%s'/>
										<input class='hidden' type='text' name='confirmdelete' value='1'/>
										<input class='hidden' type='submit' id='delete'/>
										<label for='delete' class='form-button-red'>Delete Book</label>
									</form>", $book_id);
								}
								printf("
								<form class='account-form' method='get' action='edit-book.php'>
									<input class='hidden' type='text' name='id' value='%s'/>
									<input class='hidden' type='submit' id='edit'/>
									<label for='edit' class='form-button'>Edit Book</label>
								</form>", $book_id);
							}
						?>

						<form class="account-form" action="books">
							<input class="hidden" type="submit" id="return"/>
							<label for="return" class="form-button">Back To Books</label>
						</form>
					</div>
				</div>
				<div id="book-content">
					<?php
						if (empty($blogs)) {
							echo "<p>This Alphabet Book is empty!</p>";
						}else{
							$total = $book["total"];
							
							for($i = 0; $i < $book["total"]; $i++){
								$blog = $blogs[$i];
								$creator = getUser($blog["creator_email"]);
								$author_name = $creator["name"];
								
								//skip private blogs added to public alphabet book
								if($blog["privacy_filter"] == "private" && (!$account || $blog["creator_email"] != $account["email"])){
									continue;
								}
								
								printf('
										<div class="blog-slide fade">
											<div class="blog-image-container"><img src="images/%s"></img></div>
											
											<div class="blog-controls">
												<button onclick="incSlides(-1);" class="form-button-inline" style="width: 90px">Previous</button>
												<div class="blog-label">%d / %d</div>
												<button onclick="incSlides(1);"class="form-button-inline" style="width: 90px">Next</button>
											</div>
											
											<h1  class="blog-title">%s</h1>
											<p 	 class="blog-details">by %s %s</p>
											<p 	 class="blog-text">%s</p>
											
										</div>',
										htmlspecialchars($blog["image"] != "" ? $blog["image"] : "default.png"),
										$i + 1,
										htmlspecialchars($total),
										htmlspecialchars($blog["title"]),
										htmlspecialchars($author_name),
										htmlspecialchars($blog["event_date"]),
										nl2br(htmlspecialchars($blog["description"]))
										);
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
	<script src="inc/slideshow.js"></script>
</body>
</html>
