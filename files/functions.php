<?php 





function checklogin() {
	// Check if User is logged in & if not send to login page
	if($_SESSION["in"] == FALSE) {
  	  	//echo("<a href=\"login.php\">Log in!</a>"); 
  	  		$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "login.php";
			header("Location: http://$host$path$site");
			
			// header("Location: $_SERVER['HTTP_REFERER']");
			exit;	
   	} 
	}

function locationquery() {
	// prepare query		
	$locationquery = "SELECT location_id, l_name, street, streetnumber, postalcode, province, type FROM `location` WHERE location_id > 0";
	
	// execute query
	$locationqueryresult = mysql_query($locationquery) or die ("<br />no query");

	
	$result_array = mysql_fetch_assoc($locationqueryresult);
	return $result_array;	
	}

function getlocationids() {
	// generate # of events for each location with link									
	// prepare location_id
	// $location_id = ($locationresult_array["location_id"]);
	//php displays only require
	global $location_id;
	global $nevents;
	// prepare query
	$neventsquery = "SELECT COUNT(*) FROM `events` WHERE location_id = $location_id";
	
	// execute query
	$neventsqueryresult = mysql_query($neventsquery) or die ("<br />no nevents query");		
	
	$neventsresult_array = mysql_fetch_assoc($neventsqueryresult);	
	$nevents = $neventsresult_array["COUNT(*)"];

	return $nevents;
	}
	
	
function dbconnect() {
	if (($connection = mysql_connect(HOST, USER, PASS)) === FALSE)
			die("Could not connect to database");
		
	if (mysql_select_db(DB, $connection) === FALSE)
			die("Could not select database");

	mysql_set_charset("utf8");
	}





  function smart_resize_image($file,
                              $width = 0,
                              $height = 0,
                              $proportional = false,
                              $output = 'file',
                              $delete_original = true,
                              $use_linux_commands = false ) {
      
    if ( $height <= 0 && $width <= 0 ) return false;

    # Setting defaults and meta
    $info = getimagesize($file);
    $image = '';
    $final_width = 150; 
    $final_height = 0;
    list($width_old, $height_old) = $info;

    # Calculating proportionality
    if ($proportional) {
      if ($width == 0) $factor = $height/$height_old;
      elseif ($height == 0) $factor = $width/$width_old;
      else $factor = min( $width / $width_old, $height / $height_old );

      $final_width = round( $width_old * $factor );
      $final_height = round( $height_old * $factor );
    }
    else {
      $final_width = ( $width <= 0 ) ? $width_old : $width;
      $final_height = ( $height <= 0 ) ? $height_old : $height;
    }

    # Loading image to memory according to type
    switch ( $info[2] ) {
      case IMAGETYPE_GIF: $image = imagecreatefromgif($file); break;
      case IMAGETYPE_JPEG: $image = imagecreatefromjpeg($file); break;
      case IMAGETYPE_PNG: $image = imagecreatefrompng($file); break;
      default: return false;
    }
    
    
    # This is the resizing/resampling/transparency-preserving magic
    $image_resized = imagecreatetruecolor( $final_width, $final_height );
    if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
      $transparency = imagecolortransparent($image);

      if ($transparency >= 0) {
        $transparent_color = imagecolorsforindex($image, $trnprt_indx);
        $transparency = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
        imagefill($image_resized, 0, 0, $transparency);
        imagecolortransparent($image_resized, $transparency);
      }
      elseif ($info[2] == IMAGETYPE_PNG) {
        imagealphablending($image_resized, false);
        $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
        imagefill($image_resized, 0, 0, $color);
        imagesavealpha($image_resized, true);
      }
    }
    imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $final_width, $final_height, $width_old, $height_old);
    
    # Taking care of original, if needed
    if ( $delete_original ) {
      if ( $use_linux_commands ) exec('rm '.$file);
      else @unlink($file);
    }

    # Preparing a method of providing result
    switch ( strtolower($output) ) {
      case 'browser':
        $mime = image_type_to_mime_type($info[2]);
        header("Content-type: $mime");
        $output = NULL;
      break;
      case 'file':
        $output = $file;
      break;
      case 'return':
        return $image_resized;
      break;
      default:
      break;
    }
    
    # Writing image according to type to the output destination
    switch ( $info[2] ) {
      case IMAGETYPE_GIF: imagegif($image_resized, $output); break;
      case IMAGETYPE_JPEG: imagejpeg($image_resized, $output); break;
      case IMAGETYPE_PNG: imagepng($image_resized, $output); break;
      default: return false;
    }

    return true;
  }

function truncate($string, $limit, $break=" ", $pad="...") {
	 // return with no change if string is shorter than $limit 
	 if(strlen($string) <= $limit) return $string; 
	 // is $break present between $limit and the end of the string? 
	 if(false !== ($breakpoint = strpos($string, $break, $limit))) 
	 { 
	 	if($breakpoint < strlen($string) - 1) 
	 	{ 
	 		$string = substr($string, 0, $breakpoint) . $pad; 
	 	} 
	 } return $string; 
	 }

function dayshuffle($targetArray, $repetitions) {
	
	//echo"bla";
	for($i = 1; $i <= $repetitions; $i++) {
	$storage = array_shift($targetArray);
	//echo($storage);
	
	array_push($targetArray, $storage);
	
	}
	//var_dump($targetArray);
	return $targetArray;
	
	}

function dayoffset($targetArray, $indexFrom, $indexTo) {

    $targetElement = $targetArray[$indexFrom];
    $magicIncrement = ($indexTo - $indexFrom) / abs ($indexTo - $indexFrom);

    for ($Element = $indexFrom; $Element != $indexTo; $Element += $magicIncrement){
        $targetArray[$Element] = $targetArray[$Element + $magicIncrement];
    }

    $targetArray[$indexTo] = $targetElement;
    
	 return $targetArray;
	}
	
