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
    $blogs = getUserBlogs($email, $search, $filter, $start_date, $end_date);
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
					<div class="inline-buttons">
						<form action="alphabet">
							<input class="hidden" type="submit" id="alphabet"/>
							<label for="alphabet" class="form-button">Alphabet Book</label>
						</form>
						<form action="post">
							<input class="hidden" type="submit" id="newblog"/>
							<label for="newblog" class="form-button">New Blog</label>
						</form>
					</div>
				</div>

				<!-- Search and Filter Form -->
				<div class="filter-section">
					<form method="GET" action="">
						<label for="search">Search by Letter:</label>
						<input type="text" name="search" id="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">

						<label for="filter">Privacy Filter:</label>
						<select name="filter" id="filter">
							<option value="">All</option>
							<option value="public" <?= (isset($_GET['filter']) && $_GET['filter'] === 'public') ? 'selected' : '' ?>>Public</option>
							<option value="private" <?= (isset($_GET['filter']) && $_GET['filter'] === 'private') ? 'selected' : '' ?>>Private</option>
						</select>

						<label for="start_date">Start Date:</label>
						<input type="date" name="start_date" id="start_date" value="<?= htmlspecialchars($_GET['start_date'] ?? '') ?>">

						<label for="end_date">End Date:</label>
						<input type="date" name="end_date" id="end_date" value="<?= htmlspecialchars($_GET['end_date'] ?? '') ?>">

						<button type="submit">Filter</button>
					</form>
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
									<a class='no-decoration' href='blog.php?id=%s'>
										<div class='blog-image-container'>
											<img src='images/%s' alt='Blog Image'>
										</div>
										<p class='blog-title'>%s</p>
										<p class='blog-title'>By %s %s</p>
									</a>
								</div>",
								htmlspecialchars($blog["blog_id"]),
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
