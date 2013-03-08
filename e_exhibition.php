<?php
	session_start();
	 header('Content-type: text/html; charset=utf-8');
	require "files/functions.php";
	require "files/include.php";	
	require "files/datetimepicker.php";
	require "files/exhibitioncreator.php";	

checklogin();

dbconnect();

//var_dump($_POST);
//var_dump($_GET);

$ex_id = $_GET['ex_id'];
$location_id = getlocation_id_exhibition($ex_id);

$etype = 3000;


// delete exhibition
if(isset($_POST['delete_ex']) == TRUE)
	{
		$ex_id = $_POST['ex_id'];
		$location_id = $_POST['location_id'];
	delete_ex_events($ex_id, $location_id);
	delete_exhibition($ex_id, $location_id);
	} 

//delete custom openinghours

if(isset($_POST['delete'])) {
$delete_array = $_POST['delete'];
//var_dump($delete_array);

foreach ($delete_array as $key => $value) {

$day=$delete_array[$key];
$del_s = $day."_s";
$del_e = $day."_e";

$dq = "UPDATE `exhibitions` SET $del_s = '00:00:00', $del_e = '00:00:00' WHERE ex_id = $ex_id";
$dr = mysql_query($dq) or die ("no delete custom openinghours");
	
	}	
	
	$done = exhibitioncreator($ex_id,$location_id);

	
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "exhibition.php?ex_id=";
			header("Location: http://$host$path$site$ex_id");		
			exit;	
	
	}


if(isset($_POST['oh']) == 1) {
	extract($_POST);
//	var_dump($_POST);

//var_dump($hour_s);
//var_dump($hour_e);

	
	if(savecustomopeninghours($ex_id, $hour_s, $minute_s, $hour_e, $minute_e) == TRUE)
	{
	$coh = "Openinghours saved.";	
	exhibitioncreator($ex_id,$location_id);	
		}
	else { $coh = "Openinghours not saved! Something went wrong.";}
	//echo($coh);
	}	
	
	$openinghours_array = getcustomopeninghours($ex_id);
	//var_dump($openinghours_array);	
	
if(isset($_POST['imageupload']) == TRUE) {
//var_dump($_POST);
imageupload($ex_id,$_POST['genre']);
}

$custom_s = getcustomopeninghours_start($ex_id);
extract($custom_s);
$custom_e = getcustomopeninghours_end($ex_id);
extract($custom_e);	
	
if(isset($_POST['ce']) == 1) {	
	
	// debug
	// var_dump($_POST);
	
	//prepare varigetcuables
	$ex_id = mysql_real_escape_string($_POST["ex_id"]);
	$title = mysql_real_escape_string($_POST["title"]);
	$urlevent = mysql_real_escape_string($_POST["url"]);
	$artist = mysql_real_escape_string($_POST["artist"]);
	$description = mysql_real_escape_string($_POST["description"]);
	$location_id = mysql_real_escape_string($_POST["location_id"]);
	$price_min = mysql_real_escape_string($_POST["price_min"]);
	$price_max = mysql_real_escape_string($_POST["price_max"]);
	$ticket_url = mysql_real_escape_string($_POST["ticket_url"]);
	$youtube_pre = mysql_real_escape_string($_POST["youtube"]);
	$genre = mysql_real_escape_string($_POST["genre"]);
	$verification = $_SESSION['verification'];
	$eventuser = $_SESSION['id'];
	//extract($_POST);
	
	// get youtube video id
	
	preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $youtube_pre, $matches);
	$youtube = $matches[0];
	
	
	// prepare startdate
	$month_s = mysql_real_escape_string($_POST["month_s"]);
	$day_s = mysql_real_escape_string($_POST["day_s"]);
	$year_s = mysql_real_escape_string($_POST["year_s"]);
	
	//echo"Startdate: ".$month_s."-".$day_s."-".$year_s;	
	
	// check if date is real
	if(checkdate($month_s, $day_s, $year_s)) {
	$startdate = $year_s."-".$month_s."-".$day_s;
	}
	else {
	$err_date = "Something is wrong with the startdate.";
	//echo($err_date);
	}
	//echo($startdate);
	
	// prepare enddate
	$month_e = mysql_real_escape_string($_POST["month_e"]);
	$day_e = mysql_real_escape_string($_POST["day_e"]);
	$year_e = mysql_real_escape_string($_POST["year_e"]);
	
	// check if date is real
	if(checkdate($month_e, $day_e, $year_s)) {
	$enddate = $year_e."-".$month_e."-".$day_e;
	}
	else {
	$err_date = "Something is wrong with the enddate.";
	//echo($err_date);
	}	
	//echo($enddate);


	
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
	

	$err = 0;
	$f = 'Y-n-d';
	
	if($_POST['title'] == FALSE) 
	{ 
	$err_title = "No event title set"; 
	$err = $err + 1;
	}	
	
	if($_POST['genre'] == FALSE) 
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
	
	if(isset($err_date)) {
		$err++;
	}
	
	unset($_POST['ce']);
}
	
	
	
	if(($err < 1) && ($_POST['update'] == 1)){
	// prepare UPDATE - taken out: `location_id` = '$location_id', 
	$writeevent = "UPDATE `exhibitions` SET `e_title` = '$title', `startdate` = '$startdate', 
														 `enddate` = '$enddate', `url` = '$urlevent', `ticket_url` = '$ticket_url', 
														 `artist` = '$artist', `price_min` = '$price_min', `price_max` = '$price_max', 
														 `price_free` = '$price_free', `donation` = '$donation', `genre` = '$genre', `description` = '$description', 
														 `youtube` = '$youtube', `modified` = NOW(), `verified` ='$verification' WHERE ex_id = $ex_id";
	//echo"<br />";	
	//echo($writeevent);
	// echo"<br />";
	// execute insert
	$writeeventquery = mysql_query($writeevent) or die ("no writeevent");
	
	//delete individual events with old data
	delete_ex_events($ex_id, $location_id);
	

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// pass arguments to exhibitioncreator to create individual events!
// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX


	$done = exhibitioncreator($ex_id,$location_id);

	
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "exhibition.php?ex_id=";
			header("Location: http://$host$path$site$ex_id");		
			exit;
		
	
}


