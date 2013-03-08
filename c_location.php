<?php 
session_start();

	require "files/functions.php";	
	require "files/include.php";
	require "files/datetimepicker.php";	

dbconnect();

checklogin();  

if(($_POST['cl']) == 1) {
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
	
//echo($err);

	
	
	//var_dump($_POST);
	extract($_POST);
	$c_l_name = $_POST['l_name'];
	$c_province = $_POST['province'];
	$c_city = $_POST['city'];
	$c_url = $_POST['url'];
	// prepare user-id
	$id = $_SESSION['id'];
				
		// check if a location with a similar name and address already exists
		// prepare variables to avoid notices
		//if($) {
			// run query
			
			$locationcheck = "SELECT * FROM `location` WHERE (l_name = '$l_name' AND city = '$city' ) ";
									// OR (url = '$url')  ;";
			//echo($locationcheck);
			$locationcheckquery = mysql_query($locationcheck) or die ("no locationcheck");
			$nlocationconflicts = mysql_num_rows($locationcheckquery);
			$locationcheckquery_array = mysql_fetch_assoc($locationcheckquery);

			//echo"nlocationconflicts";
			//echo($nlocationconflicts);

			// get locations
			$locationlist = "SELECT location_id FROM `location` WHERE (l_name = '$l_name' AND city = '$city' ) ";
			//echo($locationlist);
			$locationlistresult = mysql_query($locationlist) or die ("no locationlist");
			$locationlistresult_array = mysql_fetch_assoc($locationlistresult);
			//var_dump($locationlistresult_array);


		// display list with similar locations	
			//$locationcheckquery_array = mysql_fetch_assoc($locationcheckquery);
				//var_dump($locationcheckquery_array);
								

			}

	if(($nlocationconflicts == 0) && (($_POST['cl']) == 1) && ($err < 1)) { 
	//echo($l_name);
	// db insert
	$locationcreate = "INSERT INTO `location` 
							(`l_name`, `street`, `streetnumber`, `additional`, `postalcode`, `city`, `province`, `url`, `mail`, `type`, `facebook`, `twitter`, `created_by`, `creation`, `verification`)
							VALUES
							('$l_name', '$street', '$streetnumber', '$additional', '$postalcode', '$city', '$province', '$url', '$mail', '$type', '$facebook', '$twitter', '$id', NOW(), 0)";
	//echo($locationcreate);
	$locationcreateresult = mysql_query($locationcreate) or die ("no locationinsert");
	
	// get new location-id 
	$id = $_SESSION['id'];
	//echo($id);
	$locationidquery = "SELECT location_id FROM `location` WHERE created_by = $id ORDER BY creation DESC LIMIT 1"; 
	//echo"<br />";
	//echo($locationidquery);
	//echo"<br />";
	$locationidqueryresult = mysql_query($locationidquery) or die ("no locationidquery");
	$locationidqueryresult_array = mysql_fetch_assoc($locationidqueryresult);
	$location_id = $locationidqueryresult_array["location_id"];
	
	
	// forward user to location-page
	$host = $_SERVER["HTTP_HOST"];
	$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
	$site = "location.php?location_id=";
	header("Location: http://$host$path$site$location_id");
	exit;	
	
	}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Locations</title>
  </head>
  <body>
<?php include "files/nav.php"?>  
<div class="columnleft">


<?php

