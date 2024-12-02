<?php
    // Include necessary files
    include 'inc/account.php';
    include_once 'inc/blog_data.php';

    // Get the current user's account and email
    $account = getSessionAccount();
    $email = $account ? $account["email"] : "";

    // Fetch blogs based on the criteria (you may modify the query as needed)
    $blogs = getUserBlogs($email, null, null, null, null);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Printable Alphabet Book / Blog Collection</title>
    <link rel="stylesheet" type="text/css" href="styles.css"/>
    <style>
        /* Print-specific CSS */
        @media print {
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                margin: 0;
                padding: 0;
            }
            .content-box {
                width: 100%;
                margin: 0;
            }
            .section-header, .inline-buttons, #footer {
                display: none;
            }
            #blogs-container {
                width: 100%;
                page-break-before: always;
            }
            .blog-container {
                margin-bottom: 20px;
                page-break-inside: avoid;
                border-bottom: 1px solid #ccc;
                padding-bottom: 10px;
            }
            .blog-thumbnail-container {
                width: 100%;
                height: auto;
                text-align: center;
            }
            .blog-thumbnail-title {
                font-size: 16px;
                font-weight: bold;
                margin: 10px 0;
            }
            .blog-thumbnail-title, .blog-thumbnail-title p {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div id="global-wrapper">
        <div id="body">
            <div class="content-box">
                <div class="section-header">
                    <h1 class="section-title">Alphabet Book / Blog Collection</h1>
                    <div class="inline-buttons">
                        <!-- Print Button -->
                        <button onclick="window.print()" class="form-button">Print Collection</button>
                    </div>
                </div>

                <div id="blogs-container">
                    <?php
                        if (empty($blogs)) {
                            echo "<p>No blogs found.</p>";
                        } else {
                            foreach ($blogs as $blog) {
                                $name = getUser($blog["creator_email"])["name"] ?? "Unknown Author";
                                $private = !strcmp($blog["privacy_filter"], "private") ? "(private)" : "";

                                if ($account && $blog["creator_email"] === $account["email"]) {
                                    $name = "You";
                                }

                                // Build blog entry for printable version
                                printf("
                                <div class='blog-container'>
                                    <div class='blog-thumbnail-container'>
                                        <img src='images/%s' alt='Blog Image' style='width: 100%%;'>
                                    </div>
                                    <p class='blog-thumbnail-title'>%s</p>
                                    <p class='blog-thumbnail-title'>By %s %s</p>
                                    <p>%s</p>
                                </div>",
                                htmlspecialchars(getBlogImage($blog)),
                                htmlspecialchars($blog["title"]),
                                htmlspecialchars($name),
                                htmlspecialchars($private),
                                htmlspecialchars($blog["description"]));
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