// GET ex_id, lookup info, get location_id

$ex_id = $_GET['ex_id'];
$exhibition = getexhibitiondata($ex_id);
$location_id = $exhibition['location_id'];


// get location-info
$location = getlocationdata($location_id);
$user_id = $location['created_by'];	

	
// get location creator info
$user = getuserdata($user_id);


//check if user is active
isuseractive($user_id);



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
  <title>Schmittens - Event yourself!</title>
 </head>
 <body>

<?php include "files/nav.php"?>
<div class="columnleft">


<?php 
displayeventsinlocation($location_id); 
displayexhibitionsinlocation($location_id);



if($err > 0) { ?>
	
<div class="alerttext">
<h1>Attention!</h1>
<p><?php echo($err); ?> problems found:</p>
<?php echo($err_es); if(isset($err_es)) { echo"<br />"; } ?>
<?php echo($err_opening); if(isset($err_opening)) { echo"<br />"; } ?>
<?php echo($err_date); if(isset($err_date)) { echo"<br />"; } ?>
<?php echo($err_title); if(isset($err_title)) { echo"<br />"; } ?>
<?php echo($err_st); if(isset($err_st)) { echo"<br />"; } ?>
<?php echo($err_stp); ?>
</div>
<?php } ?>

<?php 

if($_POST['err_noopen'] == TRUE) { ?>
	
<div class="alerttext">
<h1>Attention!</h1>
<p>This location does not have opening hours! Please enter custom opening hours for this exhibition below!</p>
</div>
<?php	}

/*
display_imageupload($ex_id,$exhibition['genre']);
*/
?>


