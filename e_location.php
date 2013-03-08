<?php session_start();

	require "files/functions.php";
	require "files/include.php";
	require "files/datetimepicker.php";	

dbconnect();

checklogin();  


 /*
if(isset($_POST['delete']) == TRUE)
	{
	// make sure that user is creator and has status > 2
	
	
	// prepare delete query
	$locationid_todelete = $_POST['location_id'];
	$deletelocation = "DELETE FROM `location` WHERE location_id = $locationid_todelete ";
	//echo($deletelocation);
	$deletelocationquery = mysql_query($deletelocation);
	
	// redirect user to locationlist 
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "locationlist.php";
			$location = $_POST['location_id'];
			header("Location: http://$host$path$site");
			exit;
	}   
*/
//var_dump($_POST);

if(isset($_POST['delete']) == TRUE)
	{
	extract($_POST);
	
	for($i = 0; $i < 7; $i++) {

	if(isset($delete[$i])) {
		$dday = $delete[$i];
		$dday_s = $dday."_s";
		$dday_e = $dday."_e";
		$ddayq = "UPDATE `location` SET $dday_s = '00:00:00', $dday_e = '00:00:00' WHERE location_id = $location_id";
		//echo($ddayq);
		$ddayq_result = mysql_query($ddayq) or die('no dday');
		}
	
	}
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "location.php?location_id=";
			$location = $location_id;
			header("Location: http://$host$path$site$location");
			exit;
	}
	
if(isset($_POST['el']) == 1) {

	$err = 0;
	
	if($_POST['l_name'] == FALSE) 
	{ 
	$err_l_name = "No location name set"; 
	$err = $err + 1;
	}
	if($_POST['street'] == FALSE) 
	{ 
	$err_street = "No street name set"; 
	$err = $err + 1;
	}		
	if($_POST['postalcode'] == FALSE) 
	{ 
	$err_postal = "No postal code set"; 
	$err = $err + 1;
	}
	if($_POST['city'] == FALSE) 
	{ 
	$err_city = "No city set"; 
	$err = $err + 1;
	}
	if($_POST['province'] == FALSE) 	
	{ 
	$err_province = "No province selected"; 
	$err = $err + 1;
	}
	if($_POST['type'] == FALSE) 	
	{ 
	$err_type = "No type selected"; 
	$err = $err + 1;
	}	
	

}

