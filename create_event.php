<?php require "files/header.php";
dbconnect();
checklogin();

?>

<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>Schmittens - The fat cat knows what's going on</title>
 </head>
 
 <body>
<?php include "files/nav.php"?>

<div class="columnleft">


<div class="eventlocation">

<h1>Choose location</h1>
<p>Enter the name of your location and hit enter, then select your location.</p>
<form method="post" enctype="multipart/formdata" action="create_event.php">
Search for Location: <input type="text" name="locationsearch" />
</form>
<?php 
if(isset($_POST['locationsearch']) && ($_POST['locationsearch'] != '')) {
$ls = mysql_real_escape_string($_POST['locationsearch']);
$lquery = "SELECT * FROM `location` WHERE l_name LIKE '%$ls%' ORDER BY `location`.`l_name` ASC ";
$lqueryresult = mysql_query($lquery);
$lquery_array = mysql_fetch_assoc($lqueryresult);?>


<?php
if($lquery_array == TRUE) { ?>
<form action="create_event.php" method="post">
<p>Locations found:</p>
<table class="locationlist" >
<?php do {?>
<tr>
	<td style="width: 160px; "><a href="location.php?location_id=<?php echo($lquery_array['location_id']); ?>" ><img src="/img/location/<?php echo($lquery_array['location_id']); ?>_160.jpg" alt="<?php echo($lquery_array['location_id']); ?>" ></a></td>
	<td style="width: 235px; padding-left: 5px;">	<a href="location.php?location_id=<?php echo($lquery_array['location_id']); ?>" ><b><?php echo($lquery_array['l_name']); ?></b></a><br>
			<?php echo($lquery_array['streetnumber']); ?> <?php echo($lquery_array['street']); ?></br>
	 		<?php echo($lquery_array['city']); ?>, <?php echo($lquery_array['province']); ?>, <?php echo($lquery_array['postalcode']); ?> <br>
	 		<a href="<?php echo($lquery_array['url']); ?>" target="blank">Website</a>
	</td>
	<td style="text-align: right;"><a href="s_e.php?location_id=<?php echo($lquery_array['location_id']); ?>" >Select</a></td>
</tr>

<?php } while($lquery_array = mysql_fetch_assoc($lqueryresult)); ?>

</table>
</form>

<div class="hint">
Is your location not listed? <a href='c_location.php'>Create it here</a>. You can create your event from the new location. For a complete list of existing locations click <a href="locationlist.php">here</a>.</div>

<?php	}
else { echo"<div class='hint'>No such location found. Search again or <a href='c_location.php'>create a new location</a>. You can create your event from the new location. For a complete list of existing locations click <a href='locationlist.php'>here</a>.</div>"; }
 } 
?>




</div>


</div>
<div class="columnright" >


</div>






<div class="footer"><?php require "files/footer.php"; ?></div>
</body>
</html>