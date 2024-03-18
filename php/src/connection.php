<?php
	$dbhost = 'db';
    $dbuser = 'MYSQL_USER';
    $dbpass = 'MYSQL_PASSWORD';
	$dbname = "MYSQL_DATABASE";
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);    
    if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);
?>