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

if($lid != '') {$p_lid = "location_id = ".$lid." AND ";}
// get events
// if location-id is provided, only events from that location will be exported
// if no location-id is provided, all events will be exported

$query = "SELECT `event_id`, `location_id`, `ex_id`, `stage_id`, `f_id`, `canceled`, `title`, `url`, `ticket_url`, 
					  `artist`, `price_min`, `price_max`, `price_free`, `donation`, `type`, `genre`, `starttime`, 
					  `endtime`, `set_endtime`, `description`, `prio`, `img_original`, `img_480`, `img_320`, 
					  `img_240`, `img_160`, `img_present`, `youtube`, `created`, `modified`, `verified` 
					  FROM `events` WHERE $p_lid starttime > NOW()";
$result = mysql_query($query);
$n = mysql_num_rows($result);
$events = mysql_fetch_assoc($result);


 


$xml = new SimpleXMLElement('<xml/>');


do {
	
	$xml->addAttribute('feed', "terse");
	$xml->addAttribute('type', "events");
	$xml->addAttribute('time', time());
	$xml->addAttribute('n_events', $n);
	$xml->addAttribute('comments', "using this feed assumes that seperate feeds are used for each table. 
											  therefore this feed contains no additional information on location, festival, exhibition and stage
											  (besides the relevant ids). Recommended if frequent updates are necessary.");
	
   	$event = $xml->addChild('event');

			// prepare img-link
			if($events['img_original'] != '') { $img_link = "http://www.schmittens.net/img/event/".$events['img_original']; }
   		   		
   		$event->addChild('link', "http://www.schmittens.net/event.php?event_id=".$events['event_id']);
   		$event->addChild('img_link', $img_link);
   		$event->addChild('genre_name', getgenrename($events['genre']));
 
    		unset($img_link);
   	
   	// loop through queried values
   	foreach($events as $key => $value) {
		$event->addChild($key, $value);   		
   		}


    	
}
while ($events = mysql_fetch_assoc($result));

Header('Content-type: text/xml');
print($xml->asXML());


}
?>