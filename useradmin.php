<?php



	// done in header.php	
	// require "files/include.php";
	require "files/header.php";

dbconnect();

// Check if User is logged in & if not send to login page
if($_SESSION["in"] == FALSE) {
			// redirect user to home page
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "login.php";
			header("Location: http://$host$path$site");
			exit;
}  

//var_dump($_POST);


// generate secret

if($_POST['gs'] == "Generate") {
	
	$newsecret = rand(1000000000, 9999999999);
	//echo($newsecret);
	$id = $_POST['id'];
	$tou = 1;
	
	$query = "UPDATE `users` SET `feedsecret`= $newsecret , `tou` = $tou WHERE id = $id";
	//echo($query);
	$result = mysql_query($query);
	unset($_POST['gs']);
	
	}	

// Get info from database

// $user_id comes from header.php

$query = "SELECT * FROM `users` WHERE `id` = '$user_id'";

$result = mysql_query($query) or die ("no query");

$result_array = mysql_fetch_assoc($result);

//print_r($result_array);
extract($result_array);


// get user's event
//$usereventsquery = "SELECT * FROM `events` WHERE created_by = $user_id AND starttime >= NOW() ORDER BY starttime,location_id";
$usereventsquery = "SELECT event_id, title, artist, starttime, events.type, l_name, events.location_id FROM `events` LEFT JOIN `location` ON `events`.location_id = `location`.location_id WHERE `events`.created_by = $user_id AND starttime >= NOW() ORDER BY starttime,location_id";
//echo($usereventsquery);
//$eventlistquery = "SELECT event_id, title, artist, starttime, events.type, l_name, events.location_id FROM `events` LEFT JOIN `location` ON `events`.location_id = `location`.location_id WHERE `starttime` > NOW() ORDER BY starttime,events.location_id LIMIT 1, 30";
$usereventsqueryresult = mysql_query($usereventsquery) or die ("no usereventsquery");
$usereventsqueryresult_array = mysql_fetch_assoc($usereventsqueryresult);
if(mysql_num_rows($usereventsqueryresult) < 1) {
	$noevents = "You have not created any events.";	
	}
	

// handle imageupload

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
		$userid = $_POST['user_id'];
		
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
		
		//we will give an unique name, using the users id
		$image_name=$userid."_original".'.'.$extension;
		//the new name will be containing the full path where will be stored (images folder)
		$newname="img/user/".$image_name;
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
		$writepic = "UPDATE `users` SET `img_original`= '$image_name' WHERE id = $id";
		$writepicquery = mysql_query($writepic);
		//echo($writepicquery);
		
		// create 3 thumbnail sizes		
		
			$uid = $_POST['user_id'];
			
			$imagepath = "img/user/".$uid."_original.jpg";
			
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
			$filename = "img/user/".$uid.$suffix;
			$imgname = $uid.$suffix;
			
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
			$imgupdate = "UPDATE `users` SET $resize = '$imgname' WHERE id = '$uid'";

			$imgupdatequery = mysql_query($imgupdate); 
			
			$c = $c + 1;		
			}		
			while($c < 5) ;
				
		}
	}

?>	
	


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>User Administration</title>
<link href="style.css" rel="stylesheet" type="text/css" />  
 </head>
 <body>
<?php include "files/nav.php"?>

<div class="columnleft">

<?php if($_SESSION['pwupdate'] == 1) {?>
<div class="notice">Your password has been updated.</div>
<?php 
unset($_SESSION['pwupdate']);
} ?>

<!--
<ul class="tabnav">
<a name="top">
	<li id="account"><a href="#account">My account</li>
	<li id="events"><a href="#events">My events</a></li>
	<li id="locations"><a href="#locations">My locations</a></li>
</a>
</ul>
-->
<div class="minicontainer">
<h1 id="account" ><a href="#top">My Account</a></h1>
<div id="account" >

<div class="form">
<img src="img/user/<?php echo($id) ?>_480.jpg" alt="" >
<form name="newad" method="post" enctype="multipart/form-data" action="">
<table>
<tr><td><input type="file" class="imgupload" name="image"></td></tr>
<tr><td><input type="hidden" name="user_id" value="<?php echo($id); ?>"/><input name="imageupload" disabled="disabled" type="submit" class="disable" value="Upload image"></td></tr>
</table>
</form>
<br />


<table>
	<tr>
		<td>ID:</td><td><?php echo($id); ?></td>	
	</tr>
	<tr>
		<td>Username:</td><td><?php echo($user); ?></td>	
	</tr>
	<tr>
		<td>Mail:</td><td><?php echo($mail); ?></td>	
	</tr>
	<tr>
		<td></td><td><FORM METHOD="LINK" ACTION="/pwchange.php">
<INPUT TYPE="submit" VALUE="Change password">
</FORM></td>	
	</tr>