if(($err < 1) && (isset($_POST['oh']) == 1))

	{
//echo '<pre>starttime: '; print_r($_POST); echo '</pre><br/>';	
extract($_POST);


	
$open_hs = array_intersect_key($hour_s, $day);
$open_ms = array_intersect_key($minute_s, $day);
$open_he = array_intersect_key($hour_e, $day);
$open_me = array_intersect_key($minute_e, $day);

/*
	echo"<br />hour_s: ";
$open_hs = array_combine($day, $hour_s);
	echo '<pre>open_hs: '; print_r($open_hs); echo '</pre>';	
	echo"<br />minute_s: ";
$open_ms = array_combine($minute_s, $day);
	echo '<pre>'; print_r($open_ms); echo '</pre>';	
	echo"<br />hour_e: ";
$open_he = array_combine($hour_e, $day);
	echo '<pre>'; print_r($open_he); echo '</pre>';	
	echo"<br />minute_e: ";
$open_me = array_combine($minute_e, $day);
	echo '<pre>'; print_r($open_me); echo '</pre>';	
*/

for($i = 0; $i < 7; $i++) {

	if(isset($open_hs[$i])) {
		$h_s = $open_hs[$i];
		$m_s = $open_ms[$i];
		$time_s = $h_s.":".$m_s.":00";
		$time_ds[$i] = $time_s;
		}


	if(isset($open_hs[$i])) {
		$h_e = $open_he[$i];
		$m_e = $open_me[$i];
		$time_e = $h_e.":".$m_e.":00";
		$time_de[$i] = $time_e;
		}
		
	unset($err_opening);
	
	if(($h_s == "") || ($h_e == "")) { $err_opening = "Closing hours must be later than opening hours (unless the venue closes between 12am and 5 am)."; }
	
	if(($h_s >= $h_e) && ($h_e > 5)) { $err_opening = "Closing hours must be later than opening hours (unless the venue closes between 12am and 5 am)."; }
	
	if(!isset($err_opening)) {
	
 	$d = $day[$i];
 	$d_s = $d."_s";
 	$d_e = $d."_e";
	
	if(isset($open_hs[$i])) { 
		$opening = "UPDATE `location` SET $d_s = '$time_s', $d_e = '$time_e' WHERE location_id = $location_id";
		$opening_result = mysql_query($opening) or die('no openingresult');
		}	
	}
	}	
	
	if(!isset($err_opening)) {
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "location.php?location_id=";
			$location = $location_id;
			header("Location: http://$host$path$site$location");
			exit;
		}
		
}
	
	
if(($err < 1) && (isset($_POST['el']) == 1))

	{ 
	extract($_POST);
	

	
	$locationupdate = "UPDATE `location` SET 
								l_name = '$l_name', street = '$street', streetnumber = '$streetnumber', additional = '$additional',
								city = '$city', postalcode = '$postalcode', province = '$province',
								url = '$url', mail = '$mail', type = '$type',
								facebook = '$facebook', twitter = '$twitter',
								`update` = NOW() WHERE location_id = $location_id" 	;			
								
	//echo($locationupdate);
	$locationupdateresult = mysql_query($locationupdate) or die ("no locationupdate");
			
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "location.php?location_id=";
			$location = $location_id;
			header("Location: http://$host$path$site$location");
			exit;
			
	}

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
		$imglocationid = $_POST['location_id'];
		
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
		//echo '<h1>You have exceeded the size limit!</h1>';
		$err_image = "Image is too big";
		$errors=1;
		}
		
		//we will give an unique name, for example the time in unix time format
		$image_name=$imglocationid."_original".'.'.$extension;
		//echo($image_name);
		//the new name will be containing the full path where will be stored (images folder)
		$newname="img/location/".$image_name;
		//echo($newname);
		//we verify if the image has been uploaded, and print error instead
		$copied = copy($_FILES['image']['tmp_name'], $newname);
		if (!$copied)
		{
		//echo '<h1>Copy unsuccessfull!</h1>';
		$err_image = "Image copy unsuccessfull.";
		$errors=1;
		}}}/*}*/
		
		//If no errors registred, print the success message
		if(isset($_POST['imageupload']) && !$errors)
		{
		// write imagename to db
		$writepic = "UPDATE `location` SET `img_original`= '$image_name' WHERE location_id = $imglocationid";
		$writepicquery = mysql_query($writepic);
		//echo($writepic);
		
		// create 4 thumbnail sizes		
		
			$lid = $_POST['location_id'];
			
			$imagepath = "img/location/".$lid."_original.jpg";
			
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
			$filename = "img/location/".$lid.$suffix;
			$imgname = $lid.$suffix;
			
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
			$imgupdate = "UPDATE `location` SET $resize = '$imgname' WHERE location_id = '$lid'";
			$imgupdatequery = mysql_query($imgupdate); 
			
			$c = $c + 1;		
			}		
			while($c < 5) ;
				
		}
		
if(isset($_POST['dlimg']) == TRUE) {
	$location_id = $_POST['location_id'];
	$deleteimage = "UPDATE `location` SET `img_original`= '',`img_480`= '',`img_320`= '',`img_240`= '',`img_160`= '' WHERE location_id = $location_id";
	$dlimagequery = mysql_query($deleteimage);
	}

	// rerun location query
	$location_id = $_POST['location_id'];
	$location = "SELECT * FROM `location` WHERE location_id = $location_id";
	$locationquery = mysql_query($location);
	$locationquery_array = mysql_fetch_assoc($locationquery);
 
	}

if(($_POST['editlocation']) == 1 || isset($_POST['location_id'])) {
	$location_id = $_POST['location_id'];
	$location = "SELECT * FROM `location` WHERE location_id = $location_id";
	$locationquery = mysql_query($location) or die("no locationquery");
	$locationquery_array = mysql_fetch_assoc($locationquery);

extract($locationquery_array);

 }  
 


	include "files/nav.php";
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Locations</title>
  </head>
  <body>

