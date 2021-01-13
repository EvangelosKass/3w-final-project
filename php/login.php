<?php
session_start();
if (!isset($_POST['username']) or !isset($_POST['password'])){
	$_SESSION["error"] = 'Κενό όνομα ή κωδικός';
	header("location: /login.php");
	exit();
}
$userName = SQLite3::escapeString($_POST['username']);
$password = SQLite3::escapeString($_POST['password']);

$db = new SQLite3('../databases/database.db');
$user = $db->query("SELECT * FROM users WHERE username=\"{$userName}\"")->fetchArray();;

if(!$user){
	$_SESSION["error"] = "Το όνομα {$userName} δεν υπάρχει";
	header("location: /login.php");
	exit();
}
$dbpass = $user['password'];
if(password_verify($password,$dbpass)){
	$_SESSION['username'] = $user['username'];
	$_SESSION['loggedin'] = true;
	header('Location: /');
}else{
	$_SESSION["error"] = "Λάθος κωδικός";
	header("location: /login.php");
	exit();
}