function delete_event($event_id, $location_id) {
	// prepare delete query
	$deleteevent = "DELETE FROM `events` WHERE event_id = $event_id";
	//echo($deleteevent);
	$deleteeventquery = mysql_query($deleteevent);
	
	// redirect user to location 
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "location.php?location_id=";
			header("Location: http://$host$path$site$location_id");
			exit;	
	}

function delete_exhibition($ex_id, $location_id) {
	// prepare delete query
	$deleteex = "DELETE FROM `exhibitions` WHERE ex_id = $ex_id ";
	//echo($deleteex);
	$deleteexquery = mysql_query($deleteex);
	
	// redirect user to location 
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "location.php?location_id=";
			header("Location: http://$host$path$site$location_id");
			exit;	
	
	}

function delete_ex_events($ex_id, $location_id) {
	// prepare delete query
	$deleteevent = "DELETE FROM `events` WHERE ex_id = $ex_id ";
	//echo($deleteevent);
	$deleteeventquery = mysql_query($deleteevent);
	return;
	
	// redirect user to location 
	/*
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "location.php?location_id=";
			header("Location: http://$host$path$site$location_id");
			exit;		
	*/
	
	}

function delete_stage($stage_id, $location_id) {
	// prepare delete query
	$deleteevent = "DELETE FROM `stage` WHERE stage_id = $stage_id ";
	//echo($deleteevent);
	$deleteeventquery = mysql_query($deleteevent);
	
	// redirect user to location 
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "location.php?location_id=";
			header("Location: http://$host$path$site$location_id");
			exit;		
	}
	
function delete_stage_events($stage_id, $location_id) {
	// prepare delete query
	$deleteevent = "DELETE FROM `events` WHERE stage_id = $stage_id ";
	//echo($deleteevent);
	$deleteeventquery = mysql_query($deleteevent);
	
	// redirect user to location 
	/*
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "location.php?location_id=";
			header("Location: http://$host$path$site$location_id");
			exit;		
	*/
	}
	
function getopeninghours($location_id) {
	
	// (over)write openinghours in LOCATION according to delivered array

	$open_s = "SELECT monday_s, tuesday_s, wednesday_s, thursday_s, friday_s, saturday_s, sunday_s					
						 FROM location WHERE location_id = $location_id";
	$result_s = mysql_query($open_s) or die ("no openhinghourquery");
	$s_array = mysql_fetch_array($result_s);
	
	$open_e = "SELECT monday_e, tuesday_e, wednesday_e, thursday_e, friday_e, saturday_e, sunday_e					
						 FROM location WHERE location_id = $location_id";
	$result_e = mysql_query($open_e) or die ("no openhinghourquery");
	$e_array = mysql_fetch_array($result_e);

	$day = array(
				0 => Monday,
				1 => Tuesday,
				2 => Wednesday,
				3 => Thursday,
				4 => Friday,
				5 => Saturday,
				6 => Sunday,
				);

	$f = "G:i";
	for($i = 0; $i < 7; $i++) { 
			$start = $s_array[$i];
			$start = (date($f, strtotime($start)));
			$end = $e_array[$i];
			$end = (date($f, strtotime($end)));
			$hours = $start." - ".$end;
		if(strtotime($start) != strtotime('00:00:00')) {
			//echo($day[$i]);
			//echo": ";
			//echo($hours);
			//echo"<br />";
			$z = $day[$i];
			$hours_array[$z] = $hours;
			}		
		}
	return $hours_array;
	}
	
function getcustomopeninghours($ex_id) {
	
	// (over)write openinghours in LOCATION according to delivered array

	$open_s = "SELECT monday_s, tuesday_s, wednesday_s, thursday_s, friday_s, saturday_s, sunday_s					
						 FROM exhibitions WHERE ex_id = $ex_id";
	$result_s = mysql_query($open_s) or die ("no openhinghourquery");
	$s_array = mysql_fetch_array($result_s);
	
	$open_e = "SELECT monday_e, tuesday_e, wednesday_e, thursday_e, friday_e, saturday_e, sunday_e					
						 FROM exhibitions WHERE ex_id = $ex_id";
	$result_e = mysql_query($open_e) or die ("no openhinghourquery");
	$e_array = mysql_fetch_array($result_e);

	$day = array(
				0 => Monday,
				1 => Tuesday,
				2 => Wednesday,
				3 => Thursday,
				4 => Friday,
				5 => Saturday,
				6 => Sunday,
				);

	$f = "G:i";
	for($i = 0; $i < 7; $i++) { 
			$start = $s_array[$i];
			$start = (date($f, strtotime($start)));
			$end = $e_array[$i];
			$end = (date($f, strtotime($end)));
			$hours = $start." - ".$end;
		if(strtotime($start) != strtotime('00:00:00')) {
			/*echo($day[$i]);
			echo": ";
			echo($hours);
			echo"<br />";
			*/
			$z = $day[$i];
			$hours_array[$z] = $hours;
			}		
		}
	return $hours_array;
	}	

function getcustomopeninghours_start($ex_id) {
	
	// (over)write openinghours in LOCATION according to delivered array

	$open_s = "SELECT monday_s, tuesday_s, wednesday_s, thursday_s, friday_s, saturday_s, sunday_s					
						 FROM exhibitions WHERE ex_id = $ex_id";
	$result_s = mysql_query($open_s) or die ("no openhinghourquery");
	$s_array = mysql_fetch_assoc($result_s);
	
	return $s_array;
	}
	
