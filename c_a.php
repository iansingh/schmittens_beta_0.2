<?php
	session_start();
	 header('Content-type: text/html; charset=utf-8');
	require "files/functions.php";
	require "files/include.php";	
	require "files/datetimepicker.php";
	require "files/exhibitioncreator.php";	

checklogin();

dbconnect();

/*
echo"<br />";
var_dump($_POST);
echo"<br />";
*/

$etype = 3000;



$upperlimit = $etype + 1000;



//get styles

$stylesquery = "SELECT * FROM `styles` WHERE type_id >= '$etype' AND type_id < '$upperlimit' ORDER BY type_id ASC";
$stylesqueryresult = mysql_query($stylesquery) or die("no stylesquery");
$stylesqueryresult_array = mysql_fetch_assoc($stylesqueryresult);




if($_POST['location_id'] == FALSE) {
	$redir = TRUE; }
if($_GET['location_id'] == TRUE) {
	$location_id = $_GET['location_id'];
	$redir = FALSE; }
if($redir = TRUE) {
	/*
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "locationlist.php";
			header("Location: http://$host$path$site");
			
			// header("Location: $_SERVER['HTTP_REFERER']");
			exit;	
	*/
	}


	
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
	$err_date = "Something is wrong with the date.";
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
	$err_date = "Something is wrong with the date.";
	//echo($err_date);
	}	
	//echo($enddate);

/*	
	// prepare starttime
	$hour_s = mysql_real_escape_string($_POST["hour_s"]);
	$min_s = mysql_real_escape_string($_POST["minute_s"]);
	$starttime = $hour_s.":".$min_s.":00";
	$startdatetime = $startdate." ".$starttime;
	echo"<br />Starttime: ";
	echo($startdatetime);
	echo"<br />";
*/
	
	// check if endttime is set
	// if($_POST['set_endtime'] == 1) {	
/*	
	// prepare endttime
	$hour_e = mysql_real_escape_string($_POST["hour_e"]);
	$min_e = mysql_real_escape_string($_POST["minute_e"]);
	$endtime = $hour_e.":".$min_e.":00";
	echo($endtime);
	
	// prepare enddate
	if($endtime < $starttime) {
		$day_e = $day_s + 1;
		}
	else {
		$day_e = $day_s;
		}
		
	$enddate = $year_e."-".$month_e."-".$day_e;
	echo"<br />";
	echo($enddate);

	$enddatetime = $enddate." ".$endtime;
	
	/*
	else {
	$enddatetime = "";
	$set_endtime = "";
	}	
	*/
	
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
	
	
	
	// check for duplicate exhibition
		// prepare variables
		//$startdate1 = $startdate." 00:00:00";
		//$startdate2 = $startdate." 23:59:59";
	$excheck = "SELECT * FROM `exhibitions` WHERE e_title = '$title' AND location_id = '$location_id' AND startdate = '$startdate' AND enddate = '$enddate'";
	// echo"<br />";
	 //echo($excheck);
	$excheckquery = mysql_query($excheck) or die ("no excheckquery");
	$neventsconflict = mysql_num_rows($excheckquery);
	// echo"<br /> <br /> <br/>NEVENTSCONFLICT: ";
	// echo($neventsconflict);
	// echo"<br /> <br /> <br/>";
	$excheckquery_array = mysql_fetch_assoc($excheckquery);
	
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
	
	if(isset($err_date)) {
		$err++;
	}
	
	unset($_POST['ce']);
}
	
	
	
	if(($neventsconflict == 0) && ($err < 1)){
	// prepare insert
	$writeevent = "INSERT INTO `exhibitions` (`location_id`, `e_title`, `startdate`, `enddate`, `url`, `ticket_url`, `artist`, `price_min`, `price_max`, `price_free`, `donation`, `genre`,
													`description`, `youtube`, `created_by`, `created`, `verified` ) 
										VALUES ('$location_id','$title', '$startdate', '$enddate', '$urlevent','$ticket_url','$artist','$price_min','$price_max','$price_free', '$donation', '$genre',
													'$description', '$youtube', '$eventuser', NOW(), '$verification' )";
	//echo"<br />";	
	//echo($writeevent);
	// echo"<br />";
	// execute insert
	$writeeventquery = mysql_query($writeevent) or die ("no writeevent");
	
	
	// get new exhibition id
	$getexid = "SELECT ex_id FROM `exhibitions` WHERE created_by = $eventuser ORDER BY created DESC LIMIT 1";
	//echo($geteventid);
	//echo"<br />";
	$getexidquery = mysql_query($getexid) or die ("no geteventidquery"); 
	$getexidquery_array = mysql_fetch_assoc($getexidquery);
	$newexid = $getexidquery_array['ex_id'];
	
	
// create individual events with opening hoursSELECT * FROM `location` WHERE 1

/* 
Check if location-openinghours are present.
- if yes - call script
- if no - forward to edit-page (& set $_POST so user can directly enter opening hours)
*/

