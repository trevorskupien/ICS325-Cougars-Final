<?php
	include "account.php";
	if(!isset($_POST["submit"])){
		header('Location: ../index.php');
	}
	
	$email = $_POST["email"];
	$name = $_POST["name"];
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
	
	if(!substr_count($name, '+') === 1){
		header('Location: ../register.php?error=name');
		return;
	}
	
	if(getUser($email)){
		header('Location: ../register.php?error=taken');
		return;
	}
	$name_tokens = explode(" ", $name);
	$first_name = $name_tokens[0];
	$last_name = $name_tokens[1];

	registerUser($first_name, $last_name, $email, $password);
	loginUser($email, $password);
	
	header('Location: ../index.php');
?>