if($err > 0) { ?>
	
<div class="alerttext">
<h1>Attention!</h1>
<p><?php echo($err); ?> problems found:</p>
<?php echo($err_l_name); if(isset($err_l_name) == TRUE) { echo"<br />"; } ?>
<?php echo($err_street); if(isset($err_street)) { echo"<br />"; } ?>
<?php echo($err_postal); if(isset($err_postal)) { echo"<br />"; } ?>
<?php echo($err_city); if(isset($err_city)) { echo"<br />"; } ?>
<?php echo($err_province); if(isset($err_province)) { echo"<br />"; } ?>
<?php echo($err_type); ?>
</div>

<?php }

				if(($nlocationconflicts) > 0 ) { 

			?>
<div class="alerttext"		>	
				<h1>Attention!</h1>
				<p>Similar locations already exist:</p>


				<table> 
				<?php 
				do{ 
				
			$address = $locationcheckquery_array['streetnumber']." ".$locationcheckquery_array['street'].", ".$locationcheckquery_array['postalcode'].", ".$locationcheckquery_array['province'];
			
				/*
			$lid = $locationlistresult_array['location_id'];
			$locationlistoutput = "SELECT * FROM `location` WHERE location_id = $lid ";
			echo($locationlistoutput);
			$locationlistoutputquery = mysql_query($locationlistoutput) or die ("no locationlistoutputquery");
			$locationlistoutputquery_array = mysql_fetch_assoc($locationlistoutputquery);
				*/		
				?>
					<tr>
						<td><a href="location.php?location_id=<?php echo($locationcheckquery_array['location_id']) ?>" ><img src="/img/location/<?php echo($locationcheckquery_array['location_id']) ?>_160.jpg" alt="<?php echo($locationcheckquery_array['location_id']) ?>" ></a></td>
						<td><a href="location.php?location_id=<?php echo($locationcheckquery_array['location_id']) ?>" ><?php echo($locationcheckquery_array['l_name']) ?></a></td>					
						<td><?php echo($address) ?></td>
						<td><a href="<?php echo($locationcheckquery_array['url']) ?>" ></a><?php echo($locationcheckquery_array['url']) ?></td>
					</tr>
		<?php	}
				//while($locationcheckquery_array = mysql_fetch_assoc($locationcheckquery)) 
				while($locationcheckquery_array = mysql_fetch_assoc($locationcheckquery))?>

				</table>		</div>	<br />
		<?php } 

		