<!--	<tr>
		<td></td><td><FORM METHOD="LINK" ACTION="/deleteaccount.php">
<INPUT TYPE="submit" VALUE="Delete account">
</FORM></td>	
	</tr> -->
</table>


</div>
</div>
</div>

<?php 

?>

<div class="minicontainer">
<h1 id="events"><a href="#top">My events</a></h1>
<div id="events" class="hidden">

<?php 
displayeventsinlocation_listmode_user($user_id);

displayexhibitionsinlocation_listmode_user($user_id);

displaystageinlocation_listmode_user($user_id);

?>
</div>
</div>

<?php 
// query - if user has locations, display list
$ulq = "SELECT * FROM `location` WHERE created_by = $user_id";
$ulqr = mysql_query($ulq);
$ulqr_a = mysql_fetch_assoc($ulqr); 

if(mysql_num_rows($ulqr) > 0) { ?>
<div class="minicontainer">
<h1 id="locations"><a href="#top">My locations</a></h1>


<div id="locations" class="hidden">


<table class="locationlist" >

<?php 

$c = 1;

do 
{ 
?>


<?php 
// prepare eventnumber-query	
$eventlocation_id = $ulqr_a['location_id'];
$eventnumberquery = "SELECT event_id FROM `events` WHERE location_id = $eventlocation_id AND starttime > NOW()";


$eventnumberqueryresult = mysql_query($eventnumberquery);
$eventnumberqueryresult_array = mysql_fetch_assoc($eventnumberqueryresult);
$nevents = mysql_num_rows($eventnumberqueryresult);
$user = $locationqueryresult_array['created_by'];

// prepare address
$address1 = $ulqr_a['street']." ".$ulqr_a['streetnumber'];
$address2 = $ulqr_a['postalcode']." ".$ulqr_a['city'].", ".$ulqr_a['province'];


?>

<tr <?php if($c % 2 == 0) {echo"class='listitem1' "; }  else {echo"class='listitem2' ";}?> >
	<td class="locationlistpic">
	<a href="location.php?location_id=<?php echo $ulqr_a['location_id']; ?>" ><img src="/img/location/<?php echo $ulqr_a['location_id']; ?>_160.jpg" alt="<?php echo $ulqr_a['location_id']; ?>" ></a>
	</td>
	<td class="locationlistinfo">
		<div class="locationlisttitle"><b><a href="location.php?location_id=<?php echo $ulqr_a['location_id']; ?>" ><?php echo $ulqr_a['l_name']; ?></a></b></div>
		<?php echo $address1; ?><br />
		<?php echo $address2; ?><br />		
		<div class="locationlisturl"><a href="<?php echo $ulqr_a['url'] ?>" ><?php echo $ulqr_a['url'];  ?></a></div>
	</td>	
		<td class="locationlistextra">
		<a href="location.php?location_id=<?php echo $ulqr_a['location_id']; ?>" ><?php echo $nevents; ?></a>
		<?php 
			if(($_SESSION['id'] == $user) && isset($_SESSION['id'])){ ?>
			<br /><a href="location.php?location_id=<?php echo $ulqr_a['location_id'] ?>" >Edit</a>	
		</td> 
</tr>
			<?php			
			}
			?>	


<?php 
$c = $c + 1;

} while ($ulqr_a = mysql_fetch_assoc($ulqr))
 ?>
</table>	


</div>
</div>
<?php } ?>

<div class="minicontainer">
<h1 id="export"><a href="#top">Export-Options</a></h1>
<div id="export" class="hidden">
<form name="fs" method="post" enctype="multipart/form-data" action="">
I accept the <a href="tou.php" target="blank">Terms of Use</a>: <input class="accept" type="checkbox" name="accept" /><br />
<input type="submit" disabled="disabled" class="disable" name="gs" value="Generate" />
<input type="hidden" name="id" value="<?php echo($id);?>"/>
Secret: <input id="fsecret" type="text" name="secret" value="<?php echo($feedsecret); ?>"/>

</form>

<?php 
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$subpath = "files/xml/";
			$url = $path.$subpath;
			$export_events = "xml_export_events_verbose_user.php";
			$export_locations = "xml_export_locations_terse_user.php";
			$uid = "?user_id=".$id;
			$secret = "&feedsecret=".$feedsecret;
?>
<p id="exportlink"><a href="<?php echo($url.$export_events.$uid.$secret);?>" target="blank" >XML Export - Events, verbose</a></p>
<p id="exportlink"><a href="<?php echo($url.$export_locations.$uid.$secret);?>" target="blank" >XML Export - Locations, terse</a></p>

</div></div>

</div><!-- columnleft -->



<div class="columnright">

	<div class="rightlinks">
		<a href="manual.php" target="blank" >Manual</a>
	</div>

	<div class="ads">
		<p></p>	
	</div>
</div>  

<div class="footer">
<?php include"files/footer.php";  ?>
</div>



</body>