<!DOCTYPE html>

<?php
    // Include necessary files
    include 'inc/account.php';
    include_once 'inc/blog_data.php';

    // Set session cookie
    setcookie("PHPSESSID", session_id(), time() + 86400, "/", "", true, true);

    // Handle search and filter inputs
    $search = isset($_GET['search']) ? trim($_GET['search']) : null;
    $filter = isset($_GET['filter']) && in_array($_GET['filter'], ['public', 'private']) ? $_GET['filter'] : null;
    $start_date = isset($_GET['start_date']) ? trim($_GET['start_date']) : null;
    $end_date = isset($_GET['end_date']) ? trim($_GET['end_date']) : null;

    // Get the current user's account and email
    $account = getSessionAccount();
    $email = $account ? $account["email"] : "";

    // Fetch blogs based on search, filter, and date range criteria
	$sort = "";
	if(isset($_GET["sort"]) && $_GET["sort"] == 'date')
		$sort = "date";
	
    $blogs = getUserBlogs($email, $search, $filter, $start_date, $end_date, $sort);
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
					<h1 class="section-title">Blogs</h1>
					<div class="inline-buttons-right">
						<form method="GET" action="">
							<?php
							if(isset($_GET["add-to"]))
								printf('<input class="hidden" name="add-to" value="%s"></input>', $_GET["add-to"]);
							?>
							<label class="form-text" for="search">Title:</label>
							<input class="form-text-inline" type="text" name="search" id="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">

							<label class="form-text" for="filter">Privacy:</label>
							<select class="form-select" .form-selectname="filter" id="filter">
								<option value="">All</option>
								<option value="public" <?= (isset($_GET['filter']) && $_GET['filter'] === 'public') ? 'selected' : '' ?>>Public</option>
								<option value="private" <?= (isset($_GET['filter']) && $_GET['filter'] === 'private') ? 'selected' : '' ?>>Private</option>
							</select>

							<label class="form-text" for="start_date">Start Date:</label>
							<input class="form-calendar" type="date" name="start_date" id="start_date" value="<?= htmlspecialchars($_GET['start_date'] ?? '') ?>">

							<label class="form-text" for="end_date">End Date:</label>
							<input class="form-calendar" type="date" name="end_date" id="end_date" value="<?= htmlspecialchars($_GET['end_date'] ?? '') ?>">

							<button id="filterbutton" class="hidden" type="submit"></button>
							<label for="filterbutton" class="form-button">Filter</label>
						</form>
						
						<form method="GET" action="">
							<?php
								if(isset($_GET["add-to"]))
									printf('<input class="hidden" name="add-to" value="%s"></input>', $_GET["add-to"]);
							?>
							<input class="hidden" type="text" name="sort" value="<?php
								if(isset($_GET["sort"]) && $_GET["sort"] == "date")
									echo "alpha";
								else
									echo "date";?>"></input>
							<button id="sort" class="hidden" type="submit"></button>
							<label for="sort" class="form-button"><?php
								if(isset($_GET["sort"]) && $_GET["sort"] == "date")
									echo "Sort Alphabet";
								else
									echo "Sort Date";
							?></label>
						</form>
					</div>
					
					<div class="inline-buttons">
						<!-- Search and Filter Form -->
						<?php
							if(isset($_GET["add-to"])){
								printf('
								<form action="edit-book">
									<input class="hidden" type="submit" id="back"/>
									<input class="hidden" type="text" name="id" value="%d"/>
									<label for="back" class="form-button">Back to Book</label>
								</form>', $_GET["add-to"]);
							}else{
								echo '<a href="print-collection.php" class="no-decoration form-button">Print</a>';
								echo '
								<form action="edit-blog">
									<input class="hidden" type="submit" id="newblog"/>
									<label for="newblog" class="form-button">New Blog</label>
								</form>';
							}
						
						?>
					</div>
				</div>

				<p id="form-result">
					<?php
						if (isset($_GET["result"])) {
							switch ($_GET["result"]) {
								case 'delete':
									echo "Blog post deleted.";
									break;
							}
						}
					?>
				</p>	
				<div id="blogs-container">
					<?php
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
								htmlspecialchars(isset($_GET["add-to"]) ? "edit-book.php?id=" . $_GET["add-to"] . "&add-blog=" . $blog["blog_id"] : "blog.php?id=" . $blog["blog_id"]),
								htmlspecialchars(getBlogImage($blog)),
								htmlspecialchars($blog["title"]),
								htmlspecialchars($name),
								htmlspecialchars($private));
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
