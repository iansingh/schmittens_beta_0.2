<?php
session_start();

	require "files/functions.php";
	require "files/include.php";	
	require "files/datetimepicker.php";

	dbconnect();
	
if($_SESSION["in"] == FALSE) {
	// redirect user to login page 
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "login.php";
			header("Location: http://$host$path$site");
			exit;
   } 


if(isset($_POST['delete']) == TRUE)
	{
	//var_dump($_POST);
	// prepare delete query
	$eventid_todelete = $_POST['event_id'];
	$deleteevent = "DELETE FROM `events` WHERE event_id = $eventid_todelete ";
	//echo($deleteevent);
	$deleteeventquery = mysql_query($deleteevent);
	
	// redirect user to location 
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "location.php?location_id=";
			$location = $_POST['location_id'];
			header("Location: http://$host$path$site$location");
			exit;
	} 	


/*	
if(isset($_POST['locationchange']) == TRUE)
	{
	// prepare locationchange query
	$newlocation = $_POST['location_id'];
	// echo($newlocation);
	$eventid_toupdate = $_POST['event_id'];
	$locationchange = "UPDATE `events` SET location_id = $newlocation WHERE event_id = $eventid_toupdate ";
	// echo($locationchange);
	$locationchangequery = mysql_query($locationchange);
	// redirect user to location 
					
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "location.php?location_id=";
			$location = $_POST['location_id'];
			header("Location: http://$host$path$site$location");
			exit;
			
	} 
*/
	
if(isset($_POST['imageupload']) == TRUE)
	{
		//define a maxim size for the uploaded images in Kb
		define ("MAX_SIZE","2000");
		
		//This function reads the extension of the file. It is used to determine if the file is an image by checking the extension.
		function getExtension($str) {
		$i = strrpos($str,".");
		if (!$i) { return ""; }
		$l = strlen($str) - $i;
		$ext = substr($str,$i+1,$l);
		return $ext;
		}
		
		// set event_id
		$imgeventid = $_POST['event_id'];		
		
		//This variable is used as a flag. The value is initialized with 0 (meaning no error found) and it will be changed to 1 if an errro occures. If the error occures the file will not be uploaded.
		$errors=0;
		//checks if the form has been submitted
		// if(isset($_POST['Submit']))
		/* { */
		//reads the name of the file the user submitted for uploading
		$image=$_FILES['image']['name'];
		//if it is not empty
		if ($image)
		{
		//get the original name of the file from the clients machine
		$filename = stripslashes($_FILES['image']['name']);
		//get the extension of the file in a lower case format
		$extension = getExtension($filename);
		$extension = strtolower($extension);
		//if it is not a known extension, we will suppose it is an error and will not upload the file, otherwize we will do more tests
		if (($extension != "jpg") && ($extension != "jpeg")) /* && ($extension != "png") && ($extension != "gif")) */
		{
		//print error message
		echo '<h1>Unknown extension! Only .jpg and .jpeg are allowed.</h1>';
		$errors=1;
		}
		else
		{
		//get the size of the image in bytes
		//$_FILES['image']['tmp_name'] is the temporary filename of the file in which the uploaded file was stored on the server
		$size=filesize($_FILES['image']['tmp_name']);
		
		//compare the size with the maxim size we defined and print error if bigger
		if ($size > MAX_SIZE*1024)
		{
		echo '<h1>You have exceeded the size limit!</h1>';
		$errors=1;
		}
		
		//we will give an unique name, for example the time in unix time format
		$image_name=$imgeventid."_original".'.'.$extension;
		//the new name will be containing the full path where will be stored (images folder)
		$newname="img/event/".$image_name;
		//we verify if the image has been uploaded, and print error instead
		$copied = copy($_FILES['image']['tmp_name'], $newname);
		if (!$copied)
		{
		echo '<h1>Copy unsuccessfull!</h1>';
		$errors=1;
		}}}/*}*/
		
		//If no errors registred, print the success message
		if(isset($_POST['imageupload']) && !$errors)
		{
		// write imagename to db
		$writepic = "UPDATE `events` SET `img_original`= '$image_name' WHERE event_id = $imgeventid";
		$writepicquery = mysql_query($writepic);
		//echo($writepicquery);
		
		// create 3 thumbnail sizes		
		
			$eid = $_POST['event_id'];
			
			$imagepath = "img/event/".$eid."_original.jpg";
			
			$c = 1;
			
			do {
			
				if($c == 1) {
					$suffix = "_480.jpg";
					$resize = "img_480";
					$new_width = 480;
					$new_height = 270;
					}		
				if($c == 2) {
					$suffix = "_320.jpg";
					$resize = "img_320";
					$new_width = 320;
					$new_height = 180;
					}		
				if($c == 3) {
					$suffix = "_240.jpg";
					$resize = "img_240";
					$new_width = 240;
					$new_height = 135;
					}	
				if($c == 4) {
					$suffix = "_160.jpg";
					$resize = "img_160";
					$new_width = 160;
					$new_height = 90;
					}		
			
			$image = imagecreatefromjpeg($imagepath) or die("could not open image");
			// if($image == FALSE) { $err_image = "Could not open image"; }
			$filename = "img/event/".$eid.$suffix;
			$imgname = $eid.$suffix;
			
			$thumb_width = $new_width;
			$thumb_height = $new_height;
			
			$width = imagesx($image);
			$height = imagesy($image);
			
			$original_aspect = $width / $height;
			$thumb_aspect = $thumb_width / $thumb_height;
			
			if ( $original_aspect >= $thumb_aspect )
			{
			   // If image is wider than thumbnail (in aspect ratio sense)
			   $new_height = $thumb_height;
			   $new_width = $width / ($height / $thumb_height);
			}
			else
			{
			   // If the thumbnail is wider than the image
			   $new_width = $thumb_width;
			   $new_height = $height / ($width / $thumb_width);
			}
			
			$thumb = imagecreatetruecolor( $thumb_width, $thumb_height );
			
			// Resize and crop
			imagecopyresampled($thumb,
			                   $image,
			                   0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
			                   0 - ($new_height - $thumb_height) / 2, // Center the image vertically
			                   0, 0,
			                   $new_width, $new_height,
			                   $width, $height);
			imagejpeg($thumb, $filename, 80);
			
			// write imagename to db
			$imgupdate = "UPDATE `events` SET $resize = '$imgname', img_present = 1 WHERE event_id = '$eid'";
			$imgupdatequery = mysql_query($imgupdate); 
			
			$c = $c + 1;		
			}	
			while($c < 5) ;
				
		}

	} 	
	
	
	
	

