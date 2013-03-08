<?php
session_start();

require "../include.php";
require "../functions.php";
require "../stagefunctions.php";

dbconnect();

$err = 0;

//var_dump($_GET);
//var_dump($_SESSION);

	$uid = $_GET['user_id'];
	$fs = $_GET['feedsecret'];
	$lid = $_GET['location_id'];
	//$t = $_GET['type']; // Party, Concert, Other, Exhibition, Stage
	//$g = $_GET['genre'];



// check if feedsecret was delivered (GET)

if($fs == '') {$err_fs = "No secret, no XML"; echo($err_fs); $err++;}
if($uid == '') {$err_uid = "No user id provided"; echo($err_uid); $err++;}



// check if feedsecret matches the user

if($fs != '') {
	

		$query = "SELECT 1 FROM users WHERE feedsecret = '$fs' AND id = $uid";
		$result = mysql_query($query);
		$check = mysql_num_rows($result);
		
		if($check != 1) {$err_fsc = "Secret did not match"; echo($err_fsc); $err++;}

	
	}




if($err < 1) {

if($lid != '') {$p_lid = "WHERE location_id = ".$lid;}

// get locations
// if location-id is provided, only that location will be exported
// if no location-id is provided, all locations will be exported

$query = "SELECT `f_id`, `f_title`, `canceled`, `location_id`, `startdate`, `enddate`, `price_info`, `description`, 
					  `url`, `ticket_url`, `img_original`, `img_480`, `img_320`, `img_240`, `img_160`, `img_present`, 
					  `youtube`, `created`, `modified`, `verified` 
					  FROM `festivals` $p_lid";
$result = mysql_query($query);
$n = mysql_num_rows($result);
$festivals = mysql_fetch_assoc($result);


 


$xml = new SimpleXMLElement('<xml/>');


do {
	
	$xml->addAttribute('feed', "terse");
	$xml->addAttribute('type', "festivals");
	$xml->addAttribute('time', time());
	$xml->addAttribute('n_festivals', $n);
	$xml->addAttribute('comments', "using this feed assumes that seperate feeds are used for each table. 
											  This feed only contains information on festivals.");
	
   	$festival = $xml->addChild('festival');
   	
			// prepare img-link
			if($festivals['img_original'] != '') { $img_link = "http://www.schmittens.net/img/festival/".$festivals['img_original']; }
   		   		
   		$festival->addChild('link', "http://www.schmittens.net/festival.php?f_id=".$festivals['f_id']);
   		$festival->addChild('img_link', $img_link);
 
    		unset($img_link);
   	
   		// loop through queried values
   	  	foreach($festivals as $key => $value) {
   		$festival->addChild($key,$value);
   		}
}
while ($festivals = mysql_fetch_assoc($result));

Header('Content-type: text/xml');
print($xml->asXML());


}
?>