<div class="columnleft">
<h1>Edit location</h1>

<?php if($err > 0) { ?>

<div class="alerttext">
<h1>Attention!</h1>
<p>Something is wrong:</p>
<?php echo($err_l_name); if(isset($err_l_name) == TRUE) { echo"<br />"; } ?>
<?php echo($err_image); if(isset($err_image) == TRUE) { echo"<br />"; } ?>
<?php echo($err_street); if(isset($err_street)) { echo"<br />"; } ?>
<?php echo($err_postal); if(isset($err_postal)) { echo"<br />"; } ?>
<?php echo($err_city); if(isset($err_city)) { echo"<br />"; } ?>
<?php echo($err_province); if(isset($err_province)) { echo"<br />"; } ?>
<?php echo($err_type); ?>
</div>

<?php } ?>

<div class="form">  
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
	<input type="hidden" name="el" value="1" />
	<input type="hidden" name="location_id" value="<?php echo($locationquery_array['location_id']); ?>" />
	<table>
		<tr>
			<td><b>Location name</b></td>
			<td <?php if(isset($err_l_name)) { echo"class='required'"; } ?> >
				<input name="l_name" type="text" value="<?php echo($locationquery_array['l_name']) ?>" />		
			</td>			
		</tr>		
		<tr>
			<td><b>Street Name</b></td>
			<td <?php if(isset($err_street)) { echo"class='required'"; } ?> >
				<input name="street" type="text" value="<?php echo($locationquery_array['street']) ?>" />		
			</td>			
		</tr>	
		<tr>
			<td>Street number</td>
			<td>
				<input name="streetnumber" type="text" value="<?php echo($locationquery_array['streetnumber']) ?>" />		
			</td>			
		</tr>	
		<tr>
			<td>Additional Information</td>
			<td>
				<input name="additional" type="text" value="<?php echo($locationquery_array['additional']) ?>" />		
			</td>			
		</tr>	
		<tr>
			<td><b>Postal code</b></td>
			<td <?php if(isset($err_postal)) { echo"class='required'"; } ?> >
				<input name="postalcode" type="text" value="<?php echo($locationquery_array['postalcode']) ?>" />		
			</td>			
		</tr>	
		<tr>
			<td><b>City</b></td>
			<td <?php if(isset($err_city)) { echo"class='required'"; } ?> >
				<input name="city" type="text" value="<?php echo($locationquery_array['city']) ?>" />		
			</td>			
		</tr>		
		<tr>
			<td><b>Province</b></td>
			<td <?php if(isset($err_street)) { echo"class='required'"; } ?> >
			<select name="province" size="4">
			<option value="AB" label="Alberta" <?php if(($_locationquery_array['province']) == AB) { echo "selected='selected'"; } ?> >Alberta</option>
			<option value="BC" label="British Columbia" <?php if(($locationquery_array['province']) == BC) { echo "selected='selected'"; } ?> >British Columbia</option>
			<option value="MB" label="Manitoba" <?php if(($locationquery_array['province']) == MB) { echo "selected='selected'"; } ?> >Manitoba</option>
			<option value="NB" label="New Brunswick" <?php if(($locationquery_array['province']) == NB) { echo "selected='selected'"; } ?> >New Brunswick</option>
			<option value="NL" label="Newfoundland" <?php if(($locationquery_array['province']) == NL) { echo "selected='selected'"; } ?> >Newfoundland</option>
			<option value="NS" label="Nova Scotia" <?php if(($locationquery_array['province']) == NS) { echo "selected='selected'"; } ?> >Nova Scotia</option>
			<option value="ON" label="Ontario" <?php if(($locationquery_array['province']) == ON) { echo "selected='selected'"; } ?> >Ontario</option>
			<option value="PE" label="Prince Edward Island" <?php if(($locationquery_array['province']) == PE) { echo "selected='selected'"; } ?> >Prince Edward Island</option>
			<option value="QC" label="Quebec" <?php if(($locationquery_array['province']) == QC) { echo "selected='selected'"; } ?> >Quebec</option>
			<option value="SK" label="Saskatchewan" <?php if(($locationquery_array['province']) == SK) { echo "selected='selected'"; } ?> >Yukon</option>
			</select>			
			</td>			
		</tr>
		<tr>
			<td>Website</td>
			<td>
				<input name="url" type="text" value="<?php echo($locationquery_array['url']) ?>" />		
			</td>			
		</tr>	
		<tr>
			<td>Mail</td>
			<td>
				<input name="mail" type="text" value="<?php echo($locationquery_array['mail']) ?>" />		
			</td>			
		</tr>	
		<tr>
			<td><b>Type</b></td>
			<td <?php if(isset($err_street)) { echo"class='required'"; } ?> >
					<input type="radio" name="type" value="Club" <?php if(($locationquery_array['type']) == Club) { echo "checked='checked'"; } ?> />Club
					<input type="radio" name="type" value="Concert" <?php if(($locationquery_array['type']) == Concert) { echo "checked='checked'"; } ?> />Concert
					<input type="radio" name="type" value="Art" <?php if(($locationquery_array['type']) == Art) { echo "checked='checked'"; } ?> />Art
					<br />
					<input type="radio" name="type" value="Theatre" <?php if(($locationquery_array['type']) == Theatre) { echo "checked='checked'"; } ?> />Theatre
					<input type="radio" name="type" value="Museum" <?php if(($locationquery_array['type']) == Museum) { echo "checked='checked'"; } ?> />Museum
					<input type="radio" name="type" value="Other" <?php if(($locationquery_array['type']) == Other) { echo "checked='checked'"; } ?> />Other 
				
			</td>			
		</tr>	
		<tr>
			<td>Facebook</td>
			<td>
				<input name="facebook" type="text" value="<?php echo($locationquery_array['facebook']) ?>" />		
			</td>			
		</tr>	
		<tr>
			<td>Twitter</td>
			<td>
				<input name="twitter" type="text" value="<?php echo($locationquery_array['twitter']) ?>" />		
			</td>			
		</tr>	

		<tr>
			<td></td>
			<td>

			</td>			
		</tr>			
		<tr>
			<td></td>			
			<td>

			</td>		
		</tr>	
	</table>  
				<input name="save" type="submit" value="Save" />		
				<input type="reset" value="Reset" />			

