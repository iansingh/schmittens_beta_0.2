<?php
session_start();

	require "files/functions.php";
	require "files/include.php";	
	require "files/datetimepicker.php";
	require "files/nav.php";

dbconnect();


//var_dump($_POST);
$searchterm = mysql_real_escape_string($_POST['searchfield']);



// eventsearch
$event = "SELECT * FROM `events` WHERE title LIKE '%$searchterm%' OR artist LIKE '%$searchterm%' OR type LIKE '%$searchterm%' OR description LIKE '%$searchterm%'";
$eventquery = mysql_query($event);
$event_array = mysql_fetch_array($eventquery);
$nevents1 = mysql_num_rows($eventquery);


// locationsearch
$location = "SELECT * FROM `location` WHERE l_name LIKE '%$searchterm%' OR street LIKE '%$searchterm%' OR city LIKE '%$searchterm%'";
$locationquery = mysql_query($location);
$location_array = mysql_fetch_array($locationquery);
$nlocations1 = mysql_num_rows($locationquery);
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>Schmittens - Search results</title>
 </head>
 <body>

<div class="columnleft">

<div class="eventlist">
Events: 
<table class="evlist" >

<?php 
// set time format
$format = "g.i a, M j";

$c = 1;

if($nevents1 < 1) {
	echo"No matching events found";
	}
else {

do 
{ 
// prepare locationname-query
$eventlocation_id = $event_array['location_id'];
$locationquery = "Select l_name, location_id FROM `location` WHERE location_id = $eventlocation_id";
$locationqueryresult = mysql_query($locationquery) ;

$locationqueryresult_array = mysql_fetch_assoc($locationqueryresult);

// prepare username-query
$creator = $event_array['created_by'];
$userquery = "Select user FROM `users` WHERE id = $creator";
$userqueryresult = mysql_query($userquery) ;
$userqueryresult_array = mysql_fetch_assoc($userqueryresult);

?>
	<tr <?php if($c % 2 == 0) {echo"class='listitem1' "; }  else {echo"class='listitem2'  ";}?>  >
		<td style="width:160px; border-collapse: collapse;"><a href="event.php?event_id=<?php echo $event_array['event_id']; ?>" ><img src="/img/event/<?php echo($event_array['event_id']) ?>_160.jpg" alt="" ></td>
		<div class="eventlistentryinfo">
		<td style="padding-left: 5px; padding-right: 5px; vertical-align: top; border-collapse: collapse;"><a href="event.php?event_id=<?php echo $event_array['event_id']; ?>" ><?php echo $event_array['title']; ?></a> | <?php echo $event_array['type']; ?> 
		<br /><?php $phpdate = strtotime($event_array['starttime']); echo(date($format, $phpdate));  ?> @ <a href="location.php?location_id=<?php echo $locationqueryresult_array['location_id'] ?>" ><?php echo $locationqueryresult_array['l_name'];  ?></a>		
		<br /><?php $shortdescription = truncate($event_array['artist'], 90); echo($shortdescription);	?>
		<br /><?php $shortdescription = truncate($event_array['description'], 120); echo($shortdescription);	?> </td>
		</div>
		<td style="width:30px;"><div class="eventlistedit"><?php 
			if(($_SESSION['id']) == $creator) { ?>
			<a href="event.php?event_id=<?php echo $event_array['event_id'] ?>" >Edit</a> </div>
			<?php			
			}
			?>	
	</td></div>
	</tr>
<?php 
$c = $c + 1;

} while ($event_array = mysql_fetch_assoc($eventquery));
}
?>
</table>


</div>

<div class="separator"></div>
 
<div class="locationlist" >
Locations:
<table class="locationlist" >

<?php 

if($nlocations1 < 1) {
	echo"No matching locations found";
	}
	
else {
	
$c = 1;

do 
{ 
// prepare eventnumber-query	
$eventlocation_id = $location_array['location_id'];
$eventnumberquery = "SELECT event_id FROM `events` WHERE location_id = $eventlocation_id AND starttime > NOW()";


$eventnumberqueryresult = mysql_query($eventnumberquery);
$eventnumberqueryresult_array = mysql_fetch_assoc($eventnumberqueryresult);
$nevents = mysql_num_rows($eventnumberqueryresult);
$user = $locationqueryresult_array['created_by'];

// prepare address
$address1 = $location_array['street']." ".$location_array['streetnumber'];
$address2 = $location_array['postalcode']." ".$location_array['city'].", ".$location_array['province'];


// get usernames 
/*
$usernamequery = "SELECT user FROM `users` WHERE id = $created_by";
$usernamequeryresult = mysql_query($usernamequery) or die ("no usernamequery");
$usernamequeryresult_array = mysql_fetch_assoc($usernamequeryresult);
extract($usernamequeryresult_array);
*/

?>

<tr <?php if($c % 2 == 0) {echo"class='listitem1' "; }  else {echo"class='listitem2' ";}?> >
	<td class="locationlistpic">
	<a href="location.php?location_id=<?php echo $location_array['location_id']; ?>" ><img src="/img/location/<?php echo $location_array['location_id']; ?>_160.jpg" alt="ID <?php echo $location_array['location_id']; ?>" ></a>
	</td>
	<td class="locationlistinfo">
		<div class="locationlisttitle"><a href="location.php?location_id=<?php echo $location_array['location_id']; ?>" ><?php echo $location_array['l_name']; ?></a></div>
		<?php echo $address1; ?><br />
		<?php echo $address2; ?><br />		
		<div class="locationlisturl"><a href="<?php echo $location_array['url'] ?>" ><?php echo $location_array['url'];  ?></a></div>
	</td>	
		<td class="locationlistextra">
		<a href="location.php?location_id=<?php echo $location_array['location_id']; ?>" ><?php echo $nevents; ?> events</a>
		<?php 
			if(($_SESSION['id'] == $user) && isset($_SESSION['id'])){ ?>
			<br /><a href="location.php?location_id=<?php echo $location_array['location_id'] ?>" >Edit</a>	
		</td> 
</tr>
			<?php			
			}
			?>	


<?php 
$c = $c + 1;

} while ($location_array = mysql_fetch_assoc($locationquery)); }
?>
</table>	


</div>

</div>


<div class="columnright">

You searched for: <?php echo($searchterm); ?>
<br />
<?php echo($nevents1); ?> Events found
<br />
<?php echo($nlocations1); ?> Locations found
</div>


<div class="footer">
<?php include"files/footer.php";  ?>
</div> 
 </body>
</html>