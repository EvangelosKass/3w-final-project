<?php
session_start();
if (!isset($_SESSION['username'])) {
	header('Location: login.php');
	exit();
}

$userName = SQLite3::escapeString($_SESSION['username']);
$temperature = floatval($_POST['temperature']);
$humidity = floatval($_POST['humidity']);
$name = SQLite3::escapeString($_POST['name']);
$pressure = floatval($_POST['pressure']);
$conditions = SQLite3::escapeString($_POST['conditions']);
$notes = SQLite3::escapeString($_POST['notes']);
$latitude = floatval($_POST['latitude']);
$longitude = floatval($_POST['longitude']);
$date = time();

$db = new SQLite3('../databases/database.db');
$db->exec("INSERT INTO observations(date, user, latitude, longtitude,name,temperature,humidity, pressure, conditions, notes) VALUES(\"{$date}\", \"{$userName}\", \"{$latitude}\", \"{$longitude}\", \"{$name}\", \"{$temperature}\", \"{$humidity}\", \"{$pressure}\",\"{$conditions}\", \"{$notes}\")");
$id = $db->query("SELECT id FROM observations ORDER BY id DESC LIMIT 1")->fetchArray()['id'];
header("Location: /obsdetails.php?id={$id}");
?>