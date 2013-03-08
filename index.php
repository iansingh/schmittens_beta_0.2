<?php
	
	// done in header.php
	// session_start();

	require "files/header.php";
	
	// done in header.php	
	// require "files/include.php";	
	
	//prepare eventtype variables

dbconnect();	
	
//var_dump($_SESSION);

if(isset($_POST['eventtype']) == FALSE) 
	{
	$eventtype = "";
	}
else {
	//prepare eventtype
if($_POST['eventtype'] == 0) {
	$eventtype = "";
	}
if($_POST['eventtype'] == 1) {
	$eventtype = "type = 'Party' AND";
	$evt = "Party";
	}
if($_POST['eventtype'] == 2) {
	$eventtype = "type = 'Concert' AND";
	$evt = "Concert";
	}
if($_POST['eventtype'] == 3) {
	$eventtype = "type = 'Stage' AND";
	$evt = "Stage";
	}
if($_POST['eventtype'] == 4) {
	$eventtype = "type = 'Exhibition' AND";
	$evt = "Art";
	}
if($_POST['eventtype'] == 5) {
	$eventtype = "type = 'Other' AND";
	$evt = "";
	}
}
// NOT IMPLEMENTED
if(isset($_POST['day']) == FALSE)
	{
	$time = "starttime > NOW()";
	}
if(isset($_POST['day']) == 0)
	{
	$time = "starttime > NOW()";
	}	
	
// echo($time);

// get info for top event
$ev_top = "SELECT event_id, stage_id, ex_id, title, starttime, artist, events.type, events.genre, l_name, events.location_id, price_min, price_max, price_free FROM `events` LEFT JOIN `location` ON `events`.location_id = `location`.location_id WHERE events.$eventtype $time AND img_present = 1 ORDER BY starttime,events.location_id LIMIT 1";
$ev_top_r = mysql_query($ev_top) or die ("<br />no eventlistquery");
$eta = mysql_fetch_assoc($ev_top_r);

$link_top = "event.php?event_id=".$eta['event_id'];
if($eta['ex_id'] != 0) { $link_top = "exhibition.php?ex_id=".$eta['ex_id']; }
if($eta['stage_id'] != 0) { $link_top = "stage.php?stage_id=".$eta['stage_id']; } 
//echo($link);

//print_r($eta);
//$topeventlocation = $eta['location_id'];
//echo($eta['starttime']);

// get location for top event

$switch = 0; 
if(mysql_num_rows($ev_top_r) > 0) {
/*
$tel = "Select l_name FROM `location` WHERE location_id = $topeventlocation";
$telresult = mysql_query($tel) or die ("<br />No such location found.");
$telresult_array = mysql_fetch_assoc($telresult);
*/
$switch = 1; }

// set dateformat for top event
$format_top = "g.i a D, M j";


// get info for other events
$switch2 = 0;

$eventlistquery = "SELECT event_id, stage_id, ex_id, title, starttime, events.type, events.genre, l_name, events.location_id FROM `events` LEFT JOIN `location` ON `events`.location_id = `location`.location_id WHERE events.$eventtype $time AND img_present = 1 ORDER BY starttime,events.location_id LIMIT 1, 10";
//echo($eventlistquery);
$eventlistqueryresult = mysql_query($eventlistquery) or die ("<br />no eventlistquery2");
$eventlistqueryresult_array = mysql_fetch_assoc($eventlistqueryresult);
//$evtype = $eventlistqueryresult_array['type'];
//print_r($eventlistqueryresult_array) ;
if(mysql_num_rows($eventlistqueryresult) > 0) {
$switch2 = 1;
}

?>	
	


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<html xmlns:fb="http://ogp.me/ns/fb#">
 <head>
  <title>Schmittens - Event yourself!</title>
   <meta property="og:title" content="Schmittens - Event yourself!" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="http://www.schmittens.net" />
	<meta property="og:image" content="" />
	<meta property="og:site_name" content="Schmittens - Event yourself!" />
	<meta property="fb:admins" content="558392998" />
 </head>
 <body>
<!-- Facebook -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>


<div class="">
<?php include "files/nav.php" ?>
</div>

