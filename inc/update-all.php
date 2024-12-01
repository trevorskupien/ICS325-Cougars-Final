<?php
	include "account.php";
	include "db.php";
	include "book_data.php";
	include "blog_data.php";

	if(!isset($_GET["email"]) || !isset($_GET["type"]) || !isset($_GET["privacy"])){
		header('Location: ../index.php');
		exit;
	}
	
	$account = getSessionAccount();
	
	if(!$account){
		header('Location: ../login.php');
		exit;
	}
	
	//get data
	$email = $_GET["email"];
	$type = $_GET["type"];
	$private = $_GET["privacy"] == "public";
	
	if($account["role"] != "admin" && $email != $account["email"]){
		header('Location: ../index.php');
		exit;
	}
	
	$user = getUser($email);
	
	if(!$user){
		header('Location: ../login.php');
		exit;
	}
	
	//continue with deletion
	if($type == "blogs"){
		setBlogsPublic($email, $private);
	}
	
	elseif($type == "books"){
		setBooksPublic($email, $private);
	}
	
	echo "success";
	header('Location: ../profile.php?email=' . $email . "&display=" . $type);
	exit;
?>