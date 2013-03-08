<?php
	session_start();
	 header('Content-type: text/html; charset=utf-8');
	require "files/functions.php";
	require "files/include.php";	
	require "files/datetimepicker.php";
	require "files/stagefunctions.php";	

checklogin();

dbconnect();

//var_dump($_POST);

$etype = 4000;



$upperlimit = $etype + 1000;

// initiate Error-counter:
$err = 0;

//get styles

$stylesquery = "SELECT * FROM `styles` WHERE type_id >= '$etype' AND type_id < '$upperlimit' ORDER BY type_id ASC";
$stylesqueryresult = mysql_query($stylesquery) or die("no stylesquery");
$stylesqueryresult_array = mysql_fetch_assoc($stylesqueryresult);


$location_id = $_GET['location_id'];

	
if(isset($_POST['ce']) == 1) {	
	
	
	// prepare playtime_array
	
	$month = $_POST['month'];
	$day = $_POST['day'];
	$year = $_POST['year'];
	$hour = $_POST['hour'];
	$minute = $_POST['minute'];
	
	
	$i = 0;
	foreach ($month as $key => $value)
	
	{
	if(checkdate($month[$i], $day[$i], $year[$i])) {
		$date = $year[$i]."-".$month[$i]."-".$day[$i]." ".$hour[$i].":".$minute[$i].":00";
		//echo $date;
		$dates[$i] = $date;
		}
	else { $n = $i+1; $err++; $err_date = "Date #".$n." is not a real date."; echo($err); }
	$i++;		
	}
//var_dump($dates);

stagecreator($stage_id,$dates,$err);


		
	}	
//$location_id = $_POST['location_id']; 
// get location-info
$location = getlocationdata($location_id);
$location_user = $location['created_by'];	
	
// get location creator info
$user = getuserdata($location_user);

//check if user is active
if(($user['active']) == 0) {
	$username = 'inactive user';
	}
	else {
	$username = $user['user']; 
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
  <title></title>
  
 </head>
 <body>

<?php include "files/nav.php"?>
<div class="columnleft">

<?php 
//displayeventsinlocation($location_id);
//displayexhibitionsinlocation($location_id);
?>

	


<?php
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
$err = $err + $error['err'];
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
			<ul>
				<li>Stage events have two parts: General information and playtimes.</li>
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
<br />
<h2>Playtimes:</h2>
<table id="playtimetable">

		<?php displaystagevents_datetime($stage_id); ?>



</table>
<input class="delete_playdate" type="button" value="-" />
<input type="button" class="add_playdate" value="+" />
<br /><br />
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