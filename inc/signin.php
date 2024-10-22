<?php
	include "account.php";
	if(!isset($_POST["submit"])){
		header('Location: ../index.php');
	}
	
	$email = $_POST["email"];
	$password = $_POST["password"];
	
	//Verify that data is vahtlid
	if(!str_contains($email, "@") || !str_contains($email, ".")){
		header('Location: ../login.php?error=email');
		return;
	}

	$result = loginUser($email, $password);
	
	if(!$result){
		header('Location: ../login.php?error=failed');
		return;
	}
	
	header('Location: ../index.php');
?>