if(isset($_POST['save']) == TRUE) 
	{
	//var_dump($_POST);
	//echo"<br />";
	//echo"change";
	//var_dump($_POST);
	extract($_POST);	
	//echo($youtube);
	$youtube_pre = mysql_real_escape_string($_POST['youtube']);
	$ticket_url = mysql_real_escape_string($_POST['ticket_url']);
	$verification = $_SESSION['verification'];
	
	// get youtube video-id
	
	if(($youtube_pre != "") && (strlen($youtube_pre) > 11)) {
	preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $youtube_pre, $matches);
	$youtube = $matches[0];
	//echo($youtube);
	}
	

	
	// prepare query values
	// price
	if(($price_max) < ($price_min)) {
	$price = $price_min;
	$price2 = $price_max;
	$price_min = $price2;
	$price_max = $price;	
	}

	if($_POST['eventtype'] == NULL) {
	$eventtype = $_POST['eventtype_previous'];
	}
	if($_POST['eventtype'] == 1) {
	$eventtype = "Party";
	}
	if($_POST['eventtype'] == 2) {
	$eventtype = "Concert";
	}
	if($_POST['eventtype'] == 3) {
	$eventtype = "Stage";
	}
	if($_POST['eventtype'] == 4) {
	$eventtype = "Exhibition";
	}
	if($_POST['eventtype'] == 5) {
	$eventtype = "Other";
	}

	// prepare date
	$month_s = mysql_real_escape_string($_POST["month_s"]);
	$day_s = mysql_real_escape_string($_POST["day_s"]);
	$year_s = mysql_real_escape_string($_POST["year_s"]);
	
	$startdate = $year_s."-".$month_s."-".$day_s;
	//echo"<br />";	
	//echo($startdate);
	//echo"<br />";
	
	// prepare starttime
	$hour_s = mysql_real_escape_string($_POST["hour_s"]);
	$min_s = mysql_real_escape_string($_POST["minute_s"]);
	$starttime = $hour_s.":".$min_s.":00";
	$startdatetime = $startdate." ".$starttime;
	//echo($starttime);
	//echo"<br />";
	
	// check if endttime is set
	if($_POST['set_endtime'] == 1) {	
	
	// prepare endttime
	$hour_e = mysql_real_escape_string($_POST["hour_e"]);
	$min_e = mysql_real_escape_string($_POST["minute_e"]);
	$endtime = $hour_e.":".$min_e.":00";
	//echo($endtime);
	//echo"<br />";
	
	// prepare enddate
	if(mktime($endtime) < mktime($starttime)) {
		$day_e = $day_s + 1;
		}
	else {
		$day_e = $day_s;
		}	
		
	$enddate = $year_s."-".$month_s."-".$day_e;
	//echo"Enddate: ";	
	//echo($enddate);
	//echo"<br />";
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
	

	//prepare time
	date_default_timezone_set('America/New_York');
	$currentdate = date('Y-n-d H:i:s', time());
	
	// check required fields
	
	$err = 0;
	
	if($_POST['title'] == FALSE) 
	{ 
	$err_title = "No title set"; 
	$err = $err + 1;
	}	
	$sd = strtotime($startdatetime);
	$cd = strtotime($currentdate);
	if($sd < $cd) 
	{ 
	$err_date1 = "Startdate is past"; 
	$err = $err + 1;
	}	
	if($_POST['genre'] == FALSE) 
	{
	$err_type = "No eventtype set"; 
	$err = $err + 1;
	}	
	

if($err == 0) {
	//echo($genre);
	
	$eventtype = gettypename($genre);
	
	$eventupdate = "UPDATE `events` SET 
						`location_id`= $location_id ,
						`title`= '$title' ,`url`= '$url' ,`ticket_url` = '$ticket_url', `artist`= '$artist' ,
						`price_min`= '$price_min' ,`price_max`= '$price_max' , `price_free` = '$price_free', `donation` = '$donation',
						`type`= '$eventtype', `genre` = '$genre',
						`starttime`= '$startdatetime' ,`endtime`= '$enddatetime' ,`set_endtime` = '$set_endtime',
						`description`= '$description' , `youtube` = '$youtube',
						`modified`= NOW() , `verified` = '$verification' 
						WHERE `event_id` = $event_id"	;
	//echo"<br /><br />";	
	//echo($eventupdate);
	$eventupdatequery = mysql_query($eventupdate) or die ("no eventupdatequery") ;
	
	// redirect to event-page
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "event.php?event_id=";
			$event = $event_id;
			header("Location: http://$host$path$site$event_id");
			exit;
	}
	}


