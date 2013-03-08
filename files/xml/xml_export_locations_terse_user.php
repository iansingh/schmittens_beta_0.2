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

if($lid != '') {$p_lid = "AND location_id = ".$lid;}

// get locations
// if location-id is provided, only that location will be exported
// if no location-id is provided, all locations will be exported

$query = "SELECT `location_id`, `l_name`, `street`, `streetnumber`, `additional`, `postalcode`, 
						`city`, `province`, `url`, `mail`, `type`, `facebook`, `twitter`, `img_original`, 
						`img_480`, `img_320`, `img_240`, `img_160`, `creation`, `update`, `verification`,
						`monday_s`, `monday_e`, `tuesday_s`, `tuesday_e`, `wednesday_s`, `wednesday_e`, `thursday_s`, `thursday_e`, 
						`friday_s`, `friday_e`, `saturday_s`, `saturday_e`, `sunday_s`, `sunday_e` FROM `location` WHERE created_by = $uid $p_lid";
$result = mysql_query($query);
$n = mysql_num_rows($result);
$locations = mysql_fetch_assoc($result);
//extract($locations);

if($n < 1) {echo"You have no locations to export"; exit;} 


$xml = new SimpleXMLElement('<xml/>');


do {
	
	$xml->addAttribute('feed', "terse");
	$xml->addAttribute('type', "locations");
	$xml->addAttribute('time', time());
	$xml->addAttribute('n_locations', $n);
	$xml->addAttribute('comments', "This feed only contains information on locations. Please use as agreed in the Terms of Use.");
	
   	$location = $xml->addChild('location');

			if($locations['img_original'] != '') { $img_link = "http://www.schmittens.net/img/location/".$locations['img_original']; }
			if(	($locations['monday_s'] != "00:00:00") ||
					($locations['tuesday_s'] != "00:00:00") ||
					($locations['wednesday_s'] != "00:00:00") ||
					($locations['thursday_s'] != "00:00:00") ||
					($locations['friday_s'] != "00:00:00") ||
					($locations['saturday_s'] != "00:00:00") ||
					($locations['sunday_s'] != "00:00:00") 
			) {$custom_opening = TRUE;}
			else {$custom_opening = FALSE;}
   		   		
   		$location->addChild('link', "http://www.schmittens.net/location.php?location_id=".$locations['location_id']);
   		$location->addChild('img_link', $img_link);
   		$location->addChild('custom_opening', $custom_opening);
 
    		unset($img_link);
    		unset($custom_opening);
   	
   		// loop through queried values


   	foreach($locations as $key => $value) {
   		$location->addChild($key,$value);
   		}
}
while ($locations = mysql_fetch_assoc($result));

Header('Content-type: text/xml');
print($xml->asXML());


}
?>