function getcustomopeninghours_end($ex_id) {
	
	// (over)write openinghours in LOCATION according to delivered array

	$open_e = "SELECT monday_e, tuesday_e, wednesday_e, thursday_e, friday_e, saturday_e, sunday_e					
						 FROM exhibitions WHERE ex_id = $ex_id";
	$result_e = mysql_query($open_e) or die ("no openhinghourquery");
	$e_array = mysql_fetch_assoc($result_e);
	
	return $e_array;
	}
	
function setopeninghours($location_id,$openinghours) {
	
	// (over)write openinghours in LOCATION according to delivered array
	
	}
	
function savecustomopeninghours($ex_id, $hour_s, $minute_s, $hour_e, $minute_e) {
	
	$key_s = array(
				0 => monday_s,
				1 => tuesday_s,
				2 => wednesday_s,
				3 => thursday_s,
				4 => friday_s,
				5 => saturday_s,
				6 => sunday_s,
				);
				
	$key_e = array(
				0 => monday_e,
				1 => tuesday_e,
				2 => wednesday_e,
				3 => thursday_e,
				4 => friday_e,
				5 => saturday_e,
				6 => sunday_e,
				);
	
	
	for($i = 0; $i < 7; $i++) { 


	if($hour_s[$i] != "") {
	$time_s[$i] = $hour_s[$i].":".$minute_s[$i];
	$time_e[$i] = $hour_e[$i].":".$minute_e[$i];
	}
	
	if($hour_s[$i] == "") {
	unset($key_s[$i]);
	unset($key_e[$i]);	
	}
	}
	
		$time_s = array_combine($key_s, $time_s);
		$time_e = array_combine($key_e, $time_e);
		$time = array_merge($time_s, $time_e);
		//var_dump($time);	

	foreach ($time as $key => $value) {
		$ohw = "UPDATE `exhibitions` SET $key = '$value' WHERE ex_id = $ex_id";
		//echo($ohw);
		$ohwr = mysql_query($ohw) or die ("no ohwquery");
				
	}	
	return TRUE;
	}	
	
function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}	
	
function pinterest($id,$title,$img_480) { 

if($img_480 != "") {
// define necessary vars
$host = $_SERVER['HTTP_HOST'];
$path = "/img/event/".$id."_480.jpg";
$page = $host.$path;

$site = curPageURL();


?>
	
	<a data-pin-config="beside" href="//pinterest.com/pin/create/button/?url=<?php echo($site)?>&media=<?php echo($page); ?>&description=<?php echo($title);?>" data-pin-do="buttonPin" ><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" /></a>
	
<?php	} }


function getlocationdata($id) {
	
$query = "SELECT * FROM `location` WHERE location_id = $id";
$result = mysql_query($query) or die ("no locationquery");
$location = mysql_fetch_assoc($result);
return $location;
	}

function geteventdata($id) {
	
$query = "SELECT * FROM `events` WHERE event_id = $id";
$result = mysql_query($query) or die ("no eventquery");
$event = mysql_fetch_assoc($result);
	
return $event;	
	
	}
	
function getfestivaldata($id) {
	
$query = "SELECT * FROM `festivals` WHERE f_id = $id";
$result = mysql_query($query) or die ("no eventquery");
$event = mysql_fetch_assoc($result);
	
return $festival;	
	
	}
	
function getuserdata($id) {
	
$query = "SELECT id, user, mail, verification, created, modified, img_480, img_320, img_240, img_160, active, status FROM `users` WHERE id = $id";
$result = mysql_query($query) or die ("no userquery");
$user = mysql_fetch_assoc($result);	

return $user;	
	}
	
function isuseractive($id) {

if(($user['active']) == 0) {
	$username = 'inactive user';
	}
	else {
$username = $user['user']; 
	}	
}
	
function getexhibitiondata($id) {
	
$query = "SELECT * FROM `exhibitions` WHERE ex_id = $id";
$result = mysql_query($query) or die ("no exquery");
$exhibition = mysql_fetch_assoc($result);

return $exhibition;
	}
	
	
function getgenrename($genre) {
$query = "SELECT name FROM styles WHERE type_id = $genre";
$result = mysql_query($query) or die ("no genrequery");
$genre = mysql_fetch_assoc($result);
$genre = $genre['name'];
return $genre;
}

function gettypename($genre) {
$genre = $genre/1000; 
//echo($genre);
$type = 1000 * floor($genre);
//echo($type);
$query = "SELECT * FROM eventtypes WHERE type_id = $type";
$result = mysql_query($query) or die ("no typequery");
$type = mysql_fetch_assoc($result);
$type = $type['name'];
return $type;
}
	

function displaylocation_right($location_id) { 

$location = getlocationdata($location_id);

$hours = getopeninghours($location_id);

?>
 	<div class="eventlocation">
 	<a href="location.php?location_id=<?php echo($location_id); ?>"><img src="/img/location/<?php echo($location['img_240']) ?>" alt="" ></a>
		<div class="eventlocation_text">
		<a href="location.php?location_id=<?php echo($location_id); ?>" ><?php echo $location['l_name'] ?></a>
		<br />
		<?php echo $location['streetnumber']; ?> <?php echo $location['street']; ?>
		<?php if($location['additional'] != "") { echo"<br />"; echo($location['additional']); }?>
		<br />
		<?php echo $location['postalcode']; ?> <?php echo $location['city']; ?>, <?php echo $location['province']; ?>
		<br />
		<?php if(isset($location['url'])) {?> 	<a href="<?php echo $location['url']; ?>" target="blank">Website</a> <?php } ?>
		
		<?php if($hours == TRUE) { ?>
		<h2>Opening hours:</h2>
		<p>
		<?php foreach ($hours as $key => $value) { ?>
		<?php echo($key);?>: <?php echo($value); ?><br />
		
		<?php } } ?>
		</p>
		</div>
	</div>

<?php 
}

