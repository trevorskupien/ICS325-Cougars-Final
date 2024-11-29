<?php
session_start();
function registerUser($ffirst_name, $flast_name, $femail, $fpassword){
	include 'db.php';
	$stmt = mysqli_stmt_init($db);
	mysqli_stmt_prepare($stmt, "INSERT INTO users (email, first_name, last_name, password) VALUES (?, ?, ?, ?)");
	mysqli_stmt_bind_param($stmt, "ssss", $femail, $ffirst_name, $flast_name, password_hash($fpassword, PASSWORD_DEFAULT));
	$result = mysqli_stmt_execute($stmt);
	
	if($result){
		return "Registration successful!";
	}else{
		return "Database error while registering.";
	}
	mysqli_close($db);
}

function loginUser($femail, $fpassword){
	$user = getUser($femail);
	if(!$user){
		return false;
	}
	
	if(!password_verify($fpassword, $user["password"])){
		return false;
	}
	
	if(session_status() !== PHP_SESSION_ACTIVE){
		session_start();
	}
	
	$_SESSION['account'] = $user;
	return true;
}

function getUser($femail){
	include 'db.php';
	$stmt = mysqli_stmt_init($db);
	mysqli_stmt_prepare($stmt, "SELECT * from users WHERE email = ? LIMIT 1");
	mysqli_stmt_bind_param($stmt, "s", $femail);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	mysqli_close($db);
	
	if(!$result || mysqli_num_rows($result) === 0)
		return null;
	
	$user = mysqli_fetch_assoc($result);
	$first_name = $user["first_name"];
	$last_name = $user["last_name"];
	$email = $user["email"];
	$password = $user["password"];
	$user["name"] = $first_name . " " . $last_name;
	return $user;
}

//get session account, but reconfirm with database
function getSessionAccount(){
	if(session_status() == PHP_SESSION_ACTIVE && isset($_SESSION['account'])){
		return getUser($_SESSION['account']['email']);
	}else{
		return null;
	}
}

function deleteUser($femail){
	include 'db.php';
	$stmt = mysqli_stmt_init($db);
	mysqli_stmt_prepare($stmt, "DELETE FROM users WHERE email=?");
	mysqli_stmt_bind_param($stmt, "s", $femail);
	mysqli_stmt_execute($stmt);
	mysqli_close($db);
}

function logout(){
	unset($_SESSION['account']);
	session_destroy();
}

function getWelcomeText(){
	if(session_status() == PHP_SESSION_ACTIVE && isset($_SESSION['account'])){
		return "Welcome back, " . $_SESSION['account']['first_name'] . "!";
	}
	return "Not signed in.";
}


//returns all users
function getUsers() {
    include "db.php";

    // Start building the query
    $query = "SELECT * FROM users";
	
    $stmt = mysqli_stmt_init($db);
    mysqli_stmt_prepare($stmt, $query);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        return null;
    }

    $dbusers = mysqli_stmt_get_result($stmt);

    $users = [];
    if (mysqli_num_rows($dbusers) > 0) {
        while ($dbuser = mysqli_fetch_assoc($dbusers)) {
			$dbuser["name"] = $dbuser["first_name"] . " " . $dbuser["last_name"];
            $users[] = $dbuser;
        }
    }

    mysqli_close($db);
    return $users;
}

function getAccountStats($femail){
	include_once "blog_data.php";
	include_once "book_data.php";
	
	$account = getUser($femail);
	if(!$account){
		return null;
	}
	
	$stats = [];
	$blogs = getBlogs(null, null, $femail);
	$books = getBooks(null, null, $femail);
	
	$stats["blog_count"] = count($blogs);
	$stats["book_count"] = count($books);
	
	$coverage_arr = array_fill(0, 26, false);
	$coverage = 0;
	foreach($blogs as $blog){
		$title = $blog["title"];
		$coverage_arr[ord($title[0]) - ord('A')] = true;
	}
	for($i = 0; $i < 26; $i++){
		if($coverage_arr[$i])
			$coverage++;
	}
	
	$stats["coverage"] = $coverage;
	return $stats;
}