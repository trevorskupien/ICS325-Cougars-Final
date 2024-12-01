<?php
	include "account.php";
	include "book_data.php";
	include "db.php";
	
	if(!isset($_POST["submit"]) || !isset($_SESSION["account"])){
		header('Location: ../index.php');
		exit;
	}
	
	//get post data
	$author = $_SESSION["account"]["email"];
	$title = $_POST["title"];
	$public = isset($_POST["public"]) ? "public" : "private";
	$return = "book.php";
	
	if(isset($_POST["id"])){
		$book_id = $_POST["id"];
		$book = getBookById($book_id);
		if(!$book || ($_SESSION["account"]["role"] != "admin" && $book["creator_email"] != $author)){
			//attempting to edit a blog unknowned or nonexistant
			header('Location: ../index.php');
			exit;
		}
		
		$return = "edit-book.php";
	}else{
		$book_id = getNewBookID();
	}

	//create book entry
	createBook($book_id, $author, $title, $public);

	header('Location: ../' . $return . '?id=' . $book_id);
	exit;
?>