<?php 
if(!isset($_POST['ce']))
{ 


?>
		<div class="hint">
			<h1><a href="#">Hints >> </a></h1>	
			<ul class="toggle">
				<li>Opening hours can and should be edited or entered in the location if possible!</li>	
				<li>You can enter custom opening hours below.</li>	
				<li>You can upload pictures after saving the event. Just click the edit button.</li>	
			</ul>
		</div>

<h1>Modify event: <?php echo($exhibition['e_title']); ?></h1>

<form name="newevent" method="post" enctype="multipart/form-data">

<div>

<h2>Pick a genre:</h2>


<?php 

displaygenres($etype,$exhibition['genre']); ?>

</div>
<br />

<table style="border-collapse: collapse">
	<tr>
		<td <?php if(isset($err_title)) { echo"class='required'"; } ?>><b>Title</b></td><td <?php if(isset($err_title)) { echo"class='required'"; } ?>><input type="text" name="title" value="<?php echo($exhibition["e_title"]); ?>"/></td>	
	</tr>
	<tr>
		<td <?php if(isset($err_st) || isset($err_stp)) { echo"class='required'"; } ?>><b>Startdate</b></td><td <?php if(isset($err_st) || isset($err_stp)) { echo"class='required'"; } ?>><?php $sd = $exhibition['startdate']; drawDTP_art_start_e($sd); ?> </td>	
	</tr>
	<tr>
		<td <?php if(isset($err_st) || isset($err_stp)) { echo"class='required'"; } ?>><b>Enddate</b></td><td <?php if(isset($err_st) || isset($err_stp)) { echo"class='required'"; } ?>><?php $ed = $exhibition['enddate']; drawDTP_art_end_e($ed); ?></td>	
	</tr>
	<!--
	<tr>
		<td>Vernissage</td><td><input type="checkbox" name="vernissage" value="true" /> <?php drawDateTimePicker_art_vernissage(); ?></td>	
	</tr>
	<tr>
		<td>Finissage</td><td><input type="checkbox" name="finissage" value="true" /> <?php drawDateTimePicker_art_finissage(); ?></td>	
	</tr>
	-->
	<tr>
	
	</tr>
	<tr>
		<td><hr></td><td><hr></td>	
	</tr>
	<tr>
		<td>Artists</td><td><input type="text" name="artist" value="<?php echo($exhibition['artist']); ?>" /></td>	
	</tr>
	<tr>
		<td>Description</td><td><textarea name="description" rows="6" cols="40" ><?php echo($exhibition['description']); ?></textarea></td>	
	</tr>
	<tr>
		<td>Price (min)</td><td><input type="text" name="price_min" value="<?php if($exhibition['price_free'] == 1) {echo($exhibition['price_min']); }?>" /> $</td>	
	</tr>
	<tr>
		<td>Price (max)</td><td><input type="text" name="price_max" value="<?php if($exhibition['price_free'] == 1) {echo($exhibition['price_min']); }?>" /> $</td>	
	</tr>
	<tr>
		<td></td><td>Free: <input type="checkbox" name="free" value="1" <?php if($exhibition['price_free'] == 1) { echo "checked = 'checked'"; } ?> /> <br />Donation: <input type="checkbox" name="donation" value="1" <?php if($exhibition['donation'] == 1) { echo "checked = 'checked'"; } ?> /></td>
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
		<td>Link: </td><td><input type="text" name="url" value="<?php echo($exhibition['url']); ?>" /></td>	
	</tr>
	<tr>
		<td>Ticketlink: </td><td><input type="text" name="ticket_url" value="<?php echo($exhibition['ticket_url']); ?>" /></td>	
	</tr>
	<tr>
		<td>Youtube-Link: </td><td><input type="text" name="youtube" value="<?php echo($exhibition['youtube']); ?>" /></td>	
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
<input type="hidden" name="ex_id" value="<?php echo($ex_id); ?>"/>
<input type="hidden" name="location_id" value="<?php echo($location_id); ?>"/>
<input type="hidden" name="ce" value="1" />
<input type="hidden" name="update" value="1" />
<input type="submit" name="save" value="Save" />
</form>

 <table summary="" >
	<tr>
		<td>
<form action="e_exhibition.php" method="post">
	<input type="hidden" name="location_id" value="<?php echo($location_id); ?>"/>
	<input type="hidden" name="ex_id" value="<?php echo($ex_id); ?>"/>
	<input type="hidden" name="delete_ex" value="delete">
	<input type="submit" name="delete_ex1" value="Delete this event">
</form> 		

		</td><td>Warning! This cannot be undone!</td>	
	</tr>
</table>

<?php } ?>

	
	<div class="hint">	
	<h1><a href="#">Opening hours >> </a></h1>
	<p class="toggle">Use this only if:</p>
	<ul class="toggle">
	<li>the location has no opening hours (& you are not the administrator of the location)</li>
	<li>your exhibition has different opening hours than the location</li>
	</ul>
	</div>
	
<?php if(isset($err_opening)) { ?>

<div class="alerttext">
<h1>Attention!</h1>
<p>Something is wrong:</p>
<?php echo($err_opening); ?>
</div>

<?php } ?>	
	
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
	<input type="hidden" name="oh" value="1" />
	<input type="hidden" name="ex_id" value="<?php echo($ex_id); ?>" />