// 	var_dump($_POST);
extract($_POST);
	


// get event data
$eventquery = "SELECT * FROM `events` WHERE event_id = $event_id";
$eventqueryresult = mysql_query($eventquery) or die ("no eventquery");
$eventqueryresult_array = mysql_fetch_assoc($eventqueryresult);
//var_dump($eventqueryresult_array);
extract($eventqueryresult_array);
$ticket_url = $eventqueryresult_array['ticket_url'];

$location_id = $eventqueryresult_array['location_id'];
$price_min = $eventqueryresult_array['price_min'];
$price_max = $eventqueryresult_array['price_max'];
$user_id = $eventqueryresult_array['created_by'];
//$eventpic = $eventqueryresult_array['img'];

// prepare price
if(($price_max) == 0) {
	$price = $price_min;
	}
if(($price_max) < ($price_min)) {
	$price = $price_min;	
	}
else {
	$price = $price_min." - ".$price_max;
	}



// get location info
$locationquery = "SELECT * FROM `location` WHERE location_id = $location_id";
$locationqueryresult = mysql_query($locationquery) or die ("no locationquery");
$locationqueryresult_array = mysql_fetch_assoc($locationqueryresult);
extract($locationqueryresult_array);

// check user
$userquery = "SELECT user, active FROM `users` WHERE id = $user_id";
$userqueryresult = mysql_query($userquery) or die ("<br />no userquery");
$userqueryresult_array = mysql_fetch_assoc($userqueryresult);
// var_dump($userqueryresult_array);