<div id="note" class="topnotify"">

    <span id="s1" class="topnotify">Schmittens.net is in public beta! <b>Found a bug?</b> Please <a href='mailto:support@schmittens.net'>tell us</a> about it! Thank you for your help.</span>
    <span id="s2" class="topnotify"><b>Welcome to Schmittens.net!</b> <a href='signup.php'>Sign up</a> to create your own events, or just browse what's going on.</span>
    <span id="s3" class="topnotify"><b>Schmittens.net is currently in beta!</b> Any help is highly appreciated! <a href='mailto:contact@schmittens.net'>Just drop us a line.</a></span>


</div>
<!--
<div class="eventlistmenu" >

<form action="index.php" method="post" enctype="multipart/form-data">

<input type="radio" name="eventtype" value="1" />Party 
<input type="radio" name="eventtype" value="2" />Concert 
<input type="radio" name="eventtype" value="3" />Stage 
<input type="radio" name="eventtype" value="4" />Art 
<input type="radio" name="eventtype" value="5"/>Other 

<input type="radio" name="eventtype" value="0" checked="checked" />All 
<input type="submit" name="list" value="Go!" />
</form>



</div>
-->

<div class="columnleft_index">
<?php 
if($switch == 1) { ?>
<div class="topevent">
	<div class="topeventpicture" style="cursor:pointer ; background-image:url('img/event/<?php echo($eta['event_id']) ?>_480.jpg'); background-color: #333; width: 480px; height: 270px; " onclick="document.location='<?php echo $link_top; ?>'">
		
		<div class="topeventtitle"><a href="event.php?event_id=<?php echo $eta['event_id']; ?>"><?php echo $eta['title']; ?></a>
			<div style="font-size: 60%;"><?php echo $eta['type']; ?> - <?php echo(getgenrename($eta['genre'])); ?></div>		
		</div>
		<div class="topeventinfo">
		
			<i><?php $phpdate = strtotime($eta['starttime']); echo(date($format_top, $phpdate));  ?></i>
			@ <a href="location.php?location_id=<?php echo $eta['location_id'] ?>" ><b><?php echo($eta['l_name']); ?></b></a> <i>
			<?php if($eta['price_free'] == 1) { echo" | free"; }?>
			<?php if($eta['price_min'] != '0') { echo" | "; echo($eta['price_min']);}
				if($eta['price_max'] != '0') { echo" - "; echo($eta['price_max']);}
				if($eta['price_min'] != '0') { echo"$"; }?>	
			</i>
			<br />
			<?php $shortartist = truncate($eta['artist'], 50); echo($shortartist);	?>
			</a>
		
		
		</div>
	</div>
</div>
<?php } ?>



<div class="frontpageevents">
<table>
<tr>

<?php 

// rest of events

if($switch2 == 1) {
$a = 0;

do 
{ 

$link_main = "event.php?event_id=".$eventlistqueryresult_array['event_id'];
if($eventlistqueryresult_array['ex_id'] != 0) { $link_main = "exhibition.php?ex_id=".$eventlistqueryresult_array['ex_id']; }
if($eventlistqueryresult_array['stage_id'] != 0) { $link_main = "stage.php?stage_id=".$eventlistqueryresult_array['stage_id']; } 
//echo($link);
// prepare locationname-query
/*
$eventlocation_id = $eventlistqueryresult_array['location_id'];
$locationquery = "Select l_name FROM `location` WHERE location_id = $eventlocation_id";
$locationqueryresult = mysql_query($locationquery) or die ("<br />No locationquery.");
$locationqueryresult_array = mysql_fetch_assoc($locationqueryresult);
*/

// prepare username-query
/*
$creator = $eventlistqueryresult_array['created_by'];
$userquery = "Select user FROM `users` WHERE id = $creator";
$userqueryresult = mysql_query($userquery) or die ("<br />no userquery");
$userqueryresult_array = mysql_fetch_assoc($userqueryresult);
*/


$a = $a + 1;

// set date format
$format = "g.i a, M j";

	
?>  
	<td>

<div class="mainpageevent">
	<div class="mainpageeventpicture" style="cursor:pointer ; background-image:url('img/event/<?php echo($eventlistqueryresult_array['event_id']) ?>_240.jpg'); background-color: #333; width: 240px; height: 135px; " onclick="document.location='<?php echo($link_main); ?>'">
		<div class="mainpageeventtitle">
		<b> <a href="<?php echo($link_main); ?>" ><?php $shorttitle = truncate($eventlistqueryresult_array['title'], 15); echo($shorttitle); ?></a>  </b>

		<div style="font-size: 70%;"><?php echo($eventlistqueryresult_array['type']); ?> - <?php echo(getgenrename($eventlistqueryresult_array['genre'])); ?></div>
		</div>
		<div class="mainpageeventinfo" >
			<i><?php $phpdate = strtotime($eventlistqueryresult_array['starttime']); echo(date($format, $phpdate));  ?> </i>  <br />
			@ <a href="location.php?location_id=<?php echo $eventlistqueryresult_array['location_id'] ?>" ><?php $shortlocation = truncate($eventlistqueryresult_array['l_name'], 15); echo($shortlocation);  ?></a> 
		</div>
	</div>
</div>
	</td>
<?php 
if($a == 2) {
	echo"</tr><tr>";
	}
if(($a % 2) == 0) {
	echo"</tr><tr>";
	}
?>
<?php	
	
} while ($eventlistqueryresult_array = mysql_fetch_assoc($eventlistqueryresult));
}

