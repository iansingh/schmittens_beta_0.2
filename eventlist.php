<?php

require "files/header.php";	

dbconnect();


// prepare browsing urls

//var_dump($_GET);
$base_url = basename($_SERVER['PHP_SELF']);
$url = $base_url."?eventtype=".$_GET['eventtype']."&list=List+events&time=1&offset=";
//echo($url);

if(($_GET['offset'] == FALSE) || ($_GET['offset'] == 0)) {
	$low = 0;
	$high = 30;
	$offset = 30;
	$offsetupper = $offset + 30;
	//echo($offset);
	$fwurl = $url.$offset;
	$bwurl = "";
	}

else {
	$n = $_GET['offset'];
	$offset = $n + 30;
	$low = $n;
	$high = $offset;
	$offsetupper = $offset + 30;
	$offsetminus = $n - 30;
	//echo($offset);
	$fwurl = $url.$offset;
	$bwurl = $url.$offsetminus;
	}

if(isset($_GET['eventtype']) == FALSE) 
	{
		

	// prepare eventlistquery ALL
//$eventlistquery = "SELECT * FROM `events` WHERE `starttime` > NOW() ORDER BY starttime,location_id";
$eventlistquery = "SELECT event_id, title, artist, starttime, events.type, events.genre, events.stage_id, events.ex_id, l_name, events.location_id FROM `events` LEFT JOIN `location` ON `events`.location_id = `location`.location_id WHERE `starttime` > NOW() ORDER BY starttime,events.location_id LIMIT 1, 100";
//$eventlistquery = "SELECT event_id, title, artist, starttime, events.type, l_name, events.location_id FROM `events` LEFT JOIN `location` ON `events`.location_id = `location`.location_id WHERE `starttime` > NOW() ORDER BY starttime,events.location_id LIMIT '$low', '$high'";

$eventlistqueryresult = mysql_query($eventlistquery) or die ("<br />no eventlistquery");

if(mysql_num_rows($eventlistqueryresult) < 1) {
	$noevent = "No events found"; }

$eventlistqueryresult_array = mysql_fetch_assoc($eventlistqueryresult);

}

