<?php 
	header('Content-type: text/html; charset=utf-8');
	session_start();
	error_reporting(0); //E_ALL & ~E_NOTICE
	ini_set('display_errors', FALSE);
	ini_set('display_startup_errors', FALSE);

	//require "files/nav.php";
	require "files/include.php";	
	require "files/functions.php";
	require "files/datetimepicker.php";
	
	// checklogin();	
	
	// get user data
	$user = $_SESSION["user"];
	$user_id = $_SESSION["id"];	
	$user_verification = $_SESSION["verification"];
	


?>	
	