$username = $userqueryresult_array['user']; 

$format_date = "D, M j";
$format_time = "g.i a ";


// prepare data for datepicker
$month = date('n', strtotime($eventqueryresult_array['starttime']));
$_SESSION['month'] = $month;
$day = date('j', strtotime($eventqueryresult_array['starttime']));
$_SESSION['day'] = $day;
$year = date('Y', strtotime($eventqueryresult_array['starttime']));
$_SESSION['year'] = $year;

// prepare data for starttimepicker
$hour = date('H', strtotime($eventqueryresult_array['starttime']));
$_SESSION['hour'] = $hour;
$minute = date('i', strtotime($eventqueryresult_array['starttime']));
$_SESSION['minute'] = $minute;


// prepare data for endtimepicker
$hour_e = date('H', strtotime($eventqueryresult_array['endtime']));
$_SESSION['hour_e'] = $hour_e;
$minute_e = date('i', strtotime($eventqueryresult_array['endtime']));
$_SESSION['minute_e'] = $minute_e;


?>

<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>Schmittens - <?php echo($title); ?></title>
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
<p>Some required information is missing:</p>
<?php if(isset($err_title)) { echo($err_title); echo"<br />"; } ?>
<?php if(isset($err_date1)) { echo($err_date1); echo"<br />"; } ?>
<?php echo($err_type);  ?>
</div>

<?php } 


if(isset($err_image)) { ?>
<div class="alerttext">
<h1>Attention!</h1>
<?php echo($err_image); ?>
</div>
<?php } ?> 	

		<div class="hint">
			<h1><a href="#">Hints >> </a></h1>	
			<ul class="toggle">
				<li>Opening hours can and should be edited or entered in the location if possible!</li>	
				<li>You can enter custom opening hours below.</li>	
				<li>You can upload pictures after saving the event. Just click the edit button.</li>	
			</ul>
		</div>

<h1>Edit event - <?php echo($eventqueryresult_array['type']); ?></h1>
 	
<div class="form"> 


<?php $img_original = $event_id."_original";	?>

<!-- <img src="img/event/<?php echo($img_original); ?>" alt="Original" /> -->
<img src="img/event/<?php echo($eventqueryresult_array['img_480']); ?>" alt="480x270" />

<p>Upload Image:</p>

<form name="newad" method="post" enctype="multipart/form-data" action="">
<table>
<tr><td><input type="file" class="imgupload" name="image"></td></tr>
<tr><td><input type="hidden" name="event_id" value="<?php echo($event_id); ?>"/><input name="imageupload" disabled="disabled" type="submit" class="disable" value="Upload image"></td></tr>
</table>
</form>

<br />
Event-ID: <?php echo($event_id); ?>  | 
Created by: <?php echo($username); ?> on <?php $phpdate = strtotime($eventqueryresult_array['created']); echo(date($format_date, $phpdate))?>
<br />
<br />

<form method="post" enctype="multipart/form-data">

<?php 

if($eventqueryresult_array['type'] == 'Party') {$etype = 1000;}
if($eventqueryresult_array['type'] == 'Concert') {$etype = 2000;}
if($eventqueryresult_array['type'] == 'Other') {$etype = 5000;}
//echo($etype);

?>
<div <?php if(isset($err_type)) { echo"class='required'"; } ?> >
<?php
displaygenres($etype,$genre);
?>
</div>

