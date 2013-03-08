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

$query = "SELECT * FROM `events` WHERE $p_lid starttime > NOW()";
$result = mysql_query($query);
$n = mysql_num_rows($result);
$events = mysql_fetch_assoc($result);


 


$xml = new SimpleXMLElement('<xml/>');


do {
	


	
	//prepare festival-data
	
	$xml->addAttribute('type', "verbose");
	$xml->addAttribute('time', time());
	$xml->addAttribute('n_events', $n);
	
   	$event = $xml->addChild('event');
  		$event->addChild('event_id', $events['event_id']);
  		$event->addChild('location_id', $events['location_id']);
   	$event->addChild('ex_id', $events['ex_id']);
   	$event->addChild('stage_id', $events['stage_id']);
    	$event->addChild('f_id', $events['f_id']);
    	$event->addChild('title', $events['title']);
   	$event->addChild('artists', $events['artists']);
    	$event->addChild('description', $events['description']); 
   	$event->addChild('url', $events['url']);
   	$event->addChild('ticket_url', $events['ticket_url']);
    	$event->addChild('type', $events['type']);
    	$event->addChild('genre', $events['genre']);
    	$event->addChild('genre_name', getgenrename($events['genre']));
    	$event->addChild('price_min', $events['price_min']);
    	$event->addChild('price_max', $events['price_max']);
    	$event->addChild('price_free', $events['price_free']);
    	$event->addChild('donation', $events['donation']);
    	$event->addChild('starttime', $events['starttime']);
    	$event->addChild('endtime', $events['endtime']);
    	$event->addChild('set_endtime', $events['set_endtime']);
    	$event->addChild('prio', $events['prio']);
    	$event->addChild('img_original', "http://www.schmittens.net/img/event/".$events['img_original']);
    	$event->addChild('img_480', $events['img_480']);
    	$event->addChild('img_320', $events['img_320']);
    	$event->addChild('img_240', $events['img_240']);
    	$event->addChild('img_160', $events['img_160']);
    	$event->addChild('img_present', $events['img_present']);
    	$event->addChild('youtube', $events['youtube']);
    	$event->addChild('created_by', $events['created_by']);
    	$event->addChild('verified', $events['verified']);
    	
 		//prepare location-data
 	  	$location = getlocationdata($events['location_id']);
		extract($location);
    	
   	$location = $event->addChild('location');
   		
		// in this feed only limited information on locations is provided. 
		// cgetgenrename($genre)heck location-feed for more details 
   	
   		$location->addChild('location_id', $events['location_id']);
			$location->addChild('l_name', $l_name);
   		$location->addChild('street', $street);
   		$location->addChild('streetnumber', $streetnumber);
   		$location->addChild('additional', $additional);
   		$location->addChild('postalcode', $postalcode);
   		$location->addChild('city', $city);
   		$location->addChild('province', $province);
   		$location->addChild('url', $url);
   		$location->addChild('mail', $mail);
   		$location->addChild('type', $type);
   		$location->addChild('facebook', $facebook);
   		$location->addChild('twitter', $twitter);
   		$location->addChild('img_original', "http://www.schmittens.net/img/location/".$img_original);
   		$location->addChild('img_480', $img_480);
   		$location->addChild('img_320', $img_320);
   		$location->addChild('img_240', $img_240);
   		$location->addChild('img_160', $img_160);
   		$location->addChild('created_by', $created_by);
   		$location->addChild('creation', $creation);
   		$location->addChild('update', $update);
   		$location->addChild('verification', $verification);
   		$location->addChild('source', $source);
   		$location->addChild('monday_s', $monday_s);
   		$location->addChild('monday_e', $monday_e);
   		$location->addChild('tuesday_s', $tuesday_s);
   		$location->addChild('tuesday_e', $tuesday_e);
   		$location->addChild('wednesday_s', $wednesday_s);
   		$location->addChild('wednesday_e', $wednesday_e);
   		$location->addChild('thursday_s', $thursday_s);
   		$location->addChild('thursday_e', $thursday_e);
   		$location->addChild('friday_s', $friday_s);
   		$location->addChild('friday_e', $friday_e);
   		$location->addChild('saturday_s', $saturday_s);
   		$location->addChild('saturday_e', $saturday_e);
   		$location->addChild('sunday_s', $sunday_s);
   		$location->addChild('sunday_e', $sunday_e);
   		
   	//prepare festival-data
   	$festival = getfestivaldata($events['f_id']);
   	extract($festival);
   	
   	$festival = $event->addChild('festival');
   	
   	// in this feed only limited information on festivals is provided. 
		// check festival-feed for more details 
		if($events['f_id'] != 0) {
   		$festival->addChild('f_id', $events['f_id']);
   		$festival->addChild('f_title', $festival['f_title']);
   		$festival->addChild('location_id', $festival['location_id']);
   		$festival->addChild('startdate', $festival['startdate']);
   		$festival->addChild('enddate', $festival['enddate']);
   		$festival->addChild('price_info', $festival['price_info']);
   		$festival->addChild('description', $festival['description']);
   		$festival->addChild('url', $festival['url']);
   		$festival->addChild('ticket_url', $festival['ticket_url']);
   		$festival->addChild('img_original', "http://www.schmittens.net/img/festival/".$festival['img_original']);
   		$festival->addChild('img_480', $festival['img_480']);
   		$festival->addChild('img_320', $festival['img_320']);
   		$festival->addChild('img_240', $festival['img_240']);
   		$festival->addChild('img_160', $festival['img_160']);
   		$festival->addChild('img_present', $festival['img_present']);
   		$festival->addChild('youtube', $festival['youtube']);
   		$festival->addChild('created_by', $festival['created_by']);
   		$festival->addChild('created', $festival['created']);
   		$festival->addChild('modified', $festival['modified']);
   		$festival->addChild('verified', $festival['verified']);
   	}
   	
   	$exhibition = getexhibitiondata($events['ex_id']);
   	extract($exhibition);
   	
   	$exhibition = $event->addChild('exhibition');
   	
   	if($events['ex_id'] != 0) {
   		$exhibition->addChild('ex_id', $events['ex_id']);
   		$exhibition->addChild('location_id', $location_id);
   		$exhibition->addChild('e_title', $e_title);
   		$exhibition->addChild('startdate', $startdate);
   		$exhibition->addChild('enddate', $enddate);
   		$exhibition->addChild('url', $url);
   		$exhibition->addChild('ticket_url', $ticket_url);
   		$exhibition->addChild('artist', $artist);
   		$exhibition->addChild('price_min', $price_min);
   		$exhibition->addChild('price_max', $price_max);
   		$exhibition->addChild('price_free', $price_free);
   		$exhibition->addChild('donation', $donation);
   		$exhibition->addChild('genre', $genre);
   		$exhibition->addChild('genre_name', getgenrename($genre));
   		$exhibition->addChild('description', $description);   		
   		$exhibition->addChild('prio', $prio);   		
   		$exhibition->addChild('img_original', "http://www.schmittens.net/img/exhibition/".$img_original);   		
   		$exhibition->addChild('img_480', $img_480);   		
   		$exhibition->addChild('img_320', $img_320);   		
   		$exhibition->addChild('img_240', $img_240);   		
   		$exhibition->addChild('img_160', $img_160);   		
   		$exhibition->addChild('img_present', $img_present);   		
   		$exhibition->addChild('youtube', $youtube);
   		$exhibition->addChild('created_by', $created_by);
   		$exhibition->addChild('created', $created);
   		$exhibition->addChild('modified', $modified);
   		$exhibition->addChild('verified', $verified);
   		$exhibition->addChild('changed', $changed);
   		$exhibition->addChild('changed', $changed);
   		$exhibition->addChild('monday_s', $monday_s);
   		$exhibition->addChild('monday_e', $monday_e);
   		$exhibition->addChild('tuesday_s', $tuesday_s);
   		$exhibition->addChild('tuesday_e', $tuesday_e);
   		$exhibition->addChild('wednesday_s', $wednesday_s);
   		$exhibition->addChild('wednesday_e', $wednesday_e);
   		$exhibition->addChild('thursday_s', $thursday_s);
   		$exhibition->addChild('thursday_e', $thursday_e);
   		$exhibition->addChild('friday_s', $friday_s);
   		$exhibition->addChild('friday_e', $friday_e);
   		$exhibition->addChild('saturday_s', $saturday_s);
   		$exhibition->addChild('saturday_e', $saturday_e);
   		$exhibition->addChild('sunday_s', $sunday_s);
   		$exhibition->addChild('sunday_e', $sunday_e);   		   		
   	}
   	
   	$stage = getstagedata($events['stage_id']);
   	extract($stage);
   	
   	$stage = $event->addChild('stage');
   	
   	if($events['stage_id'] != 0) {
   		$stage->addChild('stage_id', $events['stage_id']);   		
   		$stage->addChild('location_id', $location_id);   		
   		$stage->addChild('title', $title);   		
   		$stage->addChild('url', $url);   		
   		$stage->addChild('ticket_url', $ticket_url);   		
   		$stage->addChild('artist', $artist);   		
   		$stage->addChild('price_min', $price_min);   		
   		$stage->addChild('price_max', $price_max);   		
   		$stage->addChild('price_free', $price_free);   		
   		$stage->addChild('donation', $donation);   		
   		$stage->addChild('genre', $genre); 
   		$stage->addChild('genre_name', getgenrename($genre));  		
   		$stage->addChild('description', $description);   		
   		$stage->addChild('prio', $prio);   		
   		$stage->addChild('img_original', "http://www.schmittens.net/img/stage/".$img_original);   		
   		$stage->addChild('img_480', $img_480);   		
   		$stage->addChild('img_320', $img_320);   		
   		$stage->addChild('img_240', $img_240);   		
   		$stage->addChild('img_160', $img_160);   		
   		$stage->addChild('img_present', $img_present);   		
   		$stage->addChild('youtube', $youtube);   		
   		$stage->addChild('created_by', $created_by);   		
   		$stage->addChild('created', $created);   		
   		$stage->addChild('modified', $modified);   		
   		$stage->addChild('verified', $verified);   		
   	}
}
while ($events = mysql_fetch_assoc($result));

Header('Content-type: text/xml');
print($xml->asXML());


}
?>