function feedbackmail() {
	
if($_POST['feedback_text'] == "") 
{$status = FALSE;
return $status; }

else{

if(isset($_POST['fbb'])) {
	$mail = "listings@schmittens.net";
	$subject = $_POST['feedback_type'];
	$messagemain = $_POST['feedback_text'];
	$u = $_POST['user'];
	$e = $_POST['event'];
	$l = $_POST['location'];
	$message = "
Message:
$messagemain 
					
User-ID: $u 
Event-ID: $e 
Location-ID: $l 

Event: http://www.schmittens.net/event.php?event_id=$e
";
					

          if(!mail($mail, $subject, $message,  "FROM: Schmittens.net <support@schmittens.net>")){ 
            $mailfail = "<p>Sending email failed, please contact support@schmittens.net!</p>"; 
          }else{ 
          	$mailconfirm = "<p>Mail was sent successfully.</p>";
         } 
	$fbq = "INSERT INTO `feedback`(`subject`, `message`, `event_id`, `user_id`, `location_id`, `status`) 
	VALUES ('$subject','$messagemain','$e','$u','$l','0') ";
	$fbqr = mysql_query($fbq) or die('no feedback query');
	$status = "Your feedback was successfully submitted. We'll look into it ASAP. Thank you for your help!";
	return $status;
	}	
	
	}
}

function feedbackdisplay($status) {
	 
if(($status == FALSE) && ($_POST['fbb'] == TRUE)) {?>
<div class="alerttext"><?php echo"Please describe the issue."; unset($_POST['fbb']);?> </div>

<?php }
if(($_SESSION['in'] == TRUE) && ($_POST['fbb'] == FALSE)){?>
<div class="feedback">
<h3>Send feedback for this event</h3>
<div class="toggle">
<form name="feedback" method="post" enctype="multipart/form-data">
<select name="feedback_type">
	<option value="Wrong information (please specify)">Wrong information (please specify)</option>
	<option value="Event cancelled">Event cancelled</option>
	<option value="I am the organiser & would like to edit the event">I am the organiser & would like to edit the event</option>
	<option value="I run the location & would like to edit the location">I run the location & would like to edit the location</option>
	<option value="Anything else">Anything else</option>
</select>
<br />Please describe the issue:
<textarea rows="3" cols="60" name="feedback_text"></textarea><br />
<input type="hidden" name="user" value="<?php echo($_SESSION['id']); ?>"/>
<input type="hidden" name="event" value="<?php echo($eventqueryresult_array['event_id']); ?>"/>
<input type="hidden" name="location" value="<?php echo($location_id); ?>"/>
<input type="submit" name="fbb" value="Send feedback" />
</form>


</div>
</div>
<?php } 	
	
	}
	
function display_youtube($youtube) {
	if($youtube != "") { ?>
<div >
<iframe width="480" height="270" src="http://www.youtube.com/embed/<?php echo($youtube); ?>" frameborder="0" allowfullscreen></iframe>
</div>

<?php }
	}
	
function display_editbutton($user_id,$session_id,$id,$genre) {

$type = gettypename($genre);
//echo($type);

if($type == "Party") {$a = "event"; $b = "e_event.php";}
if($type == "Concert") {$a = "event"; $b = "e_event.php";}
if($type == "Art") {$a = "ex"; $b = "e_exhibition.php";}
if($type == "Stage") {$a = "stage"; $b = "e_stage.php";}
if($type == "Other") {$a = "event"; $b = "e_event.php";}

if($user_id == $session_id) { ?>
<form action="<?php echo($b); ?>" method="get">
	<input type="hidden" name="<?php echo($a);?>_id" value="<?php echo($id) ?>" />				
	<input type="submit" value="Edit event" />		

</form>

<?php }	
	}
	
function displaygenres($etype,$genre) {
$upperlimit = $etype + 1000;
$query = "SELECT * FROM `styles` WHERE type_id >= '$etype' AND type_id < '$upperlimit' ORDER BY type_id ASC";
//echo($query);
$result = mysql_query($query) or die("no stylesquery");
$types = mysql_fetch_assoc($result);


?>
<table summary="" >
<?php 
$i = 1;

do 
	{
		if($i == 1) { ?><tr> <?php }
	?><td style="background-color: <?php echo($types['colour']); ?> "><input type="radio" name="genre" value="<?php echo($types['type_id']); ?>" <?php if($genre == $types['type_id']) { echo "checked = 'checked'"; } ?>  /><?php echo($types['name']); ?></td>
	<?php
		if($i == 4) { ?></tr> <?php }
		$i = $i + 1;
		if($i > 4) { $i = 1; }
	} while($types = mysql_fetch_assoc($result)) ;?>

</table> <?php

	}

