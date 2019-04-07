<?php
    date_default_timezone_set( "Asia/Hong_Kong" );
    $hostname = "localhost";
    $username = "root"; //set the username of your database
    $pwd = ""; //set the password of your database
    $db = "reversi_db";
    $conn = mysqli_connect($hostname, $username, $pwd, $db) or die(mysqli_connect_error());
    mysqli_query($conn,"SET NAMES utf8");
	return $conn;
?>