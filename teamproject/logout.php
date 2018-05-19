<?php
session_start();
require 'db_connection.php';

$record = $_SESSION['record'];
$sql = "INSERT INTO LOG(CustomerUsername, event)
		VALUES(:CustomerUsername, 'Logged Out')";
$stmt = $dbConn -> prepare($sql);
$stmt -> execute(array(":CustomerUsername"=>$record['CustomerUsername']));

$_SESSION['record']=$record;
session_destroy();

header("Location: login.php");
?>