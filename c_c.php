<?php
	session_start();
	 header('Content-type: text/html; charset=utf-8');
	require "files/functions.php";
	require "files/include.php";	
	require "files/datetimepicker.php";

dbconnect();

$location_id = $_GET["location_id"];

// set variables for concert events
$etype = 2000;
$eventtype = "Concert";

$upperlimit = $etype + 1000;


//get styles from database

$stylesquery = "SELECT * FROM `styles` WHERE type_id >= '$etype' AND type_id < '$upperlimit' ORDER BY type_id ASC";
$stylesqueryresult = mysql_query($stylesquery) or die("no stylesquery");
$stylesqueryresult_array = mysql_fetch_assoc($stylesqueryresult);


	
if(isset($_POST['ce']) == 1) {	
	
	// debug
	// var_dump($_POST);
	
	//prepare variables
	
	$title = mysql_real_escape_string($_POST["title"]);
	$urlevent = mysql_real_escape_string($_POST["url"]);
	$artist = mysql_real_escape_string($_POST["artist"]);
	$description = mysql_real_escape_string($_POST["description"]);
	$location_id = mysql_real_escape_string($_POST["location_id"]);
	$price_min = mysql_real_escape_string($_POST["price_min"]);
	$price_max = mysql_real_escape_string($_POST["price_max"]);
	$ticket_url = mysql_real_escape_string($_POST["ticket_url"]);
	$youtube_pre = mysql_real_escape_string($_POST["youtube"]);
	$genre = mysql_real_escape_string($_POST["eventstyle"]);
	$verification = $_SESSION['verification'];
	$eventuser = $_SESSION['id'];

	//extract($_POST);
	
	// get youtube video id
	
	preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $youtube_pre, $matches);
	$youtube = $matches[0];
	
	// prepare date
	$month_s = mysql_real_escape_string($_POST["month_s"]);
	$day_s = mysql_real_escape_string($_POST["day_s"]);
	$year_s = mysql_real_escape_string($_POST["year_s"]);
	
	$startdate = $year_s."-".$month_s."-".$day_s;
	// echo($startdate);

	
	// prepare starttime
	$hour_s = mysql_real_escape_string($_POST["hour_s"]);
	$min_s = mysql_real_escape_string($_POST["minute_s"]);
	$starttime = $hour_s.":".$min_s.":00";
	$startdatetime = $startdate." ".$starttime;
	// echo($starttime);

	
	// check if endttime is set
	if($_POST['set_endtime'] == 1) {	
	
	// prepare endttime
	$hour_e = mysql_real_escape_string($_POST["hour_e"]);
	$min_e = mysql_real_escape_string($_POST["minute_e"]);
	$endtime = $hour_e.":".$min_e.":00";
	// echo($endtime);
	
	// prepare enddate
	if($endtime < $starttime) {
		$day_e = $day_s + 1;
		}
	else {
		$day_e = $day_s;
		}
		
	$enddate = $year_s."-".$month_s."-".$day_e;
	// echo($enddate);

	$enddatetime = $enddate." ".$endtime;
	}
	else {
	$enddatetime = "";
	$set_endtime = "";
	}	
	
	// admission prices
	if($price_min > $price_max) {
		$save1 = $price_max;
		$save2 = $price_min;
		$price_max = $save2;
		$price_min = $save1;
		}
		
	if(($price_min == 0) && ($price_max == 0)) {
		$price_min = NULL;
		$price_max = NULL;
		}	
	
	if($price_min == $price_max) {
		$price_max = NULL;
		}

	if(($price_min == 0) && ($price_max != 0)) {
		$price_min = $price_max;
		$price_max = NULL;
		}
	
	if($_POST['free'] == 1) {
		$price_min = NULL;
		$price_max = NULL;
		$price_free = "1";
		}	
	
	if($_POST['donation'] == 1) {
		$donation = "1";
		}		
	
	
		// prepare variables
		$startdate1 = $startdate." 00:00:00";
		$startdate2 = $startdate." 23:59:59";
	$eventcheck = "SELECT * FROM `events` WHERE title = '$title' AND location_id = '$location_id' AND starttime > '$startdate1' AND starttime < '$startdate2'";
	// echo"<br />";
	// echo($eventcheck);
	$eventcheckquery = mysql_query($eventcheck) or die ("no eventcheckquery");
	$neventsconflict = mysql_num_rows($eventcheckquery);
	// echo"<br /> <br /> <br/>NEVENTSCONFLICT: ";
	// echo($neventsconflict);
	// echo"<br /> <br /> <br/>";
	$eventcheckquery_array = mysql_fetch_assoc($eventcheckquery);
	
	