function displayeventsinlocation($location_id) {
	
$query = "SELECT * FROM `events` WHERE location_id = $location_id AND starttime > NOW() AND type != 'Exhibition' ORDER BY starttime ASC ";
$result = mysql_query($query) or die ("no eventslistquery");
$nevents = mysql_num_rows($result);
// echo($neventsinlocation);
$eventsinlocation = mysql_fetch_assoc($result);

if($nevents > 0) {
	echo"<div class='notice'>";
	echo"<h1><a href='#'>Existing events >> </a></h1>";
?>
	<table class="toggle">
<?php 

$formatlist = "M j, g a";

do { 
// prepare username-query
$creator = $eventsinlocation['created_by'];
$userdata = getuserdata($creator);

$format = "g a D, M j";
$starttime = $eventsinlocation['starttime'];

?>

	<tr>
		<td><?php $phpdate = strtotime($starttime); echo(date($formatlist, $phpdate)); ?></td>
		<td><a href="event.php?event_id=<?php echo $eventsinlocation['event_id']; ?>" ><?php echo $eventsinlocation['title']; ?></a></td>
		<td><?php echo $eventsinlocation['type']; ?></td>
		<td><?php 
			if(($_SESSION['id']) == $creator) { ?>
			<a href="event.php?event_id=<?php echo $eventsinlocation['event_id'] ?>" >Edit</a>			
			<?php			
			}
			?>	
	</td>
<?php 
} while ($eventsinlocation = mysql_fetch_assoc($result));
?> 
</table> 	</div><?php
	} 
	}
	
	
function displayeventsinlocation_listmode($location_id) {
	
$query = "SELECT * FROM `events` WHERE location_id = $location_id AND starttime > NOW() AND type != 'Exhibition' AND type != 'Stage' ORDER BY starttime ASC ";
$result = mysql_query($query) or die ("no eventslistquery");
$nevents = mysql_num_rows($result);
// echo($neventsinlocation);
$eventsinlocation = mysql_fetch_assoc($result);

if($nevents > 0) {
	echo"<div class='eventlist'>";
	echo"<h1>Events</h1>";
?>
	<table class="evlist">
<?php 

$formatlist = "M j, g a";
$c = 1;

do { 
// prepare username-query
$creator = $eventsinlocation['created_by'];
$userdata = getuserdata($creator);

$format = "g a D, M j";
$starttime = $eventsinlocation['starttime'];

?>

	<tr <?php if($c % 2 == 0) {echo"class='listitem1' "; }  else {echo"class='listitem2'  ";}?>  >
		<td class="eventlistpic"><?php if($eventsinlocation['img_160'] != "") {?><a href="event.php?event_id=<?php echo($eventsinlocation['event_id']); ?>" ><img src="img/event/<?php echo($eventsinlocation['img_160']); ?>" alt="" ></a><?php } ?>
		<td class="eventlistinfo"><b><a href="event.php?event_id=<?php echo($eventsinlocation['event_id']); ?>" ><?php echo $eventsinlocation['title']; ?></a></b><br />
				<?php $phpdate = strtotime($starttime); echo(date($formatlist, $phpdate)); ?> <br />
		<?php if($eventsinlocation['artist'] != "") {echo $eventsinlocation['artist']; echo"<br />"; }?>
		<?php $shortdescription = truncate($eventsinlocation['description'], 90); if($eventsinlocation['description'] != '') {echo($shortdescription); echo"<br />"; }	?>
		<td class="eventlistextra">
		<?php 
			if(($_SESSION['id']) == $creator) { ?>
			
			<a href="event.php?event_id=<?php echo $eventsinlocation['event_id'] ?>" >Edit</a>	
			
			<?php	
			}
			?>	
		</td>		
	</tr>

<?php 
$c++;
} while ($eventsinlocation = mysql_fetch_assoc($result));
?> 
</table> 	</div><?php
	} 
	}
	
	
function displayeventsinlocation_listmode_user($user_id) {
	
$query = "SELECT event_id, title, artist, description, events.img_160, events.starttime, events.created_by, location.l_name, location.location_id FROM `events` JOIN `location` on events.location_id = location.location_id WHERE events.created_by = $user_id AND starttime > NOW() AND events.type != 'Exhibition' AND events.type != 'Stage' ORDER BY starttime ASC";
//echo($query);
$result = mysql_query($query) or die ("no eventslistquery");
$nevents = mysql_num_rows($result);
// echo($neventsinlocation);
$eventsinlocation = mysql_fetch_assoc($result);

if($nevents > 0) {
	echo"<div class='eventlist'>";
	echo"<h1>Events</h1>";
?>
	<table class="evlist">
<?php 

$formatlist = "M j, g a";
$c = 1;

do { 
// prepare username-query
$creator = $eventsinlocation['created_by'];
$userdata = getuserdata($creator);

$format = "g a D, M j";
$starttime = $eventsinlocation['starttime'];
//echo($starttime);
?>

	<tr <?php if($c % 2 == 0) {echo"class='listitem1' "; }  else {echo"class='listitem2'  ";}?>  >
		<td class="eventlistpic"><?php if($eventsinlocation['img_160'] != "") {?><a href="event.php?event_id=<?php echo($eventsinlocation['event_id']); ?>" ><img src="img/event/<?php echo($eventsinlocation['img_160']); ?>" alt="" ></a><?php } ?>
		<td class="eventlistinfo"><b><a href="event.php?event_id=<?php echo($eventsinlocation['event_id']); ?>" ><?php echo $eventsinlocation['title']; ?></a></b> @ <a href="location.php?location_id=<?php echo($eventsinlocation['location_id']); ?>" ><?php echo $eventsinlocation['l_name']; ?></a><br />
				<?php $phpdate = strtotime($starttime); echo(date($formatlist, $phpdate)); ?> <br />
		<?php if($eventsinlocation['artist'] != "") {echo $eventsinlocation['artist']; echo"<br />"; }?>
		<?php $shortdescription = truncate($eventsinlocation['description'], 90); if($eventsinlocation['description'] != '') {echo($shortdescription); echo"<br />"; }	?>
		<td class="eventlistextra">
		<?php 
			if(($_SESSION['id']) == $creator) { ?>
			
			<a href="event.php?event_id=<?php echo $eventsinlocation['event_id'] ?>" >Edit</a>	
			
			<?php	
			}
			?>	
		</td>		
	</tr>

<?php 
$c++;
} while ($eventsinlocation = mysql_fetch_assoc($result));
?> 
</table> 	</div><?php
	} 
	}


