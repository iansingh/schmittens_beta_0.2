<?php

 //header('Content-type: text/html; charset=utf-8');
	session_start();
	require "../files/functions.php";
	require "../files/include.php";	
	require "../files/datetimepicker.php";

dbconnect();

// get location info

$location = "SELECT * FROM `location` ORDER BY l_name ASC";
$location_result = mysql_query($location);
$location_array = mysql_fetch_assoc($location_result);
//print_r($location_array);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>Location-Match</title>
 </head>
 <body>

<table class="locationlist" >
	<tr>
		<td>ID</td>	
		<td>Name</td>
		<td>Address</td>
		<td>Events</td>
		<td>Match with...</td>
		<td></td>
		<td>Matched?</td>
	
	</tr>
	
	
<?php 
do { 

$location_id = $location_array['location_id'];

$events = "SELECT 'id' FROM `events` WHERE location_id = $location_id AND starttime > NOW()";
$event_result = mysql_query($events);
$nevents = mysql_num_rows($event_result);


?>
	
	<tr>
		<td><?php echo($location_array['location_id']); ?></td>	
		<td><a href="../location.php?location_id=<?php echo($location_array['location_id']); ?>" target="_blank"><?php echo($location_array['l_name']); ?></a></td>
		<td><?php echo($location_array['street']); ?> <?php echo($location_array['streetnumber']); ?><br />
		<?php echo($location_array['postalcode']); ?>, <?php echo($location_array['city']); ?> <?php echo($location_array['province']); ?></td>
		<td><a href="../location.php?location_id=<?php echo($location_array['location_id']); ?>" target="_blank" ><?php echo($nevents); ?></a></td>
<form>
		<td>

<select name="match">
 


<?php 

$x = $location_array['location_id'];

$location2 = "SELECT location_id, l_name FROM `location` WHERE location_id != '$x'";

$location2_result = mysql_query($location2);
while($location2_array = mysql_fetch_assoc($location2_result)) { 



?>
	<option><?php echo($location2_array['l_name']); ?> - <?php echo($location2_array['location_id']); ?></option>
	<?php } ?>
	
</select>
		
		
	
		
		
		</td>
		<td>
		
		<input type="submit" name="ml" value="Save" />
		
		</td>
		<td></td>
</form>	
	</tr>
	
<?	}
while($location_array = mysql_fetch_assoc($location_result)) 
?>

</table>


</body>
</html>