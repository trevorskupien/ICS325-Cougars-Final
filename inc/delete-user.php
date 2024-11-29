<?php
	include "account.php";
	include "db.php";
	include "book_data.php";
	include "blog_data.php";
	
	if(!isset($_GET["email"])){
		header('Location: ../index.php');
		exit;
		
	}
	
	if(!isset($_SESSION["account"])){
		header('Location: ../login.php');
		exit;
	}
	
	//get data
	$email = $_GET["email"];
	$current_user = $_SESSION["account"]["email"];

	if($_SESSION["account"]["role"] != "admin" && strcmp($current_user, $email)){
		header('Location: ../index.php');
		exit;
	}
	
	//continue with deletion
	deleteUserBlogs($email);
	deleteUserBooks($email);
	deleteUser($email);
		
	if($current_user == $email){
		logout();
	}
	
	if(isset($_GET["return"])){
		header('Location: ../' . $_GET["return"]);
		exit;
	}

	header('Location: ../index.php');
	exit;
?>