function displayexhibitionsinlocation($location_id) {
$query = "SELECT * FROM `exhibitions` WHERE location_id = $location_id AND enddate > NOW() ORDER BY startdate ASC ";
$result = mysql_query($query) or die ("no exlistquery");
$nex = mysql_num_rows($result);
// echo($neventsinlocation);
$exinlocation = mysql_fetch_assoc($result);	

if($nex > 0) {
	echo"<div class='notice'>";
	echo"<h1><a href='#'>Existing exhibitions >> </a></h1>";
?>
	<table class="toggle">
<?php 

$formatlist = "M j";

do { 
// prepare username-query
$creator = $exinlocation['created_by'];
$userdata = getuserdata($creator);

$format = "g a D, M j";
$startdate = $exinlocation['startdate'];
$enddate = $exinlocation['enddate'];

?>

	<tr>
		<td><?php $phpdate = strtotime($startdate); echo(date($formatlist, $phpdate)); ?> - <?php $phpdate = strtotime($enddate); echo(date($formatlist, $phpdate)); ?></td>
		<td><a href="exhibition.php?ex_id=<?php echo $exinlocation['ex_id']; ?>" ><?php echo $exinlocation['e_title']; ?></a></td>
		<td><?php echo $exinlocation['type']; ?></td>
		<td><?php 
			if(($_SESSION['id']) == $creator) { ?>
			<a href="exhibition.php?ex_id=<?php echo $exinlocation['ex_id'] ?>" >Edit</a>			
			<?php			
			}
			?>	
	</td>
<?php 
} while ($exinlocation = mysql_fetch_assoc($result));
?> 
</table> 	</div><?php
	} 
	}

function displayexhibitionsinlocation_listmode($location_id) {
$query = "SELECT * FROM `exhibitions` WHERE location_id = $location_id AND enddate > NOW() ORDER BY startdate ASC ";
$result = mysql_query($query) or die ("no exlistquery");
$nex = mysql_num_rows($result);
// echo($neventsinlocation);
$exinlocation = mysql_fetch_assoc($result);	

if($nex > 0) {
	echo"<div class='eventlist'>";
	echo"<h1>Exhibitions</h1>";
?>
	<table class="evlist">
<?php 

$formatlist = "M j";
$c = 1;

do { 
// prepare username-query
$creator = $exinlocation['created_by'];
$userdata = getuserdata($creator);

$format = "g a D, M j";
$startdate = $exinlocation['startdate'];
$enddate = $exinlocation['enddate'];

?>
	<tr <?php if($c % 2 == 0) {echo"class='listitem1' "; }  else {echo"class='listitem2'  ";}?>  >
		<td class="eventlistpic"><?php if($exinlocation['img_160'] != "") {?><a href="exhibition.php?ex_id=<?php echo($exinlocation['ex_id']); ?>" ><img src="img/exhibition/<?php echo($exinlocation['img_160']); ?>" alt="" ></a><?php } ?>
		<td class="eventlistinfo"><b><a href="exhibition.php?ex_id=<?php echo($exinlocation['ex_id']); ?>" ><?php echo $exinlocation['e_title']; ?></a></b><br />
				<?php $phpdate = strtotime($startdate); echo(date($formatlist, $phpdate)); ?> - <?php $phpdate = strtotime($enddate); echo(date($formatlist, $phpdate)); ?><br />
		<?php if($exinlocation['artist'] != "") {echo $exinlocation['artist']; echo"<br />"; }?>
		<?php $shortdescription = truncate($exinlocation['description'], 90); if($exinlocation['description'] != '') {echo($shortdescription); echo"<br />"; }	?>
		<td class="eventlistextra">
		<?php 
			if(($_SESSION['id']) == $creator) { ?>
			
			<a href="exhibition.php?ex_id=<?php echo $exinlocation['ex_id'] ?>" >Edit</a>	
			
			<?php	
			}
			?>	
		</td>		
	</tr>

<?php 
$c++;		
} while ($exinlocation = mysql_fetch_assoc($result));
?> 
</table> 	</div><?php
	} 
	}
	

