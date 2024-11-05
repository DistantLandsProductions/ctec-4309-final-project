<?php

function dbConnect(){
	$host = "localhost";
	$user = "gkb8719_dbuser";
	$pwd = "testpasswordyay";
	$database = "gkb8719_4321";
	$port = "3306";
 
	$conn = new mysqli($host, $user, $pwd, $database, $port) or die("could not connect to server");

	return $conn;}
?>