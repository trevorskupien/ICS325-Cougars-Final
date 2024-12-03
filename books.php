<!DOCTYPE html>

<?php
    // Include necessary files
    include 'inc/account.php';
    include_once 'inc/blog_data.php';
    include_once 'inc/book_data.php';

    // Set session cookie
    setcookie("PHPSESSID", session_id(), time() + 86400, "/", "", true, true);

    // Handle search and filter inputs
    $search = isset($_GET['search']) ? trim($_GET['search']) : null;
    $filter = isset($_GET['filter']) && in_array($_GET['filter'], ['public', 'private']) ? $_GET['filter'] : null;

    // Get the current user's account and email
    $account = getSessionAccount();
    $email = $account ? $account["email"] : "";

    // Fetch books based on search, filter, and date range criteria
    $books = getUserBooks($email, $search, $filter);
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
					<h1 class="section-title">Alphabet Books</h1>
					<div class="inline-buttons-right">
						<form method="GET" action="">
							<label class="form-text" for="search">Title:</label>
							<input class="form-text-inline" type="text" name="search" id="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">

							<label class="form-text" for="filter">Privacy:</label>
							<select class="form-select" name="filter" id="filter">
								<option value="">All</option>
								<option value="public" <?= (isset($_GET['filter']) && $_GET['filter'] === 'public') ? 'selected' : '' ?>>Public</option>
								<option value="private" <?= (isset($_GET['filter']) && $_GET['filter'] === 'private') ? 'selected' : '' ?>>Private</option>
							</select>

							<button id="filterbutton" class="hidden" type="submit"></button>
							<label for="filterbutton" class="form-button">Filter</label>
						</form>
					</div>
					
					<div class="inline-buttons">
						<!-- Export and Create Book Buttons -->
						<div class="form-stretch"></div>

						<!-- Export link with parameters for search and filter -->
						<a href="export.php?search=<?= urlencode($search) ?>&filter=<?= urlencode($filter) ?>" class="form-button no-decoration">Print Alphabet Books</a>

						<!-- New Alphabet Book Button -->
						<form action="create-book">
							<input class="hidden" type="submit" id="newbook"/>
							<label for="newbook" class="form-button">New Alphabet Book</label>
						</form>
					</div>
				</div>

				<p id="form-result">
					<?php
						if (isset($_GET["result"])) {
							switch ($_GET["result"]) {
								case 'delete':
									echo "Alphabet book deleted.";
									break;
							}
						}
					?>
				</p>	
				<div id="blogs-container">
					<?php
						if (empty($books)) {
							echo "<p>No books found matching your criteria.</p>";
						} else {
							foreach ($books as $book) {
								$name = getUser($book["creator_email"])["name"] ?? "Unknown Author";
								$private = !strcmp($book["privacy_filter"], "private") ? "(private)" : "";

								if ($account && $book["creator_email"] === $account["email"]) {
									$name = "You";
								}

								// Build book entry with a link to view details
								printf("
								<div class='blog-container'>
									<a class='no-decoration' href='book.php?id=%s'>
										<div class='blog-thumbnail-container'>
											<img src='images/%s' alt='Book Image'>
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