// check for blank required fields

/*
if(isset($_POST['ce']) == 1) {	
            $fields=array();
            $fields['Title'] = $_POST['title'];
            $fields['Eventtype'] = $_POST['eventtype'];

            foreach ($fields as $key => $val)
            {   if(trim($val)=='')
                {       $errmsg=$key." is not set!";
                        break;
                }
            }
    }
*/

if(($_POST['ce']) == 1) {
	$err = 0;
	$f = 'Y-n-d';
	
	if($_POST['title'] == FALSE) 
	{ 
	$err_title = "No event title set"; 
	$err = $err + 1;
	}	
	
	if($_POST['eventstyle'] == FALSE) 
	{ 
	$err_es = "No event genre set"; 
	$err = $err + 1;
	}
	
	if($startdate == date($f)) {
	$err_st = "Can't enter event for today";
	$err = $err + 1;
	}
	
	if($startdate < date($f)) 
	{
	$err_stp = "Can't enter past dates";
	$err = $err + 1;
	}
	
	unset($_POST['ce']);
}
	
	
	
	if(($neventsconflict == 0) && ($err < 1)){
	// prepare insert
	$writeevent = "INSERT INTO `events` (`location_id`, `title`, `url`, `ticket_url`, `artist`, `price_min`, `price_max`, `price_free`, `donation`, `type`, `genre`,
													`starttime`, `endtime`, `set_endtime`, `description`, `youtube`, `created_by`, `created`, `verified` ) 
										VALUES ('$location_id','$title','$urlevent','$ticket_url','$artist','$price_min','$price_max','$price_free','$donation','$eventtype', '$genre',
													'$startdatetime', '$enddatetime', '$set_endtime', '$description', '$youtube', '$eventuser', NOW(), '$verification' )";
	//echo"<br />";	
	//echo($writeevent);
	// echo"<br />";
	// execute insert
	$writeeventquery = mysql_query($writeevent) or die ("no writeevent");
	
	// get new event id
	$geteventid = "SELECT event_id FROM `events` WHERE created_by = $eventuser ORDER BY created DESC LIMIT 1";
	//echo($geteventid);
	//echo"<br />";
	$geteventidquery = mysql_query($geteventid) or die ("no geteventidquery"); 
	$geteventidquery_array = mysql_fetch_assoc($geteventidquery);
	$neweventid = $geteventidquery_array['event_id'];
	

	
	// redirect to event-page
	
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "event.php?event_id=";
			header("Location: http://$host$path$site$neweventid");
			
			// header("Location: $_SERVER['HTTP_REFERER']");
			exit;	
	

		}
	}	
//var_dump($_POST);	
extract($_POST);

// get location-info
$locationquery = "SELECT * FROM `location` WHERE location_id = $location_id";
$locationqueryresult = mysql_query($locationquery) or die ("no locationquery");
$locationqueryresult_array = mysql_fetch_assoc($locationqueryresult);
// var_dump($locationqueryresult_array);
//extract($locationqueryresult_array);
$location_user = $locationqueryresult_array['created_by'];	
	
// get location creator info
$userquery = "SELECT user, active FROM `users` WHERE id = $location_user ";
// echo($userquery);
$userqueryresult = mysql_query($userquery) or die ("<br />no userquery");
$userqueryresult_array = mysql_fetch_assoc($userqueryresult);

//check if user is active
if(($userqueryresult_array['active']) == 0) {
	$username = 'inactive user';
	}
	else {
$username = $userqueryresult_array['user']; 
// var_dump($userqueryresult_array);
}


