<?php
session_start();
	require "files/header.php";

	dbconnect();

// check if a location is set
if(($_GET) == FALSE) 
{
	echo "No location selected";
}

// var_dump($_GET);
extract($_GET);

// get location information
$locationquery = "SELECT * FROM `location` WHERE location_id = $location_id";
// echo($locationquery);
$locationqueryresult = mysql_query($locationquery) or die ("no locationquery");
$locationqueryresult_array = mysql_fetch_assoc($locationqueryresult);
extract($locationqueryresult_array);
//var_dump($locationqueryresult_array);
$location_user = $locationqueryresult_array['created_by'];


// get events for this location
$eventinlocationquery = "SELECT * FROM `events` WHERE location_id = $location_id AND starttime > NOW() ORDER BY starttime ASC ";
// echo($eventinlocationquery);
$eventinlocationqueryresult = mysql_query($eventinlocationquery) or die ("no locationquery");
$eventinlocationqueryresult_array = mysql_fetch_assoc($eventinlocationqueryresult);

// check if location has no events
if($eventinlocationqueryresult_array == TRUE) {
extract($eventinlocationqueryresult_array);
}

// check user
$userquery = "SELECT user, active FROM `users` WHERE id = $location_user ";
// echo($userquery);
$userqueryresult = mysql_query($userquery); //or die ("<br />no userquery");
$userqueryresult_array = mysql_fetch_assoc($userqueryresult);
// var_dump($userqueryresult_array);

//check if user is active
if(($userqueryresult_array['active']) == 0) {
	$username = 'inactive user';
	}
	else {
$username = $userqueryresult_array['user']; 
//var_dump($userqueryresult_array);
}



?>

<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>Schmittens - The fat cat knows what's going on</title>
 </head>
 <body>

<?php require "files/nav.php"; ?>

<div class='columnleft'>

<?php 

displayeventsinlocation_listmode($location_id); 
displayexhibitionsinlocation_listmode($location_id);
displaystageinlocation_listmode($location_id);

?>

</div>

 	<div class="columnright">
 	<?php displaylocation_right($location_id); ?>


		<?php 
if(($locationqueryresult_array['created_by'] == $_SESSION['id']) && isset($_SESSION['id'])) { ?>
<form action="e_location.php" method="post">
	<input type="hidden" name="location_id" value="<?php echo($location_id) ?>" />
	<input type="hidden" name="editlocation" value="1" />				
	<input type="submit" value="Edit location" />		

</form>


<?php }
if($_SESSION['in'] == TRUE) { ?>
<form action="s_e.php?location_id=<?php echo($location_id);?>" method="post">
	<input type="hidden" name="location_id" value="<?php echo($location_id); ?>" />
	<input type="submit" value="Create Event" />		
</form>
<?php } 

?>

		<br>
		<div class="createdby">
		<i>Location created <?php $formatcr = "M j"; $phpdate = strtotime($locationqueryresult_array['creation']); echo(date($formatcr, $phpdate)); echo" by "; echo($userqueryresult_array['user']); ?></i>
		
		</div>
		 <div class="googlemaps"></div>

 	
	<div class="ads"></div>
	

	
	
	</div>

<div class="footer">
<?php include"files/footer.php";  ?>
</div>
 
 </body>
 </html>