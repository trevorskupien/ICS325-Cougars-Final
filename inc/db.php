<?php
$hostname = "localhost";
$username = "root";
$password = "";
$database = "photo-cougars-db";

$db = mysqli_connect("localhost", "root", "", "photo_cougars_db");

if(!$db){
	die("Connection failed: " . mysqli_connect_error());
}