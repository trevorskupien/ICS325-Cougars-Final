<?php
	include "account.php";
	include "db.php";
	
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
		header('Location: ../book.php?id=' . $id);
		exit;
	}
	
	//continue with deletion
	deleteUser($id);
		
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