if($errmsg != '') {

	// prepare data for datepicker
$_SESSION['month'] = $_POST['month_s'];
$_SESSION['day'] = $_POST['day_s'];
$_SESSION['year'] = $_POST['year_s'];

// prepare data for starttimepicker
$_SESSION['hour'] = $_POST['hour_s'];
$_SESSION['minute'] = $_POST['minute_s'];

// prepare data for endtimepicker
$_SESSION['hour_e'] = $_POST['hour_e'];
$_SESSION['minute_e'] = $_POST['minute_e'];
}


?>

<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>Schmittens - The fat cat knows what's going on</title>
 </head>
 <body>

<?php include "files/nav.php"?>
<div class="columnleft">

<?php 
displayeventsinlocation($location_id);
displayexhibitionsinlocation($location_id);

if(($neventsconflict > 0) ) { ?>

<div class="alerttext">
<h1>Attention!</h1>
<p>There are duplicate events (same title and same date):</p>

	<table summary='' >

	
<?php 	
do 
{ 
// prepare username-query
$creator2 = $eventcheckquery_array['created_by'];
$userquery = "Select user FROM `users` WHERE id = $creator2";
$userqueryresult = mysql_query($userquery); //or die ("<br />no userquery");
$userqueryresult_array = mysql_fetch_assoc($userqueryresult);


$format = "g a D, M j";
$starttime_conflict = $eventcheckquery_array['starttime'];
?>
	<tr>
		<td><?php $phpdate = strtotime($starttime_conflict); echo(date($format, $phpdate)); ?></td>
		<td><a href="event.php?event_id=<?php echo $eventcheckquery_array['event_id']; ?>" ><?php echo $eventcheckquery_array['title']; ?></a></td>
		<td><?php echo $eventcheckquery_array['artist']; ?></td>
		<td><?php echo $eventcheckquery_array['description'];  ?></td>
		<td><?php echo $eventcheckquery_array['type']; ?></td>
		<td><?php 
			if(($_SESSION['id']) == $creator2) { ?>
			<a href="event.php?event_id=<?php echo $eventcheckquery_array['event_id'] ?>" >Edit</a>			
			<?php			
			}
			?>	
	</td>
<?php 
} while ($eventcheckquery_array = mysql_fetch_assoc($eventcheckquery))	;

?>

</table>
</div>
<?php } ?>


<?php 
if($err > 0) { ?>
	
<div class="alerttext">
<h1>Attention!</h1>
<p><?php echo($err); ?> problems found:</p>
<?php echo($err_es); if(isset($err_es)) { echo"<br />"; } ?>
<?php echo($err_title); if(isset($err_title)) { echo"<br />"; } ?>
<?php echo($err_st); if(isset($err_st)) { echo"<br />"; } ?>
<?php echo($err_stp); ?>
</div>
<?php } ?>