?>


</tr>
</table>

</div>



<?php 
$disable = 1;
if($disable == 0) {
$format = "D M j, G.i"; 
?>

<!-- Party -->

<div class="mainpagelistingsbygenretop">
<p class="mainpagelistingbygenretitle">More Parties...</p>
<?php 
$party = "SELECT * FROM `events` LEFT JOIN `location` ON `events`.location_id = `location`.location_id WHERE events.type = 'Party' AND starttime > NOW() ORDER BY starttime ASC LIMIT 0, 10";
//echo($concert);
$partyresult = mysql_query($party);
$party_array = mysql_fetch_assoc($partyresult);

if(mysql_num_rows($partyresult) != 0) {
do { 

/*
// prepare locationname-query
$eventlocation_id = $party_array['location_id'];
//echo"<br />eventlocation:";
//echo($eventlocation_id);
$locationquery = "Select l_name FROM `location` WHERE location_id = $eventlocation_id";
$locationqueryresult = mysql_query($locationquery) or die ("<p>No events found</p>");
$locationqueryresult_array = mysql_fetch_assoc($locationqueryresult);
?>
*/
?>

<div class="eventlist_small">
<b><a href="event.php?event_id=<?php echo($party_array['event_id']); ?>" ><?php echo($party_array['title']); ?></a></b><br /> 
<?php $phpdate = strtotime($party_array['starttime']); echo(date($format, $phpdate));  ?>  @ <a href="location.php?location_id=<?php echo($party_array['location_id']); ?>"><?php echo($party_array['l_name']); ?></a>
</p>
</div>
<?php 
} while ($party_array = mysql_fetch_assoc($party));
?>
<a href="/eventlist.php?eventtype=1&time=1&list=List+events">Even more...</a>
<?php } else { echo"<div class='eventlist_small'>Sorry, no events to show!</div>"; } ?>
</div>

<!-- Concert -->

<div class="mainpagelistingsbygenretop">
<p class="mainpagelistingbygenretitle">More Concerts...</p>
<?php 
$concert = "SELECT * FROM `events` LEFT JOIN `location` ON `events`.location_id = `location`.location_id WHERE events.type = 'Concert' AND starttime > NOW() ORDER BY starttime ASC LIMIT 0, 10";
//echo($concert);
$concertresult = mysql_query($concert);
$concert_array = mysql_fetch_assoc($concertresult);

if(mysql_num_rows($concertresult) != 0) {
do {
	
/*
// prepare locationname-query
$eventlocation_id = $concert_array['location_id'];
//echo"<br />eventlocation:";
//echo($eventlocation_id);
$locationquery = "Select l_name FROM `location` WHERE location_id = $eventlocation_id";
$locationqueryresult = mysql_query($locationquery) or die ("<p>No events found</p>");
$locationqueryresult_array = mysql_fetch_assoc($locationqueryresult);
*/
?>

<div class="eventlist_small">
<b><a href="event.php?event_id=<?php echo($concert_array['event_id']); ?>" ><?php echo($concert_array['title']); ?></a></b><br /> 
<?php $phpdate = strtotime($concert_array['starttime']); echo(date($format, $phpdate));  ?>  @ <a href="location.php?location_id=<?php echo($concert_array['location_id']); ?>"><?php echo($concert_array['l_name']); ?></a>
</p>
</div>
<?php 
} while ($concert_array = mysql_fetch_assoc($concertresult));
?>
<a href="/eventlist.php?eventtype=2&time=1&list=List+events">Even more...</a>
<?php } else { echo"<div class='eventlist_small'>Sorry, no events to show!</div>"; } ?>
</div>


<!-- Stage -->

<div class="mainpagelistingsbygenre">
<p class="mainpagelistingbygenretitle">More Stage...</p>
<?php 
$stage = "SELECT * FROM `events` LEFT JOIN `location` ON `events`.location_id = `location`.location_id WHERE events.type = 'Stage' AND starttime > NOW() ORDER BY starttime ASC LIMIT 0, 10";
$stageresult = mysql_query($stage);
$stage_array = mysql_fetch_assoc($stageresult);

if(mysql_num_rows($stageresult) != 0) {
do {
	
/*
// prepare locationname-query
$eventlocation_id = $stage_array['location_id'];
//echo"<br />eventlocation:";
//echo($eventlocation_id);
$locationquery = "Select l_name FROM `location` WHERE location_id = $eventlocation_id";
$locationqueryresult = mysql_query($locationquery) or die ("<p>No events found</p>");
$locationqueryresult_array = mysql_fetch_assoc($locationqueryresult);
*/
?>

<div class="eventlist_small">
<b><a href="stage.php?stage_id=<?php echo($stage_array['stage_id']); ?>" ><?php echo($stage_array['title']); ?></a></b><br /> 
<?php $phpdate = strtotime($stage_array['starttime']); echo(date($format, $phpdate));  ?> <br /> @ <a href="location.php?location_id=<?php echo($stage_array['location_id']); ?>"><?php echo($stage_array['l_name']); ?></a>
</p>
</div>
<?php 
} while ($stage_array = mysql_fetch_assoc($stageresult));
?>
<a href="/eventlist.php?eventtype=3&time=1&list=List+events">Even more...</a>
<?php } else { echo"<div class='eventlist_small'>Sorry, no events to show!</div>"; } ?>
</div>

<!-- Art -->

<div class="mainpagelistingsbygenre">
<p class="mainpagelistingbygenretitle">More Artshows...</p>
<?php 
$art = "SELECT * FROM `events` LEFT JOIN `location` ON `events`.location_id = `location`.location_id WHERE events.type = 'Exhibition' AND starttime > NOW() ORDER BY starttime ASC LIMIT 0, 10";
$artresult = mysql_query($art);
$art_array = mysql_fetch_assoc($artresult);

if(mysql_num_rows($artresult) != 0) {
do {

/*
// prepare locationname-query
$eventlocation_id = $art_array['location_id'];
//echo"<br />eventlocation:";
//echo($eventlocation_id);
$locationquery = "Select l_name FROM `location` WHERE location_id = $eventlocation_id";
$locationqueryresult = mysql_query($locationquery) or die ("<p>No events found</p>");
$locationqueryresult_array = mysql_fetch_assoc($locationqueryresult);
*/
?>

<div class="eventlist_small">
<b><a href="exhibition.php?ex_id=<?php echo($art_array['ex_id']); ?>" ><?php echo($art_array['title']); ?></a></b><br /> 
<?php $phpdate = strtotime($art_array['starttime']); echo(date($format, $phpdate));  ?> <br /> @ <a href="location.php?location_id=<?php echo($art_array['location_id']); ?>"><?php echo($art_array['l_name']); ?></a>
</p>
</div>
<?php 
} while ($art_array = mysql_fetch_assoc($artresult));
?>
<a href="/eventlist.php?eventtype=3&time=1&list=List+events">Even more...</a>
<?php } else { echo"<div class='eventlist_small'>Sorry, no events to show!</div>"; } ?>
</div>

<!-- Other -->

<div class="mainpagelistingsbygenre">
<p class="mainpagelistingbygenretitle">More misc shows...</p>
<?php 
$other = "SELECT * FROM `events` LEFT JOIN `location` ON `events`.location_id = `location`.location_id WHERE events.type = 'Other' AND starttime > NOW() ORDER BY starttime ASC LIMIT 0, 10";
$otherresult = mysql_query($other);
$other_array = mysql_fetch_assoc($otherresult);

if(mysql_num_rows($otherresult) != 0) {
do {
	
/*
// prepare locationname-query
$eventlocation_id = $other_array['location_id'];
//echo"<br />eventlocation:";
//echo($eventlocation_id);
$locationquery = "Select l_name FROM `location` WHERE location_id = $eventlocation_id";
$locationqueryresult = mysql_query($locationquery) or die ("<p>No events found</p>");
$locationqueryresult_array = mysql_fetch_assoc($locationqueryresult);
*/
?>

<div class="eventlist_small">
<b><a href="event.php?event_id=<?php echo($other_array['event_id']); ?>" ><?php echo($other_array['title']); ?></a></b><br /> 
<?php $phpdate = strtotime($other_array['starttime']); echo(date($format, $phpdate));  ?>  
<br /> @ <a href="location.php?location_id=<?php echo($other_array['location_id']); ?>"><?php echo($other_array['l_name']); ?></a>
</p>
</div>
<?php 
} while ($other_array = mysql_fetch_assoc($otherresult));
?>
<a href="/eventlist.php?eventtype=3&time=1&list=List+events">Even more...</a>
<?php } else { echo"<div class='eventlist_small'>Sorry, no events to show!</div>"; } ?>
</div>

<?php } ?>