if($_SESSION["in"] == TRUE) {		
		
		?>
<h1>Create location</h1>
<div class="form">
 
    

<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
	<input type="hidden" name="cl" value="1" />
	<table style="border-collapse: collapse">
		<tr>
			<td <?php if(isset($err_l_name)) { echo"class='required'"; } ?>><b>Location name</b></td>
			<td <?php if(isset($err_l_name)) { echo"class='required'"; } ?> >
				<input name="l_name" type="text" value="<?php echo($_POST['l_name']) ?>" />		
			</td>			
		</tr>		
		<tr>
			<td <?php if(isset($err_street)) { echo"class='required'"; } ?>><b>Street Name</b></td>
			<td <?php if(isset($err_street)) { echo"class='required'"; } ?>>
				<input name="street" type="text" value="<?php echo($_POST['street']) ?>" />		
			</td>			
		</tr>	
		<tr>
			<td>Street number</td>
			<td>
				<input name="streetnumber" type="text" value="<?php echo($_POST['streetnumber']) ?>" />		
			</td>			
		</tr>	
		<tr>
			<td>Additional Information</td>
			<td>
				<input name="additional" type="text" value="<?php echo($_POST['additional']) ?>" />		
			</td>			
		</tr>		
		<tr>
			<td <?php if(isset($err_postal)) { echo"class='required'"; } ?>><b>Postal code</b></td>
			<td <?php if(isset($err_postal)) { echo"class='required'"; } ?>>
				<input name="postalcode" type="text" value="<?php echo($_POST['postalcode']) ?>" />		
			</td>			
		</tr>		
		<tr>
			<td <?php if(isset($err_city)) { echo"class='required'"; } ?>><b>City</b></td>
			<td <?php if(isset($err_city)) { echo"class='required'"; } ?>>
				<input name="city" type="text" value="<?php echo($_POST['city']) ?>" />		
			</td>			
		</tr>	
		<tr>
			<td <?php if(isset($err_street)) { echo"class='required'"; } ?>><b>Province</b></td>
			<td <?php if(isset($err_street)) { echo"class='required'"; } ?>>
			<select name="province" size="4">
			<option value="AB" label="Alberta" <?php if(($_POST['province']) == AB) { echo "selected='selected'"; } ?> >Alberta</option>
			<option value="BC" label="British Columbia" <?php if(($_POST['province']) == BC) { echo "selected='selected'"; } ?> >British Columbia</option>
			<option value="MB" label="Manitoba" <?php if(($_POST['province']) == MB) { echo "selected='selected'"; } ?> >Manitoba</option>
			<option value="NB" label="New Brunswick" <?php if(($_POST['province']) == NB) { echo "selected='selected'"; } ?> >New Brunswick</option>
			<option value="NL" label="Newfoundland" <?php if(($_POST['province']) == NL) { echo "selected='selected'"; } ?> >Newfoundland</option>
			<option value="NS" label="Nova Scotia" <?php if(($_POST['province']) == NS) { echo "selected='selected'"; } ?> >Nova Scotia</option>
			<option value="ON" label="Ontario" <?php if(($_POST['province']) == ON) { echo "selected='selected'"; } ?> >Ontario</option>
			<option value="PE" label="Prince Edward Island" <?php if(($_POST['province']) == PE) { echo "selected='selected'"; } ?> >Prince Edward Island</option>
			<option value="QC" label="Quebec" <?php if(($_POST['province']) == QC) { echo "selected='selected'"; } ?> >Quebec</option>
			<option value="SK" label="Saskatchewan" <?php if(($_POST['province']) == SK) { echo "selected='selected'"; } ?> >Yukon</option>
			</select>			
			</td>			
		</tr>
		<tr>
			<td>Website</td>
			<td>
				<input name="url" type="text" value="<?php echo($_POST['url']) ?>" />		
			</td>			
		</tr>	
		<tr>
			<td>Mail</td>
			<td>
				<input name="mail" type="text" value="<?php echo($_POST['mail']) ?>" />		
			</td>			
		</tr>	
		<tr>
			<td <?php if(isset($err_street)) { echo"class='required'"; } ?>><b>Type</b></td>
			<td <?php if(isset($err_street)) { echo"class='required'"; } ?>>
					<input type="radio" name="type" value="Club" <?php if(($_POST['type']) == Club) { echo "checked='checked'"; } ?> />Club
					<input type="radio" name="type" value="Concert" <?php if(($_POST['type']) == Concert) { echo "checked='checked'"; } ?> />Concert
					<input type="radio" name="type" value="Art" <?php if(($_POST['type']) == Art) { echo "checked='checked'"; } ?> />Art<br/>
					<input type="radio" name="type" value="Theatre" <?php if(($_POST['type']) == Theatre) { echo "checked='checked'"; } ?> />Theatre
					<input type="radio" name="type" value="Museum" <?php if(($_POST['type']) == Museum) { echo "checked='checked'"; } ?> />Museum
					<input type="radio" name="type" value="Other" <?php if(($_POST['type']) == Other) { echo "checked='checked'"; } ?> />Other
				
			</td>			
		</tr>	
		<tr>
			<td>Facebook</td>
			<td>
				<input name="facebook" type="text" value="<?php echo($_POST['facebook']) ?>" />		
			</td>			
		</tr>	
		<tr>
			<td>Twitter</td>
			<td>
				<input name="twitter" type="text" value="<?php echo($_POST['twitter']) ?>" />		
			</td>			
		</tr>	


		<tr>
			<td></td>
			<td>
				<input type="submit" value="Submit" />		
			</td>			
		</tr>			
		<tr>
			<td></td>			
			<td>
				<input type="reset" value="Reset" />			
			</td>		
		</tr>	
	</table>  	


</form>
</div></div>

<div class="columnright">
	<div class="ads">Ads go here</div>
</div>

<?php } ?>


<div class="footer">
<?php include"files/footer.php";  ?>
</div>
  </body>
</html>