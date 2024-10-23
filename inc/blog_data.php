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

?>