</div>



<div class="columnright">

<div class="socialmedia">
<div class="fb-like" data-href="http://www.schmittens.net" data-send="true" data-layout="button_count" data-width="240" data-show-faces="false"></div>
<!-- <a href="https://twitter.com/SchmittensEvent" class="twitter-follow-button" data-show-count="false">Follow @SchmittensEvent</a>
<div class="twitterfollow"><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></div>
</div>-->
<!-- <div class="messagebox">Schmittens sez...</div> -->
<div class="twitter">
<a href="https://twitter.com/SchmittensEvent" class="twitter-follow-button" data-show-count="false" data-dnt="true">Follow @SchmittensEvent</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
</div>
</div>
<div>
<h1>Upcoming <?php if((isset($_POST['crlist']) == TRUE)) { echo" - "; echo($_POST['fplet']); } ?></h1>
<?php 

if((isset($_POST['crlist']) == FALSE)) {
	//echo"standard";
	$cutoff_day = date('o-m-d 04:00:00',strtotime('+5 days'));
	//echo($cutoff_day);
	$crlist_query = "SELECT event_id, stage_id, ex_id, title, starttime, events.type, events.genre, events.location_id, location.l_name FROM `events` LEFT JOIN `location` ON `events`.location_id = `location`.location_id WHERE starttime > NOW() AND starttime < '$cutoff_day' ORDER BY starttime LIMIT 10";
	//echo($crlist_query);
	}