// check opening hours of location

/*
$getlocationopening = "SELECT location_id FROM location WHERE location_id = '$location_id'
																AND (monday_s != '00:00:00' 
																OR tuesday_s != '00:00:00' 
																OR wednesday_s != '00:00:00' 
																OR thursday_s != '00:00:00' 
																OR friday_s != '00:00:00' 
																OR saturday_s != '00:00:00' 
																OR sunday_s != '00:00:00')";

echo"<br />";
echo($getlocationopening);

$gloquery = mysql_query($getlocationopening) or die ("no getlocationopening-query");
$gloquery_array = mysql_fetch_assoc($gloquery);
*/

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// pass arguments to exhibitioncreator to create individual events!
// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX


	if(exhibitioncreator($newexid,$location_id) == TRUE) {
		//echo"<br />YES!";
			//forward to exhibitionpage  
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "exhibition.php?ex_id=";
			//echo($host.$path.$site.$newexid);
			header("Location: http://$host$path$site$newexid");
			break;
			
			}

else {
	//echo"<br />NO!";

			// Set $_POST['err_noopen']
			$_POST['err_noopen'] = TRUE;	
	
			//forward to editpage for exhibition  
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "e_exhibition.php?ex_id=";
			header("Location: http://$host$path$site$newexid");
			break;	
}


	


	// redirect to event-page
	/*
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "exhibition.php?ex_id=";
			header("Location: http://$host$path$site$neweventid");
			
			// header("Location: $_SERVER['HTTP_REFERER']");
			exit;	
	*/

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
<p>There are duplicate events (same title and same dates):</p>

	<table summary='' >

	
<?php 	
do 
{ 
// prepare username-query
$creator2 = $excheckquery_array['created_by'];
$userquery = "Select user FROM `users` WHERE id = $creator2";
$userqueryresult = mysql_query($userquery); //or die ("<br />no userquery");
$userqueryresult_array = mysql_fetch_assoc($userqueryresult);


$format = "D, M j";
$starttime_conflict = $excheckquery_array['startdate'];
$endtime_conflict = $excheckquery_array['enddate'];
?>
	<tr>
		<td><?php $phpdate1 = strtotime($starttime_conflict); echo(date($format, $phpdate1)); ?> to <?php $phpdate2 = strtotime($endtime_conflict); echo(date($format, $phpdate2)); ?></td>
		<td><a href="exhibition.php?ex_id=<?php echo $excheckquery_array['ex_id']; ?>" ><?php echo $excheckquery_array['e_title']; ?></a></td>
		<td><?php echo $excheckquery_array['artist']; ?></td>
		<td><?php echo $excheckquery_array['description'];  ?></td>
		<td><?php echo $excheckquery_array['type']; ?></td>
		<td><?php 
			if(($_SESSION['id']) == $creator2) { ?>
			<a href="e_exhibition.php?ex_id=<?php echo $excheckquery_array['ex_id'] ?>" >Edit</a>			
			<?php			
			}
			?>	
	</td>
	</tr>
<?php 
} while ($excheckquery_array = mysql_fetch_assoc($excheckquery))	;

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
<?php echo($err_date); if(isset($err_date)) { echo"<br />"; } ?>
<?php echo($err_title); if(isset($err_title)) { echo"<br />"; } ?>
<?php echo($err_st); if(isset($err_st)) { echo"<br />"; } ?>
<?php echo($err_stp); ?>
</div>
<?php } ?>



<?php 
if(!isset($_POST['ce']))
{ 


?>
		<div class="hint">
			<h1><a href="#">Hints >> </a></h1>	
			<ul class="toggle">
				<li>Opening hours can and should be edited or entered in the location if possible!</li>	
				<li>After saving you will be forwarded to the edit-screen, where you can enter custom opening hours.</li>	
				<li>You can upload pictures after saving the event. Just click the edit button.</li>	
			</ul>
		</div>

<h1>Create new event: <?php echo($etname); ?></h1>

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
		<td <?php if(isset($err_st) || isset($err_stp)) { echo"class='required'"; } ?>><b>Startdate</b></td><td <?php if(isset($err_st) || isset($err_stp)) { echo"class='required'"; } ?>><?php drawDateTimePicker_art_start(); ?></td>	
	</tr>
	<tr>
		<td <?php if(isset($err_st) || isset($err_stp)) { echo"class='required'"; } ?>><b>Enddate</b></td><td <?php if(isset($err_st) || isset($err_stp)) { echo"class='required'"; } ?>><?php drawDateTimePicker_art_end(); ?></td>	
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


</div>


</div>

<div class="columnright">

 	<div class="eventlocation">
<?php displaylocation_right($location_id); ?>
	</div>


	<div class="ads"></div>

 	
</div>
<div class="footer">
<?php include"files/footer.php";  ?>
</div>
 </body>
</html>