<?php 
if(!isset($_POST['ce']))
{ 


?>
<h1>Create new event: <?php echo($eventtype); ?></h1>

<form name="newevent" method="post" enctype="multipart/form-data">

<div>

<h2>Pick a genre:</h2>
<table summary="" >

<?php 

$i = 1;

do 
	{
		if($i == 1) { ?><tr> <?php }
	?><td style="background-color: <?php echo($stylesqueryresult_array['colour']); ?> "><input type="radio" name="eventstyle" value="<?php echo($stylesqueryresult_array['type_id']); ?>" <?php if($genre == $stylesqueryresult_array['type_id']) { echo "checked = 'checked'"; } ?> /><?php echo($stylesqueryresult_array['name']); ?></td>
	<?php
		if($i == 4) { ?></tr> <?php }
		$i = $i + 1;
		if($i > 4) { $i = 1; }
	} while($stylesqueryresult_array = mysql_fetch_assoc($stylesqueryresult)) ;?>

</table>
</div>
<br />
<table style="border-collapse: collapse">
	<tr>
		<td <?php if(isset($err_title)) { echo"class='required'"; } ?>><b>Title</b></td><td <?php if(isset($err_title)) { echo"class='required'"; } ?>><input type="text" name="title" value="<?php echo($_POST["title"]); ?>"/></td>	
	</tr>
	<tr>
		<td <?php if(isset($err_st) || isset($err_stp)) { echo"class='required'"; } ?>><b>Date</b></td><td <?php if(isset($err_st) || isset($err_stp)) { echo"class='required'"; } ?>><?php drawDateTimePicker_date(); ?></td>	
	</tr>
	<tr>
		<td><b>Start</b></td><td><?php  drawDateTimePicker_start_short();  ?></td>
	</tr>
	<tr>
		<td>End</td><td><?php  drawDateTimePicker_end_short();  ?> (<input type="checkbox" name="set_endtime" value="1" 
																						<?php if($_POST['set_endtime'] == 1) { echo"checked = 'checked'"; } ?>
																						/> set endtime)</td>	
	</tr>
	<tr>
		<td>Artists</td><td><input type="text" name="artist" value="<?php echo($_POST['artist']); ?>" /></td>	
	</tr>
	<tr>
		<td>Description</td><td><textarea name="description" rows="6" cols="40" ><?php echo($_POST['description']); ?></textarea></td>	
	</tr>
	<tr>
		<td>Price (min)</td><td><input type="text" name="price_min" value="<?php echo($_POST['price_min']); ?>" /> $</td>	
	</tr>
	<tr>
		<td>Price (max)</td><td><input type="text" name="price_max" value="<?php echo($_POST['price_max']); ?>" /> $</td>	
	</tr>
	<tr>
		<td></td><td>Free: <input type="checkbox" name="free" value="1" <?php if($_POST['free'] == 1) { echo "checked = 'checked'"; } ?> /> <br />Donation: <input type="checkbox" name="donation" value="1" <?php if($_POST['donation'] == 1) { echo "checked = 'checked'"; } ?> /></td>
	</tr>
	<!--
	<tr>	
		<td>Type</td><td><?php echo($_POST['eventtype']); ?><input type="hidden" name="eventtype_previous" value="<?php echo($_POST['eventtype']); ?>"/></td>	
	</tr>

	<tr>
		<td <?php if(isset($err_et)) { echo"class='required'"; } ?>><b>Type</b></td>
		<td <?php if(isset($err_et)) { echo"class='required'"; } ?>>
	<input type="radio" name="eventtype" value="1" <?php if($_POST['eventtype'] == 1) { echo "checked = 'checked'"; } ?>/>Party 
	<input type="radio" name="eventtype" value="2" <?php if($_POST['eventtype'] == 2) { echo "checked = 'checked'"; } ?>/>Concert 
	<input type="radio" name="eventtype" value="3" <?php if($_POST['eventtype'] == 3) { echo "checked = 'checked'"; } ?> />Stage 
	<input type="radio" name="eventtype" value="4" <?php if($_POST['eventtype'] == 4) { echo "checked = 'checked'"; } ?> />Art 
	<input type="radio" name="eventtype" value="5" <?php if($_POST['eventtype'] == 5) { echo "checked = 'checked'"; } ?> />Other 
		</td>
	</tr>
	-->
	<tr>
		<td>Link: </td><td><input type="text" name="url" value="<?php echo($_POST['url']); ?>" /></td>	
	</tr>
	<tr>
		<td>Ticketlink: </td><td><input type="text" name="ticket_url" value="<?php echo($_POST['ticket_url']); ?>" /></td>	
	</tr>
	<tr>
		<td>Youtube-Link: </td><td><input type="text" name="youtube" value="<?php echo($youtube); ?>" /></td>	
	</tr>
	<tr>
		<td>Verified</td><td><?php 
		// prepare verification
		if(($verified) == 0) {
			$verification = "No";
			}
		else {
			$verification = "Yes";
			}	
		print_r($verification); ?></td>	
	</tr>
</table> 
<input type="hidden" name="location_id" value="<?php echo($location_id); ?>"/>
<input type="hidden" name="ce" value="1" />
<input type="submit" name="save" value="Save" />
</form>

<?php } ?>

<div class="hint">
<h1><a href="#">Uploading pictures >> </a></h1>
<p class="toggle">You can upload pictures after you create the event. Just hit the edit-button after you save your event.</p>
</div>



</div>

<div class="columnright">

 	<div class="eventlocation">
<?php displaylocation_right($location_id);?>
	</div>


	<div class="ads"></div>

 	
</div>
<div class="footer">
<?php include"files/footer.php";  ?>
</div>
 </body>
</html>