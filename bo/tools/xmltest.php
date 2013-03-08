<?php 
	session_start();
	require "files/functions.php";
	require "files/include.php";	
	require "files/datetimepicker.php";

dbconnect();

echo "xml test";
echo"<br /><br />";

//$ev_array = array();




$xml = simplexml_load_file('resources/ws.audioscrobbler.com.xml');



foreach ($xml->events->event as $event) {
//	echo $event->title, "<br />";
//	echo $event->artists->headliner, "<br />";
	foreach ($event->artists->artist as $artist) {
		echo ($artist);
		echo "<br />";
		}
//	echo $event->startDate, "<br />";
//	echo $event->venue->name, "<br />";
	echo"<br />";
	}





foreach ($xml->events->event->children() as $key=>$val) {
	$ev_array[$key] = (string)$val;
	}
$artist_array = array();
foreach ($xml->events->event->artists->artist as $i=>$artist) {
	array_push($artist_array, (string)$artist);
	}
foreach ($xml->events->event->venue->children() as $key=>$val) {
	$venue_array[$key] = (string)$val;
	}	
foreach ($xml->events->event->venue->location->children() as $key=>$val) {
	$location_array[$key] = (string)$val;
	}

	

	echo"<br />ev_array:<br />";
	print_r($ev_array);
	echo"<br />artist_array:<br />";
	print_r($artist_array);
	echo"<br />venue_array:<br />";
	print_r($venue_array);
	echo"<br />location_array:<br />";
	print_r($location_array);
	echo"<br /> <br />";


foreach ($xml->events->event as $event) {
	
//prepare variables for locationquery
$lqname = $venue_array['name'];
$lqpostal = $location_array['postalcode'];
//prepare & run locationquery
$lq = "SELECT `location_id` FROM `location` WHERE l_name LIKE '%$lqname%' AND postalcode = '$lqpostal'";
$result = mysql_query($lq);
echo($lq);
echo"<br />";
//check how many results were found & prepare switch
$nl = mysql_num_rows($result);
if($nl > 1) { echo ($nl); echo" matching locations found - ERROR! <br />"; $switch1 = 2; }
if($nl == 1) { echo"Matching location found, checking event: "; echo($lqname); echo"<br />"; $switch1 = 1;  }
if($nl == 0) { echo"No matching location found, location will be created <br />"; $switch1 = 0; }

//switch1 = 0 - create location and print confirmation
	//prepare variables
if($switch1 == 0) {
$l_name = $venue_array['name'];
preg_match('/[0-9]+/',$location_array['street'],$match);
$streetnumber = $match[0];
//echo"<br />";
//echo($streetnumber);
//echo"<br />";
preg_match('/\s.[A-Za-z ]+/',$location_array['street'],$match);
$street = $match[0];
//echo($street);
$postalcode = $location_array['postalcode'];
$city = $location_array['city'];
if($city == "") { $city = "Montreal"; }
//echo($city);
$province = "QC";
$url = $venue_array['website'];
$type = "Concert";
$source = "last.fm";

$cl = 	"INSERT INTO `location` 
			(`l_name`, `street`, `streetnumber`, `postalcode`, `city`, `province`, 
			`url`, `type`, `created_by`, `creation`, `source`)
			VALUES('$l_name', '$street', '$streetnumber', '$postalcode', '$city', '$province',
			'$url', '$type', '1', NOW(), '$source')";
echo($cl);

$clq = mysql_query($cl);
if($clq == TRUE) { echo"<br/>New location created: "; echo($l_name); echo"<br />"; 	}
else { echo"<br />Create location failed: "; echo($l_name); echo"<br />"; }

$switch1 = 1;
}

//switch1 = 1 - create event (also do this step after switch1 = 0) and print confirmation
if($switch1 == 1) {
//get location-id
$l_name = $venue_array['name'];
$lidq = "SELECT location_id FROM `location` WHERE l_name = '$l_name'";
//echo($lidq);
$result = mysql_query($lidq);
$lidq_array = mysql_fetch_assoc($result);
$location_id = $lidq_array['location_id'];


//prepare starttime
$st = $ev_array['startDate'];
$starttime = date("Y-m-d G:i:s:00", strtotime($st));
$datecheck = date("Y-m-d 00:00:00:01", strtotime($st));
$datecheck2 = strtotime('+1 day', strtotime($st));
$datecheck2 = date("Y-m-d 00:00:00:01", $datecheck2);
//echo($datecheck2);

//prepare artist
$artist = implode(", ", $artist_array);

//prepare variables
$source_id = $ev_array['id'];
$title = $ev_array['title'];
$description = $ev_array['description'];
$type = 'Concert';
$created_by = '1';
$source_url = $ev_array['url'];
$source = "last.fm";
//check if event already exists

$ec = "SELECT event_id FROM `events` WHERE location_id = '$location_id' AND starttime > '$datecheck' AND starttime < '$datecheck2'";
//echo($ec);
$result = mysql_query($ec);
$ne = mysql_num_rows($result);
	//if no match found - create event and notify
	if($ne == 0) { echo"No matching events found, event will be created <br />"; $switch2 = 0; }
	//if 1 match found - abort and notify
	if($ne == 1) {echo"Matching event found, aborted: "; echo($title); echo"<br />"; $switch2 = 1; }
	//if >1 match found - abort and ERROR
	if($ne > 1) { decho($nl); echo" matching events found, aborted, ERROR! <br />"; $switch2 = 2; }
	
	//write event to db
	if($switch2 == 0) {
	$ew = 	"INSERT INTO `events` 
				(`location_id`, `title`, `artist`, 
				`type`, `starttime`, `description`, `created_by`, `created`, 
				`source`, `source_id`, `source_url`) 
				VALUES 
				('$location_id', '$title', '$artist',
				'$type', '$starttime', '$description', '$created_by', NOW(),
				'$source', '$source_id', '$source_url')";	
	//echo"<br />";
	//echo($ew);
	
	$ewq = mysql_query($ew);
if($ewq == TRUE) { echo"<br/>New event created: "; echo($title); echo"<br />"; 	}
else { echo"<br />Create event failed: "; echo($title); echo"<br />"; }

	
	}

	
}


//switch1 = 2 - create error report and print
if($switch1 == 2) {
	echo"<br />";
	echo"ERROR: ";
	echo($nl);
	echo" locations found! Source-ID of Event: ";
	echo($ev_array['id']);
	echo"<br />Source-Name of Location: ";
	echo($venue_array['name']);
	echo"<br /> No event created for this id.";
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
	
	$counter = $counter -1;	
}

// find location id
	//check if location is already in db
	//if yes get location_id
	//if not create location
	//search for new location to get location_id
	//create log entry with link


// loop through ev_array

	// check if event already exists
	//if yes create log entry with link
	
	// if event doesnt exist yet insert	



?>