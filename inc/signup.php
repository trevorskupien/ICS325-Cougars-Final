<?php
	include "account.php";
	if(!isset($_POST["submit"])){
		header('Location: ../index.php');
	}
	
	$email = $_POST["email"];
	$first_name = $_POST["first-name"];
	$last_name = $_POST["last-name"];
	$password = $_POST["password"];
	$passwordc = $_POST["password-confirm"];
	
	//Verify that data is valid
	if(!str_contains($email, "@") || !str_contains($email, ".")){
		header('Location: ../register.php?error=email');
		return;
	}
	
	if(strcmp($password, $passwordc)){
		header('Location: ../register.php?error=password');
		return;
	}
	
	if(getUser($email)){
		header('Location: ../register.php?error=taken');
		return;
	}
	$name_tokens = explode(" ", $name);

	registerUser($first_name, $last_name, $email, $password);
	loginUser($email, $password);
	
	header('Location: ../index.php');
?>