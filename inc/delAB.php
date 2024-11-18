<?php
	include "account.php";
	include "blog_data.php";
	
	if(!isset($_GET["id"]) || !isset($_SESSION["account"])){
		header('Location: ../index.php');
		exit;
	}
	
	$id = $_GET["id"];
	$blog = getBlogById($id);
	$letter = $blog["title"][0];
	$return = $_GET["return"];
	
	delAlphabetBook($_SESSION["account"]["email"], $letter);
	
	header('Location: ../' . $return);
	exit;
?>