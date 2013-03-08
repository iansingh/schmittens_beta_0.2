<?php 

 header('Content-type: text/html; charset=utf-8');
	//session_start();
	require "../../files/functions.php";
	require "../../files/include.php";	
	require "../../files/datetimepicker.php";

dbconnect();

echo "Last.fm XML-Import";
echo"<br />";



//$ev_array = array();


$lastfmurl = "http://ws.audioscrobbler.com/2.0/?method=geo.getevents&location=montreal&api_key=754b6f56b199f58d91becefdb9bb1bef&limit=0";


$xml = simplexml_load_file($lastfmurl);

echo"Data import from: ";
echo($lastfmurl);

//print_r($xml);

echo "<br /><br />";

foreach($xml->events->event as $event) {
	
// prepare general event vars	
	$title = mysql_real_escape_string($event->title);
	echo($title);
	echo"<br />";
	$st = $event->startDate;
	//echo($st);
	//echo"<br />";
	$url = $event->website;
	//echo($url);
	$ticket_url = $event->tickets;
	$description = mysql_real_escape_string($event->description);
	$type = 'Concert';
	$created_by = '1';

// prepare artists
	$artist_array = array();
	foreach ($event->artists->artist as $artist) {
	array_push($artist_array, (string)$artist);
	}	
	$artistue = implode(", ", $artist_array);
	$artist = mysql_real_escape_string($artistue);
	//echo($artist);
	//echo"<br />";

// prepare location vars
	$l_name = mysql_real_escape_string($event->venue->name);
	//echo($l_name);
	$pc = $event->venue->location->postalcode;
	preg_match('/[A-Za-z][0-9][A-Za-z][ ][0-9][A-Za-z][0-9]/',$pc,$match);
	//print_r($match);
	$postalcode = $match[0];
	echo($postalcode);

	//echo($postalcode);
	$str = mysql_real_escape_string($event->venue->location->street);
	//echo($str);
	$city = mysql_real_escape_string($event->venue->location->city);
	if($city == "") { $city = "Montreal"; }
	//echo($city);
	$l_url = mysql_real_escape_string($event->venue->website);
	//echo($l_url);	
	$province = "QC";
	
//prepare type & genre 
	$ltype = "Concert";
	$genre = 2000;
	
	
	
// prepare source vars
	$source_id = $event->id;
	$source_url = $event->url;
	$source = "last.fm";

	
//prepare & run locationquery

if($postalcode != "") {
// query with postalcode
//$lq = "SELECT `location_id` FROM `location` WHERE l_name LIKE '%$l_name%' AND postalcode = '$postalcode'";
// query without postalcode
$lq = "SELECT `location_id` FROM `location` WHERE l_name LIKE '%$l_name%'";
echo($lq);
$result = mysql_query($lq);
//echo"<br />";
//check how many results were found & prepare switch
$nl = mysql_num_rows($result);
//echo($nl);
if($nl > 1) { echo"<p style='background: red;'>"; echo ($nl); echo" matching locations found - ERROR! </p>"; $switch1 = 2; }
if($nl == 1) { echo"<p style='background: orange;'>Matching location found, checking event: "; echo($l_name); echo"</p>"; $switch1 = 1;  }
if($nl == 0) { echo"<p style='background: green;'>No matching location found, location will be created </p>"; $switch1 = 0; }
}

//switch1 = 2 - create error report and print
if($switch1 == 2) {
	echo"<br />";
	echo"ERROR: ";
	echo($nl);
	echo" locations found! Source-ID of Event: ";
	echo($source_id);
	echo"<br />Source-Name of Location: ";
	echo($l_name);
	echo"<br /> No event created for this id. ";
	$switch2 = -1;
	}	
	
if($postalcode == "") {
	echo"<p style='background: red;'>Wrong format for postal code: ";
	echo($pc);
	echo". Skipped event. Source-ID of Event: ";
	echo($source_id);
	echo"<br />Source-Name of Location: ";
	echo($l_name);
	echo" </p>";

	$switch2 = -1;
	$switch1 = -1;
	}

// no matching location, create location
	if($switch1 == 0) {
preg_match('/[0-9]+/',$str,$match);
$streetnumber = $match[0];
//echo"<br />";
//echo($streetnumber);
//echo"<br />";
preg_match('/\s.[A-Za-z \.\-]+/',$str,$match);
$street = $match[0];
//echo($street);
//echo($city);

$cl = 	"INSERT INTO `location` 
			(`l_name`, `street`, `streetnumber`, `postalcode`, `city`, `province`, 
			`url`, `type`, `created_by`, `creation`, `source`)
			VALUES ('$l_name', '$street', '$streetnumber', '$postalcode', '$city', '$province',
			'$l_url', '$ltype', '1', NOW(), '$source')";
echo($cl);

$clq = mysql_query($cl);
if($clq == TRUE) { echo"<p style='background: green;'>New location created: "; echo($l_name); echo"</p>"; 	}
else { echo"<p style='background: red'>Create location failed: "; echo($l_name); echo"</p>"; }

$switch1 = 1;
}


//switch1 = 1 - create event (also do this step after switch1 = 0) and print confirmation
if($switch1 == 1) {
//get location-id
$lidq = "SELECT location_id FROM `location` WHERE l_name = '$l_name'";
//echo($lidq);
$result = mysql_query($lidq);
if($result == FALSE) { echo"<p style='background: red'>No location selected, event not created:"; echo($title); echo"</p>";}
else {
$lidq_array = mysql_fetch_assoc($result);
$location_id = $lidq_array['location_id'];


//prepare starttime
$starttime = date("Y-m-d G:i:s:00", strtotime($st));
$datecheck = date("Y-m-d 00:00:00:01", strtotime($st));
$datecheck2 = strtotime('+1 day', strtotime($st));
$datecheck2 = date("Y-m-d 00:00:00:01", $datecheck2);
//echo($datecheck2);

$switch2 = "ok";

// check if event is in the future, if not set switch2 to -1
if ($starttime <= date("Y-m-d G:i:s:00")) {
	$switch2 = -1;
	echo"Event is in the past, skipped";}
} }

//check if event already exists

if($switch2 != -1) {
$ec = "SELECT event_id FROM `events` WHERE location_id = '$location_id' AND starttime > '$datecheck' AND starttime < '$datecheck2'";
echo($ec);
$result = mysql_query($ec);
$ne = mysql_num_rows($result);
	//if no match found - create event and notify
	if($ne == 0) { echo"<p style='background: green;'>No matching events found, event will be created </p>"; $switch2 = 0; }
	//if 1 match found - abort and notify
	if($ne == 1) {echo"<p style='background: orange;'>Event found on same day, aborted: "; echo($title); echo"</p>"; $switch2 = 1; }
	//if >1 match found - abort and ERROR
	if($ne > 1) { echo"<p style='background: red;'>"; echo($ne); echo" events found on same day, aborted! </p>"; $switch2 = 2; }
}	

if($switch2 == 2) {
	echo"<br />";
	echo"ERROR: ";
	echo($ne);
	echo" events found! Source-ID of Event: ";
	echo($ev_array['id']);
	echo"<br />Source-Name of Location: ";
	echo($venue_array['name']);
	echo"<br /> No event created for this id.";
	}


	//write event to db
	if($switch2 == 0) {
	$ew = 	"INSERT INTO `events` 
				(`location_id`, `title`, `artist`, 
				`type`, `genre`, `starttime`, `description`, `created_by`, `created`, 
				`source`, `source_id`, `source_url`) 
				VALUES ('$location_id', '$title', '$artist',
				'$type', '$genre', '$starttime', '$description', '$created_by', NOW(),
				'$source', '$source_id', '$source_url')";	
	//echo"<br />";
	echo($ew);
	
	$ewq = mysql_query($ew);
if($ewq == TRUE) { echo"<p style='background: green;'>New event created: "; echo($title); echo"</p>"; 	}
else { echo"<p style='background: red'>Create event failed: "; echo($title); echo"</p>"; }
}
	
	

	





	echo"<hr>";
	echo"<br /><br />";
	
}

?>
<html>
<a href="../index.php">Back to BO HP</a>
</html>