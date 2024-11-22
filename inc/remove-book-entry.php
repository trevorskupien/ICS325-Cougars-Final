<?php
	include "account.php";
	include "blog_data.php";
	include "book_data.php";
	
	if(!isset($_GET["book_id"]) || !isset($_GET["blog_id"]) || !isset($_SESSION["account"])){
		//header('Location: ../books.php');
		//exit;
		echo "1";
		exit;
	}

	$book_id = $_GET["book_id"];
	$blog_id = $_GET["blog_id"];
	$blog = getBlogById($blog_id);
	$book = getBookById($book_id);
	
		
	if($_SESSION["account"]["email"] != $book["creator_email"]){
		//header('Location: ../books.php');
		//exit;
		echo "2";
		exit;
	}
	
	$letter = $blog["title"][0];
	$return = $_GET["return"];
	
	clearBookSlot($book_id, $letter);
	
	header('Location: ../' . $return);
	exit;
?>