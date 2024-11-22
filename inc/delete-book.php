<?php
	include "account.php";
	include "book_data.php";
	include "db.php";
	
	if(!isset($_GET["id"])){
		header('Location: ../index.php');
		exit;
		
	}
	
	if(!isset($_SESSION["account"])){
		header('Location: ../login.php');
		exit;
	}
	
	//get data
	$current_user = $_SESSION["account"]["email"];
	$id = $_GET["id"];
	$blog = getBookById($id);
	
	if(strcmp($current_user, $blog["creator_email"])){
		header('Location: ../book.php?id=' . $id);
		exit;
	}
	
	//continue with deletion
	deleteBook($id);
	
	header('Location: ../books.php');
	exit;
?>