<table summary="" style="font-size: small; border-collapse:collapse;">
		<tr>
			<td>Weekday</td>
			<td>Save</td>
			<td>Open/Close</td>
			<td>Current hours</td>
			<td>Delete</td>
		</tr>
		<tr <?php if($monday_s != "00:00:00") { echo"style='background-color: #c1ffab;'";}?>>
			<td>Monday: </td>
			<td><input type="checkbox" name="day[0]" value="monday"/></td>
			<td><?php drawDateTimePicker_art_openinghours_ex($monday_s,$monday_e)?></td>
			<td><?php if($monday_s != "00:00:00") { echo(date('G:i',strtotime($monday_s))); echo" - "; echo(date('G:i',strtotime($monday_e)));} ?></td>
			<td><input type="checkbox" name="delete[0]" value="monday"/></td>
		</tr>
		<tr <?php if($tuesday_s != "00:00:00") { echo"style='background-color: #c1ffab;'";}?>>
			<td>Tuesday: </td>
			<td><input type="checkbox" name="day[1]" value="tuesday" <?php if($_POST['tuesday'] == TRUE) {echo'checked = "checked"';} ?>/> </td>
			<td><?php drawDateTimePicker_art_openinghours_ex($tuesday_s,$tuesday_e)?></td>
			<td><?php if($tuesday_s != "00:00:00") { echo(date('G:i',strtotime($tuesday_s))); echo" - "; echo(date('G:i',strtotime($tuesday_e)));} ?></td>
			<td><input type="checkbox" name="delete[1]" value="tuesday"/></td>
		</tr>
		<tr <?php if($wednesday_s != "00:00:00") { echo"style='background-color: #c1ffab;'";}?>>
			<td>Wednesday: </td>
			<td><input type="checkbox" name="day[2]" value="wednesday" <?php if($_POST['wednesday'] == TRUE) {echo'checked = "checked"';} ?>/> </td>
			<td><?php drawDateTimePicker_art_openinghours_ex($wednesday_s,$wednesday_e)?></td>
			<td><?php if($wednesday_s != "00:00:00") { echo(date('G:i',strtotime($wednesday_s))); echo" - "; echo(date('G:i',strtotime($wednesday_e)));} ?></td>
			<td><input type="checkbox" name="delete[2]" value="wednesday"/></td>
		</tr>
		<tr <?php if($thursday_s != "00:00:00") { echo"style='background-color: #c1ffab;'";}?>>
			<td>Thursday: </td>
			<td><input type="checkbox" name="day[3]" value="thursday" <?php if($_POST['thursday'] == TRUE) {echo'checked = "checked"';} ?>/></td>
			<td><?php drawDateTimePicker_art_openinghours_ex($thursday_s,$thursday_e)?></td>
			<td><?php if($thursday_s != "00:00:00") { echo(date('G:i',strtotime($thursday_s))); echo" - "; echo(date('G:i',strtotime($thursday_e)));} ?></td>
			<td><input type="checkbox" name="delete[3]" value="thursday"/></td>
		</tr>
		<tr <?php if($friday_s != "00:00:00") { echo"style='background-color: #c1ffab;'";}?>>
			<td>Friday: </td>
			<td><input type="checkbox" name="day[4]" value="friday" <?php if($_POST['friday'] == TRUE) {echo'checked = "checked"';} ?>/> </td>
			<td><?php drawDateTimePicker_art_openinghours_ex($friday_s,$friday_e)?></td>
			<td><?php if($friday_s != "00:00:00") { echo(date('G:i',strtotime($friday_s))); echo" - "; echo(date('G:i',strtotime($friday_e)));} ?></td>
			<td><input type="checkbox" name="delete[4]" value="friday"/></td>
		</tr>
		<tr <?php if($saturday_s != "00:00:00") { echo"style='background-color: #c1ffab;'";}?>>
			<td>Saturday: </td>
			<td><input type="checkbox" name="day[5]" value="saturday" <?php if($_POST['saturday'] == TRUE) {echo'checked = "checked"';} ?>/></td>
			<td><?php drawDateTimePicker_art_openinghours_ex($saturday_s,$saturday_e)?></td>
			<td><?php if($saturday_s != "00:00:00") { echo(date('G:i',strtotime($saturday_s))); echo" - "; echo(date('G:i',strtotime($saturday_e)));} ?></td>
			<td><input type="checkbox" name="delete[5]" value="saturday"/></td>
		</tr>
		<tr <?php if($sunday_s != "00:00:00") { echo"style='background-color: #c1ffab;'";}?>>
			<td>Sunday: </td>
			<td><input type="checkbox" name="day[6]" value="sunday" <?php if($_POST['sunday'] == TRUE) {echo'checked = "checked"';} ?>/> </td>
			<td><?php drawDateTimePicker_art_openinghours_ex($sunday_s,$sunday_e)?></td>
			<td><?php if($sunday_s != "00:00:00") { echo(date('G:i',strtotime($sunday_s))); echo" - "; echo(date('G:i',strtotime($sunday_e)));} ?></td>
			<td><input type="checkbox" name="delete[6]" value="sunday"/></td>
		</tr>
</table>
				<input name="save" type="submit" value="Save" />		
				<input type="reset" value="Reset" />
</form>

</div>


</div>

<div class="columnright">

 <?php displaylocation_right($location_id); ?>
<br />

<?php display_eventcreator($ex_id,$exhibition['genre']); ?>

	<div class="ads"></div>

 	
</div>
<div class="footer">
<?php include"files/footer.php";  ?>
</div>
 </body>
</html>