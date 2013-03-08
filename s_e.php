<?php
	session_start();
	 header('Content-type: text/html; charset=utf-8');
	require "files/functions.php";
	require "files/include.php";	
	require "files/datetimepicker.php";

checklogin();

dbconnect();


//var_dump($_POST);
$etype = $_POST["etype"];


//var_dump($_GET);
$location_id = $_GET["location_id"];


	
if(isset($_POST['ce']) == 1) {	
	
	

	if($etype == 1000) {
	$creationpage = "c_p.php";
	}
	if($etype == 2000) {
	$creationpage = "c_c.php";
	}
	if($etype == 3000) {
	$creationpage = "c_a.php";
	}
	if($etype == 4000) {
	$creationpage = "c_s.php";
	}
	if($etype == 5000) {
	$creationpage = "c_o.php";
	}

	

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
	
	if($_POST['etype'] == FALSE) 
	{ 
	$err_es = "No event genre set"; 
	$err = $err + 1;
	unset($_POST['ce']);
	}
		

}
	
	
	
	if($err < 1){

	// redirect to event-creation page for selected event type
	
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$getinfo = "?location_id=";
			$headerinfo = $host.$path.$creationpage.$getinfo.$location_id;
			//echo($headerinfo);
			header("Location: http://$headerinfo");
			
			// header("Location: $_SERVER['HTTP_REFERER']");
			exit;	
	

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

// get location-events
$eventinlocationquery = "SELECT * FROM `events` WHERE location_id = $location_id AND starttime > NOW() ORDER BY starttime ASC ";
// echo($eventinlocationquery);
$eventinlocationqueryresult = mysql_query($eventinlocationquery) or die ("no locationquery");
$neventsinlocation = mysql_num_rows($eventinlocationqueryresult);
// echo($neventsinlocation);
$eventinlocationqueryresult_array = mysql_fetch_assoc($eventinlocationqueryresult);




?>

<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>Schmittens - The fat cat knows what's going on</title>
 </head>
 <body>

<?php include "files/nav.php"?>
<div class="columnleft">

<?php 
if(($neventsinlocation > 0) && ($ce != 1) ){
	
	displayeventsinlocation($location_id);
	
	displayexhibitionsinlocation($location_id);
	//echo"<div class='notice'>";
	//echo"<h1>Existing events:</h1>";
}	

?>


<div>
<h1>Create new event</h1>

<form name="newevent" method="post" enctype="multipart/form-data">



<h2>Pick a genre:</h2>
<table summary="" >
	<tr>
		<td>Party</td><td><input type="radio" name="etype" value="1000" /></td>
	</tr>
	<tr>
		<td>Concert</td><td><input type="radio" name="etype" value="2000" /></td>
	</tr>
	<tr>
		<td>Exhibition/Artshow:</td><td><input type="radio" name="etype" value="3000" /></td>
	</tr>
	<tr>
		<td>Theater/Stage:</td><td><input type="radio" name="etype" value="4000" /></td>
	</tr>
	<tr>
		<td>Other:</td><td><input type="radio" name="etype" value="5000" /></td>
	</tr>


</table>

<input type="hidden" name="location_id" value="<?php echo($location_id); ?>"/>
<input type="hidden" name="ce" value="1" />
<input type="submit" name="save" value="Save" />
</form>



</div>


</div>

<div class="columnright">



 	<div class="eventlocation">
<?php displaylocation_right($location_id);?>
	</div>

	<div class="ads"></div>

 	
</div>
<div class="footer">
<?php include"files/footer.php";  ?>
</div>
 </body>
</html>