else {
	//prepare eventtype
if($_GET['eventtype'] == 0) {
	$eventquery = "SELECT event_id, title, artist, starttime, events.type, events.genre, events.stage_id, events.ex_id, l_name, events.location_id FROM `events` LEFT JOIN `location` ON `events`.location_id = `location`.location_id WHERE `starttime` > NOW() ORDER BY starttime,events.location_id LIMIT $low, $high";
	}
if($_GET['eventtype'] == 1) {
	$eventquery = "SELECT event_id, title, artist, starttime, events.type, events.genre, l_name, events.location_id FROM `events` LEFT JOIN `location` ON `events`.location_id = `location`.location_id WHERE `events`.type = 'Party' AND `starttime` > NOW() ORDER BY starttime,events.location_id LIMIT $low, $high";
	}
if($_GET['eventtype'] == 2) {
	$eventquery = "SELECT event_id, title, artist, starttime, events.type, events.genre, l_name, events.location_id FROM `events` LEFT JOIN `location` ON `events`.location_id = `location`.location_id WHERE `events`.type = 'Concert' AND `starttime` > NOW() ORDER BY starttime,events.location_id LIMIT $low, $high";
	}
if($_GET['eventtype'] == 3) {
	$eventquery = "SELECT event_id, title, artist, starttime, events.type, events.stage_id, events.genre, l_name, events.location_id FROM `events` LEFT JOIN `location` ON `events`.location_id = `location`.location_id WHERE `events`.type = 'Stage' AND `starttime` > NOW() ORDER BY starttime,events.location_id LIMIT $low, $high";
	}
if($_GET['eventtype'] == 4) {
	$eventquery = "SELECT event_id, title, artist, starttime, events.type, events.ex_id, events.genre, l_name, events.location_id FROM `events` LEFT JOIN `location` ON `events`.location_id = `location`.location_id WHERE `events`.type = 'Exhibition' AND `starttime` > NOW() ORDER BY starttime,events.location_id LIMIT $low, $high";
	}
if($_GET['eventtype'] == 5) {
	$eventquery = "SELECT event_id, title, artist, starttime, events.type, events.genre, l_name, events.location_id FROM `events` LEFT JOIN `location` ON `events`.location_id = `location`.location_id WHERE `events`.type = 'Other' AND `starttime` > NOW() ORDER BY starttime,events.location_id LIMIT $low, $high";
	}

//echo($_GET['eventtype']);

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
$eventlistquery = $eventquery;
//echo($eventlistquery);
$eventlistqueryresult = mysql_query($eventlistquery) or die ("<br />no eventlistquery");
if(mysql_num_rows($eventlistqueryresult) < 1) {
	$noevent = "No events found"; }
$n = mysql_num_rows($eventlistqueryresult);

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

<input type="radio" name="eventtype" value="1" <?php if($_GET['eventtype'] == 1) { echo"checked = 'checked'";}?>/> Party
<input type="radio" name="eventtype" value="2" <?php if($_GET['eventtype'] == 2) { echo"checked = 'checked'";}?>/> Concert
<input type="radio" name="eventtype" value="3" <?php if($_GET['eventtype'] == 3) { echo"checked = 'checked'";}?>/> Stage
<input type="radio" name="eventtype" value="4" <?php if($_GET['eventtype'] == 4) { echo"checked = 'checked'";}?>/> Art
<input type="radio" name="eventtype" value="5" <?php if($_GET['eventtype'] == 5) { echo"checked = 'checked'";}?>/> Other
<input type="radio" name="eventtype" value="0" <?php if($_GET['eventtype'] == 0) { echo"checked = 'checked'";}?>/> All
<input type="submit" name="list" value="List events" />
<input type="hidden" name="time" value="1" checked="checked" />
<!--
<br />
Upcoming events: <input type="radio" name="time" value="1" checked="checked" />
Past events: <input type="radio" name="time" value="0" /> -->

</form>
</div>


<div class="browse">

<?php if($_GET['offset'] > 29)  { ?>
<div style="float:left;"><a href="<?php echo($bwurl); ?>"><< Back</a></div>
<?php } ?>
<?php if($n == 30) {?>
<div style="float:right;"> <a href="<?php echo($fwurl); ?>">Forward >></a></div>
 <?php } ?>
</div>

<div class="eventlist">
<table class="evlist" >

<?php 
// set time format
$format = "g.i a, M j";
$dateformat = "M j";
$timeformat = "g.i a";

$c = 1;

if(isset($noevent) == TRUE) {
	echo($noevent);
	}
else {

do 
{ 

// prepare links (events, exhibition, stage)

$link = "event.php?event_id=".$eventlistqueryresult_array['event_id'];
if($eventlistqueryresult_array['ex_id'] != 0) { $link = "exhibition.php?ex_id=".$eventlistqueryresult_array['ex_id']; }
if($eventlistqueryresult_array['stage_id'] != 0) { $link = "stage.php?stage_id=".$eventlistqueryresult_array['stage_id']; } 


?>
	<tr <?php if($c % 2 == 0) {echo"class='listitem1' "; }  else {echo"class='listitem2'  ";}?>  >
		<td style="width:160px; border-collapse: collapse;">		
		<a href="<?php echo($link); ?>" ><img src="/img/event/<?php echo($eventlistqueryresult_array['event_id']) ?>_160.jpg" alt="" ></td>
		<div class="eventlistentryinfo">
		<td style="padding-left: 5px; padding-right: 5px; vertical-align: top; border-collapse: collapse;"><b><a href="<?php echo($link); ?>" ><?php echo $eventlistqueryresult_array['title']; ?></a></b> <br /> <?php echo $eventlistqueryresult_array['type']; echo" - "; echo(getgenrename($eventlistqueryresult_array['genre']));?> 
		<br /><?php $phpdate = strtotime($eventlistqueryresult_array['starttime']); echo(date($format, $phpdate)); ?>  @ <a href="location.php?location_id=<?php echo $eventlistqueryresult_array['location_id'] ?>" ><?php echo $eventlistqueryresult_array['l_name'];  ?></a>		
		<br /><?php $shortdescription = truncate($eventlistqueryresult_array['artist'], 40); echo($shortdescription);	?>
		<?php if($eventlistqueryresult_array['artist'] != "") {?><br /><?php } ?>
		<?php $shortdescription = truncate($eventlistqueryresult_array['description'], 80); echo($shortdescription);	?> </td>
		</div>
		<td style="width:30px;"><div class="eventlistedit"><?php 
			if(($_SESSION['id']) == $creator) { ?>
			<a href="<?php echo($link); ?>	" >Edit</a> </div>
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
</div>	

<div class="browse">

<?php if($_GET['offset'] > 29)  { ?>
<div style="float:left;"><a href="<?php echo($bwurl); ?>"><< Back</a></div>
<?php } ?>
<?php if($n == 30) {?>
<div style="float:right;"> <a href="<?php echo($fwurl); ?>">Forward >></a></div>
 <?php } ?>
</div>

</div>
	
<div class="columnright">

	<div class="ads">	</div>
</div>
	
<div class="footer">
<?php include"files/footer.php";  ?>
</div>

	
 </body>
 
 </html>