function displayexhibitionsinlocation_listmode_user($user_id) {
$query = "SELECT ex_id, e_title, artist, description, exhibitions.img_160, exhibitions.created_by, location.l_name, location.location_id FROM `exhibitions` JOIN `location` on exhibitions.location_id = location.location_id WHERE exhibitions.created_by = $user_id AND enddate > NOW() ORDER BY startdate ASC";
//echo($query);
$result = mysql_query($query) or die ("no exlistquery");
$nex = mysql_num_rows($result);
// echo($neventsinlocation);
$exinlocation = mysql_fetch_assoc($result);	

if($nex > 0) {
	echo"<div class='eventlist'>";
	echo"<h1>Exhibitions</h1>";
?>
	<table class="evlist">
<?php 

$formatlist = "M j";
$c = 1;

do { 
// prepare username-query
$creator = $exinlocation['created_by'];
$userdata = getuserdata($creator);

$format = "g a D, M j";
$startdate = $exinlocation['startdate'];
$enddate = $exinlocation['enddate'];

?>
	<tr <?php if($c % 2 == 0) {echo"class='listitem1' "; }  else {echo"class='listitem2'  ";}?>  >
		<td class="eventlistpic"><?php if($exinlocation['img_160'] != "") {?><a href="exhibition.php?ex_id=<?php echo($exinlocation['ex_id']); ?>" ><img src="img/exhibition/<?php echo($exinlocation['img_160']); ?>" alt="" ></a><?php } ?>
		<td class="eventlistinfo"><b><a href="exhibition.php?ex_id=<?php echo($exinlocation['ex_id']); ?>" ><?php echo $exinlocation['e_title']; ?></a></b> @ <a href="location.php?location_id=<?php echo($exinlocation['location_id']); ?>" ><?php echo $exinlocation['l_name']; ?></a><br />
				<?php $phpdate = strtotime($startdate); echo(date($formatlist, $phpdate)); ?> - <?php $phpdate = strtotime($enddate); echo(date($formatlist, $phpdate)); ?><br />
		<?php if($exinlocation['artist'] != "") {echo $exinlocation['artist']; echo"<br />"; }?>
		<?php $shortdescription = truncate($exinlocation['description'], 90); if($exinlocation['description'] != '') {echo($shortdescription); echo"<br />"; }	?>
		<td class="eventlistextra">
		<?php 
			if(($_SESSION['id']) == $creator) { ?>
			
			<a href="exhibition.php?ex_id=<?php echo $exinlocation['ex_id'] ?>" >Edit</a>	
			
			<?php	
			}
			?>	
		</td>		
	</tr>

<?php 
$c++;		
} while ($exinlocation = mysql_fetch_assoc($result));
?> 
</table> 	</div><?php
	} 
	}



	
function displaystageinlocation_listmode($location_id) {
	
//get all stage events in the location
$query = "SELECT stage_id, title, artist, description, img_160, created_by FROM `stage` WHERE location_id = $location_id";
//echo($query);
$result = mysql_query($query) or die ("no stagequery");
$nse = mysql_num_rows($result);
if($nse > 0) { ?>

<div class='eventlist'>
<h1>Theatre/Stage</h1>
<table class='evlist'>

<?php

$sil = mysql_fetch_assoc($result);


//check each for current events

$c = 1;

do {
	
	$s_id = $sil['stage_id'];
	$q = "SELECT 1 FROM `events` WHERE stage_id = $s_id";
	$r = mysql_query($q);
	$n = mysql_num_rows($r);
	
	if($n > 0) {

		?>
		
	<tr <?php if($c % 2 == 0) {echo"class='listitem1' "; }  else {echo"class='listitem2'  ";}?>  >
		<td class="eventlistpic"><?php if($sil['img_160'] != "") {?><a href="stage.php?stage_id=<?php echo($sil['stage_id']); ?>" ><img src="img/stage/<?php echo($sil['img_160']); ?>" alt="" ></a><?php } ?>
		<td class="eventlistinfo"><b><a href="stage.php?stage_id=<?php echo($sil['stage_id']); ?>" ><?php echo $sil['title']; ?></a></b><br />
		<?php if($sil['artist'] != "") {echo $sil['artist']; echo"<br />"; }?>
		<?php $shortdescription = truncate($sil['description'], 90); echo($shortdescription);	?>
		<td class="eventlistextra">
		<?php 
			if(($_SESSION['id']) == $sil['created_by']) { ?>
			
			<a href="stage.php?stage_id=<?php echo $sil['stage_id']; ?>" >Edit</a>	
			
			<?php	

			}
			?>	
		</td>		
	</tr>
		

		<?php		
		
		}
				$c++;		

	} while ($sil = mysql_fetch_assoc($result));
	?>
	</table>
	</div>
	<?php
	}
	}

function displaystageinlocation_listmode_user($user_id) {
	
//get all stage events for this user
$query = "SELECT stage_id, title, artist, description, stage.img_160, stage.created_by, location.l_name, location.location_id FROM `stage` JOIN `location` on stage.location_id = location.location_id WHERE stage.created_by = $user_id";
//echo($query);
$result = mysql_query($query) or die ("no stagequery");
$nse = mysql_num_rows($result);
if($nse > 0) { ?>

<div class='eventlist'>
<h1>Theatre/Stage</h1>
<table class='evlist'>

<?php

$sil = mysql_fetch_assoc($result);


//check each for current events

$c = 1;

do {
	
	$s_id = $sil['stage_id'];
	$q = "SELECT 1 FROM `events` WHERE stage_id = $s_id";
	$r = mysql_query($q);
	$n = mysql_num_rows($r);
	
	if($n > 0) {

		?>
		
	<tr <?php if($c % 2 == 0) {echo"class='listitem1' "; }  else {echo"class='listitem2'  ";}?>  >
		<td class="eventlistpic"><?php if($sil['img_160'] != "") {?><a href="stage.php?stage_id=<?php echo($sil['stage_id']); ?>" ><img src="img/stage/<?php echo($sil['img_160']); ?>" alt="" ></a><?php } ?>
		<td class="eventlistinfo"><b><a href="stage.php?stage_id=<?php echo($sil['stage_id']); ?>" ><?php echo $sil['title']; ?></a></b> @ <a href="location.php?location_id=<?php echo($sil['location_id']); ?>" ><?php echo $sil['l_name']; ?></a><br />
		<?php if($sil['artist'] != "") {echo $sil['artist']; echo"<br />"; }?>
		<?php $shortdescription = truncate($sil['description'], 90); echo($shortdescription);	?>
		<td class="eventlistextra">
		<?php 
			if(($_SESSION['id']) == $sil['created_by']) { ?>
			
			<a href="stage.php?stage_id=<?php echo $sil['stage_id']; ?>" >Edit</a>	
			
			<?php	

			}
			?>	
		</td>		
	</tr>
		

		<?php		
		
		}
				$c++;		

	} while ($sil = mysql_fetch_assoc($result));
	?>
	</table>
	</div>
	<?php
	}
	}
	
