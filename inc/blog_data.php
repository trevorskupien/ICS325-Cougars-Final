<?php
function getBlogs(){
	include "db.php";
	
	$stmt = mysqli_stmt_init($db);
	mysqli_stmt_prepare($stmt, "SELECT * FROM blogs");
	$result = mysqli_stmt_execute($stmt);
	
	if(!$result){
		return null;
	}
	
	$dbblogs = mysqli_stmt_get_result($stmt);
	
	$blogs = [];
	
	if(mysqli_num_rows($dbblogs) > 0){
		while($dbblog = mysqli_fetch_assoc($dbblogs)){
			$blogs[] = $dbblog;
		}
	}
	
	mysqli_close($db);
	return $blogs;
}

function getUserBlogs($fauthor){
	include "db.php";
	
	$stmt = mysqli_stmt_init($db);
	mysqli_stmt_prepare($stmt, "SELECT * FROM blogs WHERE privacy_filter = 'public' OR creator_email = ?");
	mysqli_stmt_bind_param($stmt, "s", $fauthor);
	$result = mysqli_stmt_execute($stmt);
	
	if(!$result){
		return null;
	}
	
	$dbblogs = mysqli_stmt_get_result($stmt);
	
	$blogs = [];
	
	if(mysqli_num_rows($dbblogs) > 0){
		while($dbblog = mysqli_fetch_assoc($dbblogs)){
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
	mysqli_stmt_prepare($stmt, "INSERT INTO blogs (blog_id, image, creator_email, title, description, event_date, privacy_filter) VALUES (?, ?, ?, ?, ?, ?, ?)");
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
?>