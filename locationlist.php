<?php

require "files/header.php";	

dbconnect();

// get standard locations
$locationquery = "SELECT * FROM `location` ORDER BY l_name ASC";

// execute locationquery
$locationqueryresult = mysql_query($locationquery);
$locationqueryresult_array = mysql_fetch_assoc($locationqueryresult);

if(mysql_num_rows($locationqueryresult) < 1) {
	$nolocations = "There are no locations that match your search.";	
	}


$lwe = $_POST['nel'];


?>

<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>Schmittens - The fat cat knows what's going on</title>
 </head>
 <body>
<?php include "files/nav.php"?>
<div class="columnleft"> 
<h1>Locations</h1>
<!--
<form action="locationlist.php" method="post" enctype="multipart/form-data">

<input type="radio" name="nel" value="0" checked="checked"/>All locations 
<input type="radio" name="nel" value="1" />Only locations with events 

<input type="submit" name="list" value="Go!" />
</form>
-->

<div class="locationlist" >
<table class="locationlist" >

<?php 

if(isset($nolocations)) {
	echo($nolocations);
	}


$c = 1;

do 
{ 
?>


<?php 
// prepare eventnumber-query	
$eventlocation_id = $locationqueryresult_array['location_id'];
$eventnumberquery = "SELECT event_id FROM `events` WHERE location_id = $eventlocation_id AND starttime > NOW()";


$eventnumberqueryresult = mysql_query($eventnumberquery);
$eventnumberqueryresult_array = mysql_fetch_assoc($eventnumberqueryresult);
$nevents = mysql_num_rows($eventnumberqueryresult);
$user = $locationqueryresult_array['created_by'];

// prepare address
$address1 = $locationqueryresult_array['street']." ".$locationqueryresult_array['streetnumber'];
$address2 = $locationqueryresult_array['postalcode']." ".$locationqueryresult_array['city'].", ".$locationqueryresult_array['province'];


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
	<a href="location.php?location_id=<?php echo $locationqueryresult_array['location_id']; ?>" ><img src="/img/location/<?php echo $locationqueryresult_array['location_id']; ?>_160.jpg" alt="<?php echo $locationqueryresult_array['location_id']; ?>" ></a>
	</td>
	<td class="locationlistinfo">
		<div class="locationlisttitle"><b><a href="location.php?location_id=<?php echo $locationqueryresult_array['location_id']; ?>" ><?php echo $locationqueryresult_array['l_name']; ?></a></b></div>
		<?php echo $address1; ?><br />
		<?php echo $address2; ?><br />		
		<div class="locationlisturl"><a href="<?php echo $locationqueryresult_array['url'] ?>" target="_blank" >Website</a></div>
	</td>	
		<td class="locationlistextra">
		<?php if($nevents > 0) { ?><a href="location.php?location_id=<?php echo $locationqueryresult_array['location_id']; ?>" ><?php echo $nevents; ?> Ev</a> <?php } ?>
		<?php 
			if(($_SESSION['id'] == $user) && isset($_SESSION['id'])){ ?>
			<br /><a href="location.php?location_id=<?php echo $locationqueryresult_array['location_id'] ?>" >Edit</a>	
		</td> 
</tr>
			<?php			
			}
			?>	


<?php 
$c = $c + 1;

} while ($locationqueryresult_array = mysql_fetch_assoc($locationqueryresult))
 ?>
</table>	
</div></div>

<div class="columnright">

<?php 
if($_SESSION['in'] == TRUE) { ?>
<div class="form">
<form action="c_location.php" method="post">
	<input type="submit" value="Create Location" />		
</form>
</div> 
<?php } ?>
	<div class="ads"></div>

</div>






<div class="footer">
<?php include"files/footer.php";  ?>
</div>


 </body>
 </html>