if(($_POST['crlist'] == 1) && ($_POST['fplet'] == "All")) {
	$crlist_day = date('o-m-d H:i:s',strtotime($_POST['fpld']));
	$cutoff = $_POST['fpld'];
	//echo($cutoff);
	$cutoff_day = date('o-m-d 04:00:00', strtotime(date('o-m-d H:i:s', strtotime($cutoff)) . " +1 day"));
	$crlist_query = "SELECT event_id, stage_id, ex_id, title, starttime, events.type, events.genre, events.location_id, location.l_name FROM `events` LEFT JOIN `location` ON `events`.location_id = `location`.location_id WHERE starttime > '$crlist_day' AND starttime < '$cutoff_day' ORDER BY starttime ASC";
	//echo($crlist_query);
	}

if(($_POST['crlist'] == 1) && ($_POST['fplet'] != "All")) {
	$crl_type = $_POST['fplet'];
	$crlist_day = date('o-m-d H:i:s',strtotime($_POST['fpld']));
	$cutoff = $_POST['fpld'];
	//echo($cutoff);
	$cutoff_day = date('o-m-d 04:00:00', strtotime(date('o-m-d H:i:s', strtotime($cutoff)) . " +1 day"));
	$crlist_query = "SELECT event_id, stage_id, ex_id, title, starttime, events.type, events.genre, events.location_id, location.l_name FROM `events` LEFT JOIN `location` ON `events`.location_id = `location`.location_id WHERE events.type = '$crl_type' AND starttime > '$crlist_day' AND starttime < '$cutoff_day' ORDER BY starttime ASC";
	//echo($crlist_query);
	}
//echo($crlist_query);
$crlist_cutoff = "crlist_cutoff";


//echo($crlist_query);
$crlist_result = mysql_query($crlist_query);
$crlist_array = mysql_fetch_array($crlist_result);
//var_dump($crlist_array);