function display_imageupload($id,$genre) {
	
	$type = gettypename($genre);
	$a = $id."_480.jpg";
	if($type == "Party") {$b = "event"; $c = "event_id";}
	if($type == "Concert") {$b = "event"; $c = "event_id";}
	if($type == "Art") {$b = "exhibitions"; $c = "ex_id"; }
	if($type == "Stage") {$b = "stage"; $c = "stage_id";}
	if($type == "Other") {$b = "event"; $c = "event_id";}
	//echo($a);
	?>
<img src="img/<?php echo($b);?>/<?php echo($a); ?>" alt=""/>
<p>Upload Image:</p>

<form name="newad" method="post" enctype="multipart/form-data" action="">
<table>
<tr><td><input type="file" name="image"></td></tr>
<tr><td><input type="hidden" name="<?php echo($c);?>" value="<?php echo($id); ?>"/>
			<input type="hidden" name="genre" value="<?php echo($genre); ?>"/>
			<input name="imageupload" type="submit" value="Upload image"></td></tr>
</table>
</form>

	
	<?php

}

function imageupload($id,$genre) {
	//echo($genre);
	
	$type = gettypename($genre);
	if($type == "Party") {$b = "event"; $c = "event_id";}
	if($type == "Concert") {$b = "event"; $c = "event_id";}
	if($type == "Art") {$b = "exhibitions"; $c = "ex_id";}
	if($type == "Stage") {$b = "stage"; $c = "stage_id";}
	if($type == "Other") {$b = "event"; $c = "event_id";}

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
		$imgeventid = $id;		
		
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
		//echo($size);
		
		//compare the size with the maxim size we defined and print error if bigger
		if ($size > MAX_SIZE*1024)
		{
		echo '<h1>You have exceeded the size limit!</h1>';
		$errors=1;
		}
		echo($b);
		//we will give an unique name, for example the time in unix time format
		$image_name=$imgeventid."_original".'.'.$extension;
		//the new name will be containing the full path where will be stored (images folder)
		$newname="img/".$b."/".$image_name;
		echo"<br/>";
		echo($newname);
		//we verify if the image has been uploaded, and print error instead
		$copied = copy($_FILES['image']['name'], $newname);
		if (!$copied)
		{
		echo '<h1>Copy unsuccessfull!</h1>';
		$errors=1;
		}}}/*}*/
		
		//If no errors registred, print the success message
		if(isset($_POST['imageupload']) && !$errors)
		{
		// write imagename to db
		$writepic = "UPDATE $b SET `img_original`= '$image_name' WHERE $c = $imgeventid";
		echo($writepic);
		$writepicquery = mysql_query($writepic);
		//echo($writepicquery);
		
		// create 3 thumbnail sizes		
		
			$eid = $id;
			
			$imagepath = "img/".$b."/".$eid."_original.jpg";
			echo($imagepath);
			
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
			$filename = "img/".$b."/".$eid.$suffix;
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
			$imgupdate = "UPDATE $b SET $resize = '$imgname', img_present = 1 WHERE $c = '$eid'";
			$imgupdatequery = mysql_query($imgupdate); 
			
			$c = $c + 1;		
			}	
			while($c < 5) ;
				
		}
	
	
	}
	
function getlocation_id_stage($stage_id) {
	$query = "SELECT location_id FROM `stage` WHERE stage_id = $stage_id";
	//echo($query);
	$result = mysql_query($query);
	$location_id = mysql_fetch_row($result);
	$location_id = $location_id['0'];
	return $location_id;
	}
	
	
function getlocation_id_exhibition($ex_id) {
	$query = "SELECT location_id FROM `exhibitions` WHERE ex_id = $ex_id";
	//echo($query);
	$result = mysql_query($query);
	$location_id = mysql_fetch_row($result);
	$location_id = $location_id['0'];
	return $location_id;
	}
	
	
function display_deletebutton_event($event_id,$location_id) { ?>
<div class="alerttext">	
	<form action="e_event.php" method="post">
	<input type="hidden" name="location_id" value="<?php echo($location_id); ?>"/>
	<input type="hidden" name="event_id" value="<?php echo($event_id); ?>"/>
	<input type="hidden" name="delete" value="delete">
	<input type="submit" name="delete" value="Delete this event">
</form> 		
Warning! This cannot be undone!
</div>
	
	<?php }
	
function display_eventcreator($event_id,$genre) {
	
	//echo($genre);
	
	if($genre < 3000) { $type = "events.event_id"; $type2 = "events"; }
	if(($genre < 4000) && ($genre >= 3000)) { $type = "exhibitions.ex_id"; $type2 = "exhibitions"; }
	if(($genre < 5000) && ($genre >= 4000)) { $type = "stage.stage_id"; $type2 = "stage"; }
	if($genre >= 5000) { $type = "events.event_id"; $type2 = "events"; }
	
$query = "SELECT $type2.created, users.user, users.img_240 FROM `$type2` JOIN `users` on $type2.created_by = users.id WHERE $type = $event_id";
//echo($query);
$result = mysql_query($query);
$array = mysql_fetch_assoc($result);
?>
<div>
	<div style="background-image: url(../img/user/<?php echo($array['img_240']);?>); height: 135px; width: 240px;"></div>
	<div style="font-size: 0.8em;">Event created <?php $phpdate = strtotime($array['created']); echo(date('M j', $phpdate)); echo" by "; echo($array['user']); ?></div>
</div>	
<?php
	}	
	
	
?>