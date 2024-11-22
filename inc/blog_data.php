<?php
function getBlogs($search = null, $filter = null) {
    include "db.php";

    // Base query
    $query = "SELECT * FROM blogs";
    $params = [];
    $types = "";

    // Add search and filter conditions
    if ($search || $filter) {
        $query .= " WHERE";
        $conditions = [];

        if ($search) {
            $conditions[] = "(title LIKE ? OR description LIKE ?)";
            $searchParam = "%" . $search . "%";
            $params[] = $searchParam;
            $params[] = $searchParam;
            $types .= "ss";
        }

        if ($filter) {
            $conditions[] = "privacy_filter = ?";
            $params[] = $filter;
            $types .= "s";
        }

        $query .= " " . implode(" AND ", $conditions);
    }

    $stmt = mysqli_stmt_init($db);
    mysqli_stmt_prepare($stmt, $query);

    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $blogs = [];

    if (mysqli_num_rows($result) > 0) {
        while ($blog = mysqli_fetch_assoc($result)) {
            $blogs[] = $blog;
        }
    }

    mysqli_close($db);
    return $blogs;
}

function getUserBlogs($fauthor, $search = null, $filter = null, $start_date = null, $end_date = null) {
    include "db.php";

    // Start building the query
    $query = "SELECT * FROM blogs WHERE (privacy_filter = 'public' OR creator_email = ?)";
    $types = "s";
    $params = [$fauthor];

    // Add search condition for titles starting with the given letter
    if ($search) {
        $query .= " AND title LIKE ?";
        $types .= "s";
        $params[] = $search . '%';
    }

    // Add filter condition for privacy
    if ($filter) {
        $query .= " AND privacy_filter = ?";
        $types .= "s";
        $params[] = $filter;
    }

    // Add date range filter
    if ($start_date && $end_date) {
        $query .= " AND creation_date BETWEEN ? AND ?";
        $types .= "ss";
        $params[] = $start_date;
        $params[] = $end_date;
    } elseif ($start_date) {
        $query .= " AND creation_date >= ?";
        $types .= "s";
        $params[] = $start_date;
    } elseif ($end_date) {
        $query .= " AND creation_date <= ?";
        $types .= "s";
        $params[] = $end_date;
    }

    $stmt = mysqli_stmt_init($db);
    mysqli_stmt_prepare($stmt, $query);
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        return null;
    }

    $dbblogs = mysqli_stmt_get_result($stmt);

    $blogs = [];
    if (mysqli_num_rows($dbblogs) > 0) {
        while ($dbblog = mysqli_fetch_assoc($dbblogs)) {
            $blogs[] = $dbblog;
        }
    }

    mysqli_close($db);
    return $blogs;
}



function getBlogById($blog_id) {
	include "db.php";

	$stmt = mysqli_stmt_init($db);
	mysqli_stmt_prepare($stmt, "SELECT * FROM blogs WHERE blog_id = ? LIMIT 1");
	mysqli_stmt_bind_param($stmt, "i", $blog_id);
	mysqli_stmt_execute($stmt);

	$result = mysqli_stmt_get_result($stmt);
	$blog = mysqli_fetch_assoc($result);

	mysqli_close($db);
	return $blog;
}

function getBlogImage($blog){
	if(!isset($blog["image"]) || !strcmp($blog["image"], ""))
		return "default.png";
	else
		return $blog["image"];
}

function getNewBlogID(){
	include "db.php";
	$stmt = mysqli_stmt_init($db);
	mysqli_stmt_prepare($stmt, "SELECT blog_id FROM blogs ORDER BY blog_id DESC LIMIT 1");
	mysqli_stmt_execute($stmt);
	
	$result = mysqli_stmt_get_result($stmt);
	$highest = mysqli_fetch_assoc($result)["blog_id"];
	
	mysqli_close($db);
	
	return $highest + 1;
}

function postBlog($fid, $fdate, $fauthor, $ftitle, $fcontent, $fpublic, $fimage){
	include "db.php";
	$stmt = mysqli_stmt_init($db);
	mysqli_stmt_prepare($stmt, "INSERT INTO blogs (blog_id, image, creator_email, title, description, event_date, privacy_filter)
								VALUES (?, ?, ?, ?, ?, ?, ?)
								ON DUPLICATE KEY UPDATE
								image = VALUES(image),
								title = VALUES(title),
								description = VALUES(description),
								event_date = VALUES(event_date),
								privacy_filter = VALUES(privacy_filter);");
								
	mysqli_stmt_bind_param($stmt, "issssss", $fid, $fimage, $fauthor, $ftitle, $fcontent, $fdate, $fpublic);
	mysqli_stmt_execute($stmt);
	mysqli_close($db);
}

function deleteBlog($fid){
	include "db.php";
	$stmt = mysqli_stmt_init($db);
	mysqli_stmt_prepare($stmt, "DELETE FROM blogs WHERE blog_id=?");
	mysqli_stmt_bind_param($stmt, "i", $fid);
	mysqli_stmt_execute($stmt);
	mysqli_close($db);
}
