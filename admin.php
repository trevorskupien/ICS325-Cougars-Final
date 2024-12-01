<!DOCTYPE html>

<?php
    // Include necessary files
    include 'inc/account.php';
    include_once 'inc/blog_data.php';
    include_once 'inc/book_data.php';
	
	if(!isset($_SESSION["account"]) || $_SESSION["account"]["role"] != "admin"){
		//reject non admins
		header('Location: login.php');
		exit;
	}
	
	
    // Set session cookie
    setcookie("PHPSESSID", session_id(), time() + 86400, "/", "", true, true);

    // Get the current user's account and email
    $account = getSessionAccount();
    $email = $account ? $account["email"] : "";
	
	$display = isset($_GET["display"]) ? $_GET["display"] : "users";
	$for = isset($_GET["for"]) ? $_GET["for"] : null;
	
	if($display == "users"){
		$users = getUsers();
	}
	
	if($display == "blogs"){
		$blogs = getBlogs(null, null, $for);
		
		//please stop
		$coverage_arr = array_fill(0, 26, false);
		$coverage = 0;
		foreach($blogs as $blog){
			$title = $blog["title"];
			$coverage_arr[ord($title[0]) - ord('A')] = true;
		}
		for($i = 0; $i < 26; $i++){
			if($coverage_arr[$i])
				$coverage++;
		}
	}
	
	if($display == "books"){
		$books = getBooks(null, null, $for);
	}
	
?>

<html>
<head>
	<title>Photo ABCD</title>
	<link rel="stylesheet" type="text/css" href="styles.css"/>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
		<script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
</head>
<body>
	<div id="global-wrapper">
		<?php include "inc/header.php"; ?>
		<div id="body">
			<div class="content-box">
			
				<div class="section-header">
					<h1 class="section-title">Admin</h1>
					<div class="inline-buttons">
						<form class='account-form' action='admin.php'>
							<input class='hidden' type='text' name='display' value='users'/>
							<input class='hidden' type='submit' id='allusers'/>
							<label for='allusers' class='form-button'>All Users</label>
						</form>
						<form class='account-form' action='admin.php'>
							<input class='hidden' type='text' name='display' value='blogs'/>
							<input class='hidden' type='submit' id='allblogs'/>
							<label for='allblogs' class='form-button'>All Blogs</label>
						</form>
						<form class='account-form' action='admin.php'>
							<input class='hidden' type='text' name='display' value='books'/>
							<input class='hidden' type='submit' id='allbooks'/>
							<label for='allbooks' class='form-button'>All Books</label>
						</form>
					</div>
				</div>
	
				<div id="admin-container">
					<?php
						if($display == "users"){
							echo '<div id="user-table" class="admin-table">';
									for($i = 0; $i < count($users); $i++){
										$user = $users[$i];
										$email = $user["email"];
										$name = $user["name"];
										printf("
										<div class='admin-element-container'>
											<p><b>%s</b>; %s</p>
											<div class='inline-buttons'>
												<form class='account-form' action='profile.php'>
													<input class='hidden' type='text' name='email' value='%s'/>
													<input class='hidden' type='submit' id='user%dprofile'/>
													<label for='user%dprofile' class='form-button'>Profile</label>
												</form>
												<form class='account-form' action='admin.php'>
													<input class='hidden' type='text' name='display' value='blogs'/>
													<input class='hidden' type='text' name='for' value='%s'/>
													<input class='hidden' type='submit' id='user%dblogs'/>
													<label for='user%dblogs' class='form-button'>Blogs</label>
												</form>
												<form class='account-form' action='admin.php'>
													<input class='hidden' type='text' name='display' value='books'/>
													<input class='hidden' type='text' name='for' value='%s'/>
													<input class='hidden' type='submit' id='user%dbooks'/>
													<label for='user%dbooks' class='form-button'>Books</label>
												</form>
												<form class='account-form' action='inc/delete-user.php'>
													<input class='hidden' type='text' name='email' value='%s'/>
													<input class='hidden' type='text' name='return' value='admin.php?display=users'/>
													<input class='hidden' type='submit' id='user%ddelete'/>
													<label for='user%ddelete' class='form-button-red'>Delete</label>
												</form>
											</div>
										</div>", $email, $name, $email, $i, $i, $email, $i, $i, $email, $i, $i, $email, $i, $i);
									}
							echo '</div>';
						}
					?>
					
					<?php
						if($display == "blogs"){
							printf("<p>Alphabet Coverage: %d/26</p>", $coverage);
							echo '<div id="blog-table" class="admin-table">';
									for($i = 0; $i < count($blogs); $i++){
										$blog = $blogs[$i];
										$email = $blog["creator_email"];
										$id = $blog["blog_id"];
										$image = $blog["image"];
										$title = $blog["title"];

										printf("
										<div class='admin-element-container'>
											<div class='admin-thumbnail-container'>
												<img src='images/%s'></img>
											</div>
											<p>[%d] %s, %s</p>
											<div class='inline-buttons'>
												<form class='account-form' action='blog.php'>
													<input class='hidden' type='text' name='id' value='%d'/>
													<input class='hidden' type='submit' id='blog%dview'/>
													<label for='blog%dview' class='form-button'>View</label>
												</form>
												<form class='account-form' action='inc/deletepost.php'>
													<input class='hidden' type='text' name='id' value='%d'/>
													<input class='hidden' type='text' name='return' value='admin.php?display=blogs'/>
													<input class='hidden' type='submit' id='blog%ddelete'/>
													<label for='blog%ddelete' class='form-button-red'>Delete</label>
												</form>
											</div>
										</div>", $image == "" ? "default.png" : $image, $id, $title, $email, $id, $i, $i, $id, $i, $i);
									}
							echo '</div>';
						}
					?>
					
					<?php
						if($display == "books"){
							echo '<div id="book-table" class="admin-table">';
									for($i = 0; $i < count($books); $i++){
										$book = $books[$i];
										$email = $book["creator_email"];
										$id = $book["book_id"];
										$title = $book["title"];
										$total = $book["total"];

										printf("
										<div class='admin-element-container'>
											<p>[%d] [%s / 26] %s, %s</p>
											<div class='inline-buttons'>
												<form class='account-form' action='book.php'>
													<input class='hidden' type='text' name='id' value='%d'/>
													<input class='hidden' type='submit' id='book%dview'/>
													<label for='book%dview' class='form-button'>View</label>
												</form>
												<form class='account-form' action='inc/delete-book.php'>
													<input class='hidden' type='text' name='id' value='%d'/>
													<input class='hidden' type='text' name='return' value='admin.php?display=books'/>
													<input class='hidden' type='submit' id='book%ddelete'/>
													<label for='book%ddelete' class='form-button-red'>Delete</label>
												</form>
											</div>
										</div>", $id, $total, $title, $email, $id, $i, $i, $id, $i, $i);
									}
							echo '</div>';
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
