<?php
session_start();
if (!isset($_POST['username']) or !isset($_POST['password'])){
	echo 'Κενό όνομα ή κωδικός';
	exit();
}


$userName = SQLite3::escapeString($_POST['username']);
$password = SQLite3::escapeString($_POST['password']);
//password to 
$password = password_hash($password, PASSWORD_BCRYPT);

$db = new SQLite3('../databases/database.db');

$userExists = $db->querySingle("SELECT EXISTS (SELECT 1 FROM users WHERE username=\"{$userName}\")");

if($userExists){
	$_SESSION["error"] = "Το όνομα {$userName} υπάρχει";
	header("location: /register.php");
	exit();
}

$db->exec("INSERT INTO users(username, password) VALUES(\"{$userName}\", \"{$password}\")");
$_SESSION['username'] = $userName;
$_SESSION['loggedin'] = true;
header('Location: /');
?>