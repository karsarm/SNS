<?php
	session_start();
	include('dbconnect.php');
	$token3 = $_COOKIE['token'];
	
	$logo = $pdo -> prepare("UPDATE user SET LoginFlag = false,Token = null WHERE Token = :tok ");
	$logo -> bindParam(':tok',$token3);
	$logo -> execute();
	
	setCookie("token", '', -1);
	session_destroy();
	header("Location: login.php");
?>