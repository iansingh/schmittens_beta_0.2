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

$query = "SELECT `ex_id`, `location_id`, `canceled`, `e_title`, `startdate`, `enddate`, `url`, `ticket_url`, `artist`, 
					  `price_min`, `price_max`, `price_free`, `donation`, `genre`, `description`, `prio`, 
					  `img_original`, `img_480`, `img_240`, `img_160`, `img_present`, `youtube`, 
					  `created`, `modified`, `verified`, 
					  `monday_s`, `monday_e`, `tuesday_s`, `tuesday_e`, `wednesday_s`, `wednesday_e`, `thursday_s`, `thursday_e`, 
					  `friday_s`, `friday_e`, `saturday_s`, `saturday_e`, `sunday_s`, `sunday_e`
					  FROM `exhibitions` $p_lid";
$result = mysql_query($query);
$n = mysql_num_rows($result);
$exhibitions = mysql_fetch_assoc($result);


 


$xml = new SimpleXMLElement('<xml/>');


do {
	
	$xml->addAttribute('feed', "terse");
	$xml->addAttribute('type', "exhibitions");
	$xml->addAttribute('time', time());
	$xml->addAttribute('n_exhibitions', $n);
	$xml->addAttribute('comments', "using this feed assumes that seperate feeds are used for each table. 
											  This feed only contains information on exhibitions.");
	
   	$exhibition = $xml->addChild('exhibition');
   	
			// prepare img-link
			if($exhibitions['img_original'] != '') { $img_link = "http://www.schmittens.net/img/exhibition/".$exhibitions['img_original']; }
			if(	($exhibitions['monday_s'] != "00:00:00") ||
					($exhibitions['tuesday_s'] != "00:00:00") ||
					($exhibitions['wednesday_s'] != "00:00:00") ||
					($exhibitions['thursday_s'] != "00:00:00") ||
					($exhibitions['friday_s'] != "00:00:00") ||
					($exhibitions['saturday_s'] != "00:00:00") ||
					($exhibitions['sunday_s'] != "00:00:00") 
			) {$custom_opening = TRUE;}
			else {$custom_opening = FALSE;}
   		   		
   		$exhibition->addChild('link', "http://www.schmittens.net/exhibition.php?ex_id=".$exhibitions['ex_id']);
   		$exhibition->addChild('img_link', $img_link);
   		$exhibition->addChild('genre_name', getgenrename($exhibitions['genre']));
   		$exhibition->addChild('custom_opening', $custom_opening);
 
    		unset($img_link);
    		unset($custom_opening);
   	
   		// loop through queried values
   	  	foreach($exhibitions as $key => $value) {
   		$exhibition->addChild($key,$value);
   		}
}
while ($exhibitions = mysql_fetch_assoc($result));

Header('Content-type: text/xml');
print($xml->asXML());


}
?>