<table>
	<tr>
		<td><b>Title</b></td><td <?php if(isset($err_title)) { echo"class='required'"; } ?> ><input type="text" name="title" value="<?php echo($title); ?>"/></td>	
	</tr>
	<tr>
		<td><b>Date</b></td><td <?php if(isset($err_date1)) { echo"class='required'"; } ?> ><?php /* $phpdate = strtotime($eventqueryresult_array['starttime']); echo(date($format_date, $phpdate)); */ drawDateTimePicker_date_e(); ?></td>	
	</tr>
	<tr>
		<td><b>Start</b></td><td <?php if(isset($err_date1)) { echo"class='required'"; } ?> ><?php /* $phpdate = strtotime($eventqueryresult_array['starttime']); echo(date($format_time, $phpdate)); */ drawDateTimePicker_start_short_e(); ?></td>
	</tr>
	<tr>
		<td>End</td><td><?php /* if($eventqueryresult_array['endtime'] != 0) { $phpdate = strtotime($eventqueryresult_array['endtime']); echo(date($format_time, $phpdate));} else { echo"";} */ drawDateTimePicker_end_short_e(); ?> (<input type="checkbox" name="set_endtime" value="1" <?php if($eventqueryresult_array['set_endtime'] == 1) { echo"checked = 'checked'";  } ?> /> set endtime)</td>	
	</tr>
	<tr>
		<td>Artists</td><td><input type="text" name="artist" value="<?php echo($artist); ?>" /></td>	
	</tr>
	<tr>
		<td>Description</td><td><textarea name="description" rows="6" cols="40" ><?php echo($description); ?></textarea></td>	
	</tr>
	<tr>
		<td>Price (min)</td><td><input type="text" name="price_min" value="<?php echo($price_min); ?>" /> $</td>	
	</tr>
	<tr>
		<td>Price (max)</td><td><input type="text" name="price_max" value="<?php echo($price_max); ?>" /> $</td>	
	</tr>
	<tr>
		<td></td><td>Free: <input type="checkbox" name="free" value="1" <?php if($price_free == 1) { echo "checked = 'checked'"; } ?> /> <br />Donation: <input type="checkbox" name="donation" value="1" <?php if($donation == 1) { echo "checked = 'checked'"; } ?> /></td>
	</tr>
	
<!--
	<tr>
		<td>Type</td>
		<td  >
		<?php echo($eventqueryresult_array['type']); ?>
		<input type="hidden" name="eventtype" value="<?php echo($eventqueryresult_array['type']); ?>" />
		<!--
	<input type="radio" name="eventtype" value="Party" <?php if($eventqueryresult_array['type'] == Party) { echo "checked='checked'"; } ?> />Party 
	<input type="radio" name="eventtype" value="Concert" <?php if($eventqueryresult_array['type'] == 'Concert') { echo "checked='checked'"; } ?> />Concert 
	<input type="radio" name="eventtype" value="3" <?php if($eventqueryresult_array['type'] == 'Stage') { echo "checked='checked'"; } ?> />Stage 
	<input type="radio" name="eventtype" value="4" <?php if($eventqueryresult_array['type'] == 'Exhibition') { echo "checked='checked'"; } ?> />Art
	<input type="radio" name="eventtype" value="Other" <?php if($eventqueryresult_array['type'] == 'Other') { echo "checked='checked'"; } ?> />Other 

	</tr>
-->
	<tr>
		<td>Link: </td><td><input type="text" name="url" value="<?php echo($eventqueryresult_array['url']); ?>" /></td>	
	</tr>
	<tr>
		<td>Ticketlink: </td><td><input type="text" name="ticket_url" value="<?php echo($eventqueryresult_array['ticket_url']); ?>" /></td>	
	</tr>
	<tr>
		<td>Youtube-Link: </td><td><input type="text" name="youtube" value="<?php echo($eventqueryresult_array['youtube']); ?>" /></td>	
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
<input type="hidden" name="event_id" value="<?php echo($event_id); ?>"/>
<input type="hidden" name="location_id" value="<?php echo($location_id); ?>"/>
<input type="submit" name="save" value="Save" />
</form>

<br />

<?php  display_deletebutton_event($event_id,$location_id); ?>
 


</div>
</div>

<div class="columnright">

  	<div class="eventlocation">
<?php displaylocation_right($location_id); ?>
	</div>

<?php display_eventcreator($event_id,$genre); ?>

</div>

<div class="footer">
<?php include"files/footer.php";  ?>
</div>
 
 </body>
 </html>