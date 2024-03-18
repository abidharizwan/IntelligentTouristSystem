<?php
session_start();
include('/var/www/html/connection.php');

$lat = $_GET['lat'];
$lng = $_GET['lng'];
$_SESSION['mylat'] = $lat;
$_SESSION['mylng'] = $lng;

header('location: home.php');
?>