<?php
session_start();
if (!isset($_SESSION['username'])) {
	header('Location: login.php');
	exit();
}
if(!isset($_POST['id'])){
	exit();
}
$id = intval($_POST['id']);
$userName = SQLite3::escapeString($_SESSION['username']);

$db = new SQLite3('../databases/database.db');


//xrisimopioume kai to username gia na epiveveosoume oti i diagrafi tha gini apo ton idio ton xristi
$id = $db->exec("DELETE FROM observations WHERE id={$id} AND user=\"{$userName}\"");

?>