$date=$_POST['fpld'];
$postdate = date('l',strtotime($date));


//var_dump($_POST);

$d = date('l',strtotime('today'));
$d1 = date('l',strtotime('today + 1 days'));
$d2 = date('l',strtotime('today + 2 days'));
$d3 = date('l',strtotime('today + 3 days'));
$d4 = date('l',strtotime('today + 4 days'));


?>


<div class="form">
<form method="post" enctype="multipart/form-data" action="index.php">
<select name="fplet" STYLE="width: 80px">
<option value="All" <?php if(($_POST['fplet']) == 'All') {echo"selected = 'selected'";}?> >All</option>
<option value="Party" <?php if(($_POST['fplet']) == 'Party') {echo"selected = 'selected'";}?> >Party</option>
<option value="Concert" <?php if(($_POST['fplet']) == 'Concert') {echo"selected = 'selected'";}?> >Concert</option>
<option value="Exhibition	" <?php if(($_POST['fplet']) == 'Art') {echo"selected = 'selected'";}?> >Art</option>
<option value="Stage" <?php if(($_POST['fplet']) == 'Stage') {echo"selected = 'selected'";}?> >Stage</option>
<option value="Other" <?php if(($_POST['fplet']) == 'Other') {echo"selected = 'selected'";}?> >Other</option>
</select>
<select name="fpld" STYLE="width: 100px">
<option value="<?php echo date('o-m-d H:i:s',time()); ?>" <?php if($d == $postdate) {echo"selected = 'selected'";} ?> ><?php echo($d); ?></option>
<option value="<?php echo date('o-m-d H:i:s',strtotime('now + 1 days')); ?>" <?php if($d1 == $postdate) {echo"selected = 'selected'";} ?> ><?php echo($d1); ?></option>
<option value="<?php echo date('o-m-d H:i:s',strtotime('now + 2 days')); ?>" <?php if($d2 == $postdate) {echo"selected = 'selected'";} ?> ><?php echo($d2); ?></option>
<option value="<?php echo date('o-m-d H:i:s',strtotime('now + 3 days')); ?>" <?php if($d3 == $postdate) {echo"selected = 'selected'";} ?> ><?php echo($d3); ?></option>
<option value="<?php echo date('o-m-d H:i:s',strtotime('now + 4 days')); ?>" <?php if($d4 == $postdate) {echo"selected = 'selected'";} ?> ><?php echo($d4); ?></option>
</select>
<input type="hidden" name="crlist" value="1" />	
<input type="submit" name="crel" value="Go!" />
</form>
</div>

<div class="eventlist_small">


<?php 
$formatqv = "D G.i";

do {
/*
// prepare locationname-query
$eventlocation_id = $crlist_array['location_id'];
//echo"<br />eventlocation:";
//echo($eventlocation_id);
$locationquery = "Select l_name FROM `location` WHERE location_id = $eventlocation_id";
$locationqueryresult = mysql_query($locationquery) or die ("<p>No events found</p>");
$locationqueryresult_array = mysql_fetch_assoc($locationqueryresult);
*/   

//construct eventlinks depending on type

$link = "event.php?event_id=".$crlist_array['event_id'];
if($crlist_array['ex_id'] != 0) { $link = "exhibition.php?ex_id=".$crlist_array['ex_id']; }
if($crlist_array['stage_id'] != 0) { $link = "stage.php?stage_id=".$crlist_array['stage_id']; } 
//echo($link);

if(isset($crlist_array['title'])) {

?>

<p class="eventlist_small">
<b><a href="<?php echo($link); ?>" ><?php echo($crlist_array['title']); ?></a></b><br /><?php echo($crlist_array['type']); ?> - <?php echo(getgenrename($crlist_array['genre'])); ?><br /> 
<?php $phpdate = strtotime($crlist_array['starttime']); echo(date($formatqv, $phpdate));  ?>  @ <a href="location.php?location_id=<?php echo($crlist_array['location_id']); ?>"><?php echo($crlist_array['l_name']); ?></a>
</p>
<?php }

else { echo"<p class='eventlist_small'>No events found.</p>";}

} while ($crlist_array = mysql_fetch_assoc($crlist_result));

?>

</div>
</div>
</div>


<div class="footer">
<?php include"files/footer.php";  ?>
</div>
</div>
</body>
