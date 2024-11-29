<?php

function getBooks($search = null, $filter = null, $user = null) {
    include "db.php";

    // Base query
    $query = "SELECT * FROM books";
    $params = [];
    $types = "";

    // Add search and filter conditions
    if ($search || $filter || $user) {
        $query .= " WHERE";
        $conditions = [];

        if ($search) {
            $conditions[] = "(title LIKE ?)";
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
		
		if($user){
			$conditions[] = "creator_email = ?";
			$params[] = $user;
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
    $books = [];

    if (mysqli_num_rows($result) > 0) {
        while ($book = mysqli_fetch_assoc($result)) {
			$book_arr = [];
			$total = 0;
			for($i = ord('A'); $i <= ord('Z'); $i++){
				$letter = chr($i);
				if(isset($book[$letter])){
					//check for deleted blogs and unset them
					$blog = getBlogById($book[$letter]);
					if(!$blog)
					clearBookSlot($book_id, $letter);

					$book_arr[] = $book[$letter];
					$total++;
				}else{
					$book_arr[] = NULL;
				}
			}
			
			$book["list"] = $book_arr;
			$book["total"] = $total;
            $books[] = $book;
        }
    }

    mysqli_close($db);
    return $books;
}

function getUserBooks($fauthor, $search = null, $filter = null) {
    include "db.php";

    // Start building the query
    $query = "SELECT * FROM books WHERE (privacy_filter = 'public' OR creator_email = ?)";
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

    $stmt = mysqli_stmt_init($db);
    mysqli_stmt_prepare($stmt, $query);
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        return null;
    }

    $dbbooks = mysqli_stmt_get_result($stmt);

    $books = [];
    if (mysqli_num_rows($dbbooks) > 0) {
        while ($dbbook = mysqli_fetch_assoc($dbbooks)) {
            $books[] = $dbbook;
        }
    }

    mysqli_close($db);
    return $books;
}

function getBookById($book_id) {
	include "db.php";
	include_once "blog_data.php";
	
	$stmt = mysqli_stmt_init($db);
	mysqli_stmt_prepare($stmt, "SELECT * FROM books WHERE book_id = ? LIMIT 1");
	mysqli_stmt_bind_param($stmt, "i", $book_id);
	mysqli_stmt_execute($stmt);

	$result = mysqli_stmt_get_result($stmt);
	$book = mysqli_fetch_assoc($result);

	mysqli_close($db);
	
	//create ordinal array
	$book_arr = [];
	$total = 0;
	for($i = ord('A'); $i <= ord('Z'); $i++){
		$letter = chr($i);
		if(isset($book[$letter])){
			//check for deleted blogs and unset them
			$blog = getBlogById($book[$letter]);
			if(!$blog)
				clearBookSlot($book_id, $letter);
			
			$book_arr[] = $book[$letter];
			$total++;
		}else{
			$book_arr[] = NULL;
		}
	}
	
	$book["list"] = $book_arr;
	$book["total"] = $total;
	
	return $book;
}

function getNewBookID(){
	include "db.php";
	$stmt = mysqli_stmt_init($db);
	mysqli_stmt_prepare($stmt, "SELECT book_id FROM books ORDER BY book_id DESC LIMIT 1");
	mysqli_stmt_execute($stmt);
	
	$result = mysqli_stmt_get_result($stmt);
	$highest = mysqli_fetch_assoc($result)["book_id"];
	
	mysqli_close($db);
	
	return $highest + 1;
}

function createBook($fid, $fauthor, $ftitle, $fpublic){
	include "db.php";
	$stmt = mysqli_stmt_init($db);
	mysqli_stmt_prepare($stmt, "INSERT INTO books (book_id, creator_email, title, privacy_filter) VALUES (?, ?, ?, ?)
								ON DUPLICATE KEY UPDATE
								title = VALUES(title),
								privacy_filter = VALUES(privacy_filter);");
	mysqli_stmt_bind_param($stmt, "isss", $fid, $fauthor, $ftitle, $fpublic);
	mysqli_stmt_execute($stmt);
	mysqli_close($db);
}

function deleteBook($fid){
	include "db.php";
	$stmt = mysqli_stmt_init($db);
	mysqli_stmt_prepare($stmt, "DELETE FROM books WHERE book_id=?");
	mysqli_stmt_bind_param($stmt, "i", $fid);
	mysqli_stmt_execute($stmt);
	mysqli_close($db);
}

function setBookSlot($fid, $blog_id, $slot){
	include "db.php";
	$slot = mysqli_real_escape_string($db, $slot);
	$stmt = mysqli_stmt_init($db);
	mysqli_stmt_prepare($stmt, "INSERT INTO books (book_id, " . $slot . ") VALUES (?, ?) ON DUPLICATE KEY UPDATE " . $slot . " = VALUES(" . $slot . ");");
	mysqli_stmt_bind_param($stmt, "si", $fid, $blog_id);
	mysqli_stmt_execute($stmt);
	mysqli_close($db);
}

function clearBookSlot($fid, $slot){
	include "db.php";
	$slot = mysqli_real_escape_string($db, $slot);
	$stmt = mysqli_stmt_init($db);
	mysqli_stmt_prepare($stmt, "INSERT INTO books (book_id, " . $slot . ") VALUES (?, NULL) ON DUPLICATE KEY UPDATE " . $slot . " = VALUES(". $slot . ");");
	
	echo mysqli_stmt_error($stmt);
	
	mysqli_stmt_bind_param($stmt, "s", $fid);
	mysqli_stmt_execute($stmt);
	mysqli_close($db);
}

function inBookSlot($fbook_id, $fblog_id){
	include "db.php";
	$slot = getBlogById($fid)["title"][0]; //TODO: fragile?
	$book = getBookById($fbook_id);
	
	return $book[$slot] == $fblog_id;
}

function getBookThumbnail($fbook_id, $faccount){
	$book = getBookById($fbook_id);
	for($i = 0; $i < 26; $i++){
		$blog = getBlogById($book["list"][$i]);
		if(!$blog)
			continue;
		
		if($blog["image"] != ""){
			if($blog["privacy_filter"] == "private" && (!$faccount || $faccount["email"] != $blog["creator_email"]))
				continue;
			
			return $blog["image"];
		}
	}
	
	return "default.png";
}
