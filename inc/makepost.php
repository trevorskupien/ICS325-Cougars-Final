<?php
	include "account.php";
	include "blog_data.php";
	include "db.php";
	
	if(!isset($_POST["submit"]) || !isset($_SESSION["account"])){
		header('Location: ../index.php');
		exit;
	}
	
	//get post data
	$author = $_SESSION["account"]["email"];
	$title = $_POST["title"];
	$letter = $_POST["title-letter"];
	$content = $_POST["post-content"];
	$public = isset($_POST["public"]) ? "public" : "private";
	$filename = $_FILES["image"]["name"];
	
	//make final title
	$title = $letter . " for " . $title;
	
	if(isset($_POST["id"])){
		$blog_id = $_POST["id"];
		$blog = getBlogById($blog_id);
		if(!$blog || $blog["creator_email"] != $author){
			//attempting to edit a blog unknowned or nonexistant
			header('Location: ../index.php');
			exit;
		}
	}else{
		$blog_id = getNewBlogID();
	}
	
	$target_file = "";
	$date = date("Y:m:d H:i:s");
	
	//upload image file if exists
	if($filename){
		$target_dir = "../images/";
		$file_type = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
		$target_file = $blog_id . "." . $file_type;
		
		if ($_FILES["image"]["size"] > 50000000) {
			$_SESSION["title"] = $title;
			$_SESSION["content"] = $content;
			header('Location: ../post.php?error=size');
			exit;
		}

		$ok = getimagesize($_FILES["image"]["tmp_name"]);
		
		if($ok){
			move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $target_file);
		}else{
			$_SESSION["title"] = $title;
			$_SESSION["content"] = $content;
			header('Location: ../post.php?error=format');
			exit;
		}
	}

	//create blog entry
	//function postBlog($fid, $fdate, $fauthor, $ftitle, $fcontent, $fpublic, $fimage){
	postBlog($blog_id, $date, $author, $title, $content, $public, $target_file);
	echo "here";
	header('Location: ../blog.php?id=' . $blog_id);
	exit;
?>