</form>  
	
	<div class="hint">	
	<h1><a href="#">Opening hours >> </a></h1>
	<p class="toggle">This is only relevant if the location hosts exhibitions. To save opening hours check the relevant checkboxes. If a checked day already has hours they will be overwritten.</p>
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
	<input type="hidden" name="location_id" value="<?php echo($locationquery_array['location_id']); ?>" />
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
			<td><?php drawDateTimePicker_art_openinghours($monday_s,$monday_e)?></td>
			<td><?php if($monday_s != "00:00:00") { echo(date('G:i',strtotime($monday_s))); echo" - "; echo(date('G:i',strtotime($monday_e)));} ?></td>
			<td><input type="checkbox" name="delete[0]" value="monday"/></td>
		</tr>
		<tr <?php if($tuesday_s != "00:00:00") { echo"style='background-color: #c1ffab;'";}?>>
			<td>Tuesday: </td>
			<td><input type="checkbox" name="day[1]" value="tuesday" <?php if($_POST['tuesday'] == TRUE) {echo'checked = "checked"';} ?>/> </td>
			<td><?php drawDateTimePicker_art_openinghours($tuesday_s,$tuesday_e)?></td>
			<td><?php if($tuesday_s != "00:00:00") { echo(date('G:i',strtotime($tuesday_s))); echo" - "; echo(date('G:i',strtotime($tuesday_e)));} ?></td>
			<td><input type="checkbox" name="delete[1]" value="tuesday"/></td>
		</tr>
		<tr <?php if($wednesday_s != "00:00:00") { echo"style='background-color: #c1ffab;'";}?>>
			<td>Wednesday: </td>
			<td><input type="checkbox" name="day[2]" value="wednesday" <?php if($_POST['wednesday'] == TRUE) {echo'checked = "checked"';} ?>/> </td>
			<td><?php drawDateTimePicker_art_openinghours($wednesday_s,$wednesday_e)?></td>
			<td><?php if($wednesday_s != "00:00:00") { echo(date('G:i',strtotime($wednesday_s))); echo" - "; echo(date('G:i',strtotime($wednesday_e)));} ?></td>
			<td><input type="checkbox" name="delete[2]" value="wednesday"/></td>
		</tr>
		<tr <?php if($thursday_s != "00:00:00") { echo"style='background-color: #c1ffab;'";}?>>
			<td>Thursday: </td>
			<td><input type="checkbox" name="day[3]" value="thursday" <?php if($_POST['thursday'] == TRUE) {echo'checked = "checked"';} ?>/></td>
			<td><?php drawDateTimePicker_art_openinghours($thursday_s,$thursday_e)?></td>
			<td><?php if($thursday_s != "00:00:00") { echo(date('G:i',strtotime($thursday_s))); echo" - "; echo(date('G:i',strtotime($thursday_e)));} ?></td>
			<td><input type="checkbox" name="delete[3]" value="thursday"/></td>
		</tr>
		<tr <?php if($friday_s != "00:00:00") { echo"style='background-color: #c1ffab;'";}?>>
			<td>Friday: </td>
			<td><input type="checkbox" name="day[4]" value="friday" <?php if($_POST['friday'] == TRUE) {echo'checked = "checked"';} ?>/> </td>
			<td><?php drawDateTimePicker_art_openinghours($friday_s,$friday_e)?></td>
			<td><?php if($friday_s != "00:00:00") { echo(date('G:i',strtotime($friday_s))); echo" - "; echo(date('G:i',strtotime($friday_e)));} ?></td>
			<td><input type="checkbox" name="delete[4]" value="friday"/></td>
		</tr>
		<tr <?php if($saturday_s != "00:00:00") { echo"style='background-color: #c1ffab;'";}?>>
			<td>Saturday: </td>
			<td><input type="checkbox" name="day[5]" value="saturday" <?php if($_POST['saturday'] == TRUE) {echo'checked = "checked"';} ?>/></td>
			<td><?php drawDateTimePicker_art_openinghours($saturday_s,$saturday_e)?></td>
			<td><?php if($saturday_s != "00:00:00") { echo(date('G:i',strtotime($saturday_s))); echo" - "; echo(date('G:i',strtotime($saturday_e)));} ?></td>
			<td><input type="checkbox" name="delete[5]" value="saturday"/></td>
		</tr>
		<tr <?php if($sunday_s != "00:00:00") { echo"style='background-color: #c1ffab;'";}?>>
			<td>Sunday: </td>
			<td><input type="checkbox" name="day[6]" value="sunday" <?php if($_POST['sunday'] == TRUE) {echo'checked = "checked"';} ?>/> </td>
			<td><?php drawDateTimePicker_art_openinghours($sunday_s,$sunday_e)?></td>
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

<div class="imageupload">

<table>

<form name="newad" method="post" enctype="multipart/form-data" action="" >
<tr><input type="file" name="image" class="imgupload" size="14"></tr>
<tr><input type="hidden" name="location_id" value="<?php echo($_POST['location_id']); ?>" /></tr><br />
<tr><input name="imageupload" disabled="disabled" class="disable" type="submit" value="Upload image"></tr>
</form><br />
<!-- <tr><form name="deleteimage" method="post" enctype="multipart/form-data"><input type="submit" name="dlimg" value="Delete Image" />
<input type="hidden" name="location_id" value="<?php echo($_POST['location_id']); ?>" /></form></tr> -->
</table>
</div>

<?php displaylocation_right($location_id); ?>

<!--
<?php if($_SESSION['status'] > 2) {?>
<div class="deletelocation">

<form action="e_event.php" method="post">
	<input type="hidden" name="location_id" value="<?php echo($location_id); ?>"/>
	<input type="hidden" name="delete" value"delete">
</form> 		

Warning! This cannot be undone!
<?php } ?>  
</div>
-->


</div>

<div class="footer">
<?php include"files/footer.php";  ?>
</div>
  </body>
</html>