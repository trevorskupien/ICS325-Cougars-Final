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
	
	$users = getUsers();
	$blogs = getBlogs(null, null);
	$books = getBooks(null, null);
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
						<button id="users-button" onClick="toggleElement(0);" class="form-button">Users</button>
						<button id="blogs-button" onClick="toggleElement(1);" class="form-button">Blogs</button>
						<button id="books-button" onClick="toggleElement(2);" class="form-button">Books</button>
					</div>
				</div>
	
				<div id="tables-container">
					<table id="users-table">
						<thead>
							<tr>
								<th>Email</th>
								<th>Name</th>
								<th>Role</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach($users as $user){
									printf('

										<tr href="pee">
											<th>%s</th>
											<th>%s</th>
											<th>%s</th>
										</tr>
									', $user["email"], $user["name"], $user["role"]);
								}
							?>
						</tbody>
					</table>
					
					<table id="blogs-table">
						<thead>
							<tr>
								<th>ID</th>
								<th>Creator Email</th>
								<th>Title</th>
								<th>Date</th>
								<th>Privacy</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach($blogs as $blog){
									printf('
										<tr>
											<th>%s</th>
											<th>%s</th>
											<th>%s</th>
											<th>%s</th>
											<th>%s</th>
										</tr>
									', $blog["blog_id"], $blog["creator_email"], $blog["title"], $blog["event_date"], $blog["privacy_filter"]);
								}
							?>
						</tbody>
					</table>
					
					<table id="books-table">
						<thead>
							<tr>
								<th>ID</th>
								<th>Creator Email</th>
								<th>Title</th>
								<th>Completion</th>
								<th>Privacy</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach($books as $book){
									printf('
										<tr>
											<th>%s</th>
											<th>%s</th>
											<th>%s</th>
											<th>%s</th>
											<th>%s</th>
										</tr>
									', $book["book_id"], $book["creator_email"], $book["title"], "0/0", $book["privacy_filter"]);
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div id="footer">
			<p>Fall 2024 ICS 325-50 Team Cougars</p>
		</div>
	</div>
	<script>
		var tabs = ["users-table_wrapper", "blogs-table_wrapper", "books-table_wrapper"];
		var buttons = ["users-button", "blogs-button", "books-button"];
		
		function toggleElement(i){
			for(var j = 0; j < 3; ++j){
				document.getElementById(tabs[j]).hidden = true;
				document.getElementById(buttons[j]).className = "form-button-red";
			}
			
			document.getElementById(tabs[i]).hidden = false;
			document.getElementById(buttons[i]).className = "form-button";
		}
		
		$(document).ready(function() {
			new DataTable('#users-table');
			new DataTable('#blogs-table');
			new DataTable('#books-table');
			toggleElement(0);
		});
	</script>
</body>
</html>
