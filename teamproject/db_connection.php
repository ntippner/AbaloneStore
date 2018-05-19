<?php


$host = "localhost";
$dbname = "tipp4121"; //change this to your otterID
$username = "tipp4121"; //change this to your otter ID
$password = "saddog12"; //change this to your database account password

//establishes database connection
$dbConn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

//shows errors when connecting to database
$dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
?>