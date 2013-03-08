<?php
	
	// done in header.php
	// session_start();
	
// Report all PHP errors (see changelog)
error_reporting(E_ALL);

	//include "../files/functions.php";
	
	// done in header.php	
	// require "files/include.php";	
	
	//prepare eventtype variables


//dbconnect();	
/*
if($_SESSION['id'] != 1) {
			// redirect user to home page when not logged in
			$host = $_SERVER["HTTP_HOST"];
			$path = "");
			$site = "index.php";
			//echo($host.$path.$site);
			header("Location: http://$host$site");
			exit;			

	}
*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>Backoffice</title>
 </head>
 <body>
<h1>Backoffice</h1> 
 <div> 
<h2>XML-tools</h2>
<ul>
	<li><a href="tools/xml_lastfm.php" >XML Import Last.fm</a></li>
</ul> 

<h2>Matching-tools</h2>
<ul>
	<li><a href="locationmatch.php">Match duplicate locations</a></li>
</ul>
 
 
 </div>
 
 </body>
 
 </html>