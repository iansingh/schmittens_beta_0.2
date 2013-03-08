
<?php

require "files/header.php";	

dbconnect();

	//prepare eventtype variables
$party = "Party";
$concert = "Concert";
$stage = "Stage";
$exhibition = "Exhibition";
$other = "Other";

if(isset($_GET['eventtype']) == FALSE) 
	{
	// prepare eventlistquery ALL
//$eventlistquery = "SELECT * FROM `events` WHERE `starttime` > NOW() ORDER BY starttime,location_id";
$eventlistquery = "SELECT event_id, title, artist, starttime, events.type, l_name, events.location_id FROM `events` LEFT JOIN `location` ON `events`.location_id = `location`.location_id WHERE `starttime` > NOW() ORDER BY starttime,events.location_id LIMIT 1, 30";
//$eventlistquery = "SELECT event_id, title, artist, starttime, events.type, l_name, events.location_id FROM `events` LEFT JOIN `location` ON `events`.location_id = `location`.location_id WHERE `starttime` > NOW() ORDER BY starttime,events.location_id LIMIT '$low', '$high'";

$eventlistqueryresult = mysql_query($eventlistquery) or die ("<br />no eventlistquery");

if(mysql_num_rows($eventlistqueryresult) < 1) {
	$noevent = "No events found"; }

$eventlistqueryresult_array = mysql_fetch_assoc($eventlistqueryresult);
}

else {
	//prepare eventtype
if($_GET['eventtype'] == 0) {
	$eventtype = "";
	}
if($_GET['eventtype'] == 1) {
	$eventtype = "events.type = 'Party' AND";
	}
if($_GET['eventtype'] == 2) {
	$eventtype = "events.type = 'Concert' AND";
	}
if($_GET['eventtype'] == 3) {
	$eventtype = "events.type = 'Stage' AND";
	}
if($_GET['eventtype'] == 4) {
	$eventtype = "events.type = 'Exhibition' AND";
	}
if($_GET['eventtype'] == 5) {
	$eventtype = "events.type = 'Other' AND";
	}

// echo($eventtype);

// past or future

if($_GET['time'] == 0) {
	$time = "starttime < NOW()";
	$order = "starttime DESC LIMIT 50";
	}
if($_GET['time'] == 1) {
	$time = "starttime > NOW()";
	$order = "starttime ASC";
	}
	
// echo($time);
//$eventlistquery = "SELECT * FROM `events` WHERE $eventtype $time ORDER BY $order";
$eventlistquery = "SELECT event_id, title, artist, starttime, events.type, l_name, events.location_id FROM `events` LEFT JOIN `location` ON `events`.location_id = `location`.location_id WHERE $eventtype $time ORDER BY starttime,events.location_id LIMIT 1, 30";
//echo($eventlistquery);
$eventlistqueryresult = mysql_query($eventlistquery) or die ("<br />no eventlistquery");
if(mysql_num_rows($eventlistqueryresult) < 1) {
	$noevent = "No events found"; }
	

$eventlistqueryresult_array = mysql_fetch_assoc($eventlistqueryresult);


}



?>


<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>Schmittens - Event yourself!</title>
 </head>
 <body>
<?php include "files/nav.php"?>
<div class="columnleft">
<h1>Events</h1>
<div class="eventlistmenu">
<form action="eventlist.php" method="get" enctype="multipart/form-data">

<input type="radio" name="eventtype" value="1" /> Party
<input type="radio" name="eventtype" value="2" /> Concert
<input type="radio" name="eventtype" value="3" /> Stage
<input type="radio" name="eventtype" value="4" /> Art
<input type="radio" name="eventtype" value="5"/> Other
<input type="radio" name="eventtype" value="0" checked="checked" /> All
<input type="submit" name="list" value="List events" />
<input type="hidden" name="time" value="1" checked="checked" />
<!--
<br />
Upcoming events: <input type="radio" name="time" value="1" checked="checked" />
Past events: <input type="radio" name="time" value="0" /> -->

</form>
</div>

<div class="listnav">


</div>

<div class="eventlist">
<table class="evlist" >

<?php 
// set time format
$format = "g.i a, M j";

$c = 1;

if(isset($noevent) == TRUE) {
	echo($noevent);
	}
else {

do 
{ 
// prepare locationname-query
/*
$eventlocation_id = $eventlistqueryresult_array['location_id'];
$locationquery = "Select l_name FROM `location` WHERE location_id = $eventlocation_id";
$locationqueryresult = mysql_query($locationquery) ;

$locationqueryresult_array = mysql_fetch_assoc($locationqueryresult);
*/
// prepare username-query
/*
$creator = $eventlistqueryresult_array['created_by'];
$userquery = "Select user FROM `users` WHERE id = $creator";
$userqueryresult = mysql_query($userquery) ;
$userqueryresult_array = mysql_fetch_assoc($userqueryresult);
*/
?>
	<tr <?php if($c % 2 == 0) {echo"class='listitem1' "; }  else {echo"class='listitem2'  ";}?>  >
		<td style="width:160px; border-collapse: collapse;"><a href="event.php?event_id=<?php echo $eventlistqueryresult_array['event_id']; ?>" ><img src="/img/event/<?php echo($eventlistqueryresult_array['event_id']) ?>_160.jpg" alt="" ></td>
		<div class="eventlistentryinfo">
		<td style="padding-left: 5px; padding-right: 5px; vertical-align: top; border-collapse: collapse;"><b><a href="event.php?event_id=<?php echo $eventlistqueryresult_array['event_id']; ?>" ><?php echo $eventlistqueryresult_array['title']; ?></a></b> | <?php echo $eventlistqueryresult_array['type']; ?> 
		<br /><?php $phpdate = strtotime($eventlistqueryresult_array['starttime']); echo(date($format, $phpdate));  ?> @ <a href="location.php?location_id=<?php echo $eventlistqueryresult_array['location_id'] ?>" ><?php echo $eventlistqueryresult_array['l_name'];  ?></a>		
		<br /><?php $shortdescription = truncate($eventlistqueryresult_array['artist'], 40); echo($shortdescription);	?>
		<br /><?php $shortdescription = truncate($eventlistqueryresult_array['description'], 80); echo($shortdescription);	?> </td>
		</div>
		<td style="width:30px;"><div class="eventlistedit"><?php 
			if(($_SESSION['id']) == $creator) { ?>
			<a href="event.php?event_id=<?php echo $eventlistqueryresult_array['event_id'] ?>" >Edit</a> </div>
			<?php			
			}
			?>	
	</td></div>
	</tr>
<?php 
$c = $c + 1;

} while ($eventlistqueryresult_array = mysql_fetch_assoc($eventlistqueryresult));
}
?>
</table>	
</div>	</div>
	
<div class="columnright">

	<div class="ads">	</div>
</div>
	
<div class="footer">
<?php include"files/footer.php";  ?>
</div>

	
 </body>
 
 </html>