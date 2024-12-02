<?php
    // Include necessary files
    include 'inc/account.php';
    include_once 'inc/book_data.php';

    // Get the current user's account and email
    $account = getSessionAccount();
    $email = $account ? $account["email"] : "";

    // Handle search and filter input
    $search = isset($_GET['search']) ? trim($_GET['search']) : null;
    $filter = isset($_GET['filter']) && in_array($_GET['filter'], ['public', 'private']) ? $_GET['filter'] : null;

    // Fetch books based on the criteria
    $books = getUserBooks($email, $search, $filter);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Printable Alphabet Book Collection</title>
    <link rel="stylesheet" type="text/css" href="styles.css"/>
</head>
<body>
    <div id="global-wrapper">
        <div id="body">
            <div class="content-box">
                <div class="section-header">
                    <h1 class="section-title">Alphabet Book Collection</h1>
                    <div class="inline-buttons">
                        <!-- Print Button -->
                        <button onclick="window.print()" class="form-button">Print Collection</button>
                    </div>
                </div>

                <div id="books-container">
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

                                // Build book entry for printable version
                                printf("
                                <div class='book-container'>
                                    <div class='book-thumbnail-container'>
                                        <img src='images/%s' alt='Book Image' style='width: 100%%;'>
                                    </div>
                                    <p class='book-thumbnail-title'>%s</p>
                                    <p class='book-thumbnail-title'>By %s %s</p>
                                    <p>%s</p>
                                </div>",
                                htmlspecialchars(getBookThumbnail($book["book_id"], $account)),
                                htmlspecialchars($book["title"]),
                                htmlspecialchars($name),
                                htmlspecialchars($private),
                                htmlspecialchars($book["description"]));
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
