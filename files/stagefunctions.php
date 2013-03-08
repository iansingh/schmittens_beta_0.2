<?php



function getnewstageid($created_by) {
$query = "SELECT stage_id FROM `stage` WHERE created_by = '$created_by' ORDER BY created DESC LIMIT 1";
//echo"<br />";
//echo($query);	
$result = mysql_query($query) or die ("could not get new stage entry");
$stage = mysql_fetch_assoc($result);
//echo"<br /> <br />getnewstageid: ";
//var_dump($stage);
//echo"<br />";
extract($stage);

return $stage_id;
	}
	

function stageeventcreator($stage_id,$playtime_array) {
	
// delete existing events with same stage_id first

$query = "DELETE FROM `events` WHERE stage_id = '$stage_id'";
$result = mysql_query($query);
//echo"<br />Old events deleted";

// use stage_id to get all relevant information	

$query = "SELECT * FROM `stage` WHERE stage_id = '$stage_id'";
//echo($query);
$result = mysql_query($query);
$stage_array = mysql_fetch_assoc($result);
extract($stage_array);
//var_dump($stage_array);

// use playtime_array to write individual stage-events

foreach ($playtime_array as $key => $value) {

	$query = "INSERT INTO `events`
			(`location_id`, `stage_id`, `title`, `url`, `ticket_url`, 
			`artist`, `price_min`, `price_max`, `price_free`, `donation`, `type`, `genre`,
			`starttime`,			
			`description`, `prio`, `youtube`, `created_by`, `created`, `verified`) 
			VALUES 
			('$location_id','$stage_id','$title','$url','$ticket_url',
			'$artist','$price_min','$price_max','$price_free','$donation','Stage','$genre',
			'$value', 
			'$description','$prio','$youtube','$created_by',NOW(),'$verified')";
	//echo"<br />";
	//echo($query);
	
	$result = mysql_query($query);
	}
	
	}

function stagecreator($stage_id,$playtime_array,$err) {
	
// process and extract POST-Array

//var_dump($_POST);
$location_id = mysql_real_escape_string($_POST['location_id']);
$title = mysql_real_escape_string($_POST['title']);
$url = mysql_real_escape_string($_POST['url']);
$ticket_url = mysql_real_escape_string($_POST['ticket_url']);
$artist = mysql_real_escape_string($_POST['artist']);
$price_min = mysql_real_escape_string($_POST['price_min']);
$price_max = mysql_real_escape_string($_POST['price_max']);
$price_free = mysql_real_escape_string($_POST['price_free']);
$genre = mysql_real_escape_string($_POST['eventstyle']);
$description = mysql_real_escape_string($_POST['description']);
$prio = mysql_real_escape_string($_POST['prio']);
$youtube = mysql_real_escape_string($_POST['youtube']);
$created_by = mysql_real_escape_string($_SESSION['id']);
$verified = mysql_real_escape_string($_SESSION['verification']);
//$playtime_array = $_POST['playtime'];

	// get youtube video id
	
	preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $youtube_pre, $matches);
	$youtube = $matches[0];

// check prices
	if($price_min > $price_max) {
		$save1 = $price_max;
		$save2 = $price_min;
		$price_max = $save2;
		$price_min = $save1;
		}
		
	if(($price_min == 0) && ($price_max == 0)) {
		$price_min = NULL;
		$price_max = NULL;
		}	
	
	if($price_min == $price_max) {
		$price_max = NULL;
		}

	if(($price_min == 0) && ($price_max != 0)) {
		$price_min = $price_max;
		$price_max = NULL;
		}
	
	if($price_free == 1) {
		$price_min = NULL;
		$price_max = NULL;
		$price_free = "1";
		}	
		
	if($_POST['donation'] == 1) {
		$donation = "1";
		}	

// validation stage - return error message
//echo($err);
if($genre == "") { $err++; $error['genre'] = "No genre selected"; }
if($title == "") { $err++; $error['title'] = "Title is empty"; }
if($location_id == "") { $err++; $error['location_id'] = "No location set"; }
if(!is_array($playtime_array)) { $err++; $error['playtime'] = "No performance days set"; }


	// check for duplicates

	$duplicate = "SELECT * FROM `stage` WHERE title = '$title' AND location_id = '$location_id' AND stage_id != '$stage_id'";
	// echo"<br />";
	// echo($duplicate);
	$result = mysql_query($duplicate) or die ("no duplicate query");
	$neventsconflict = mysql_num_rows($result);
	// echo"<br /> <br /> <br/>NEVENTSCONFLICT: ";
	// echo($neventsconflict);
	// echo"<br /> <br /> <br/>";
	$duplicate_array = mysql_fetch_assoc($result);
	
if($neventsconflict > 0) { $err++; $error['duplicate'] = "Similar event already in DB"; }

if($err > 0) {
	//echo"WARNING";
	//echo($err);
	//var_dump($error);
	$error['err'] = $err;
		return $error;
		break;
	}
else {
	
// decide wether to update or create a new entry
if(isset($stage_id)) {

// if a stage_id has been passed - update
	$write = "UPDATE `stage` SET 
				`location_id` = '$location_id',
				`title` = '$title',
				`url` = '$url',
				`ticket_url` = '$ticket_url',
				`artist` = '$artist',
				`price_min` = '$price_min',
				`price_max` = '$price_max',
				`price_free` = '$price_free',
				`donation` = '$donation';
				`genre` = '$genre',
				`description` = '$description',
				`prio` = '$prio',
				`youtube` = '$youtube',
				`created_by` = '$created_by',
				`modified` = NOW(),
				`verified` = '$verified'
				WHERE `stage_id` = '$stage_id'
				";
		//echo"<br />Stage_ID set,update: ";
		//echo($write);
	}
else {
	
	// if no stage_id has been passed - create new
	$write = "INSERT INTO `stage`
			(`location_id`, `title`, `url`, `ticket_url`, 
			`artist`, `price_min`, `price_max`, `price_free`, `donation`, `genre`, 
			`description`, `prio`, `youtube`, `created_by`, `created`, `verified`) 
			VALUES 
			('$location_id','$title','$url','$ticket_url',
			'$artist','$price_min','$price_max','$price_free','$donation','$genre',
			'$description','$prio','$youtube','$created_by',NOW(),'$verified')";
	//echo"<br />Stage_ID NOT set, create: ";
	//echo($write);
	}
			
$query = mysql_query($write) or die ("could not write stage entry");

if(!isset($stage_id)) {
$stage_id = getnewstageid($created_by);
}

stageeventcreator($stage_id,$playtime_array);	
	}

// forward to stage-view

			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "stage.php?stage_id=";
			header("Location: http://$host$path$site$stage_id");
			break;	
}


function getstagedata($stage_id) { 

//only get data for stage-event
$query = "SELECT * FROM `stage` WHERE stage_id = '$stage_id'";
//echo($query);
$result = mysql_query($query) or die ("no stage query");
$stagedata_array = mysql_fetch_assoc($result);

return $stagedata_array;

}

function getstageevents($stage_id) { 

//only get starttimes & id for events
$query = "SELECT starttime, event_id FROM `events` WHERE stage_id = '$stage_id' ORDER BY starttime ASC";
//echo($query);
$result = mysql_query($query) or die ("no stage query");
$stageevents_array = mysql_fetch_assoc($result);

return $stageevents_array;

}

function displaystagevents($stage_id) {
$query = "SELECT starttime, event_id FROM `events` WHERE stage_id = '$stage_id' ORDER BY starttime ASC";
//echo($query);
$result = mysql_query($query) or die ("no stage query");
$stageevents_array = mysql_fetch_assoc($result);
?>
<h2>Playtimes:</h2>
<?php

$format = "l, M dS, g a";
do { ?>
	<a href="event.php?event_id=<?php echo($stageevents_array['event_id']); ?>"><?php echo(date($format, strtotime($stageevents_array['starttime'])))?></a><br />
	<? }	
while($stageevents_array = mysql_fetch_assoc($result));
	}
	
	
function displaystagevents_datetime($stage_id) {
	
if(isset($stage_id)) {
$query = "SELECT starttime, event_id FROM `events` WHERE stage_id = '$stage_id' ORDER BY starttime ASC";
//echo($query);
$result = mysql_query($query) or die ("no stage query");
$stageevents_array = mysql_fetch_assoc($result);
?>
<h2>Playtimes:</h2>
<?php

$format = "l, M dS, g a";
do { 

$date2 = strtotime($stageevents_array['starttime']);

$m = date('n',$date2);
//echo"month: ".$m."<br />";
$d = date('j',$date2);
//echo"day: ".$d."<br />";
$y = date('Y',$date2);
//echo"year: ".$y."<br />";
$h = date('G',$date2);
//echo"hour: ".$h."<br />";
$min = date('i',$date2);
//echo"min: ".$min."<br />";

?>
<tr class="stageevent_playdate">

<td class="stageevent_dts">
	<select name="month[]">
		<?php for($x=1;$x<=12;$x++) { ?>
			<option value="<?= $x ?>"<?= ($m == $x ? ' selected="selected"' : '') ?>><?= date('F', mktime(0,0,0,$x,1)) ?></option>	
		<?php } ?>
	</select>
	<select name="day[]">
		<?php for($x=1;$x<=31;$x++) { ?>
			<option value="<?= $x ?>"<?= ($d == $x ? ' selected="selected"' : '') ?>><?= $x ?></option>	
		<?php } ?>
	</select>
	<select name="year[]">
		<?php for($x=2010;$x<=2015;$x++) { ?>
			<option value="<?= $x ?>"<?= ($y == $x ? ' selected="selected"' : '') ?>><?= $x ?></option>	
		<?php } ?>		
	</select> / 
	<select name="hour[]">
		<?php for($x=0;$x<=23;$x++) { ?>
			<option value="<?= $x ?>"<?= ($h == $x ? ' selected="selected"' : '') ?>><?= $x ?></option>	
		<?php } ?>
	</select>	
	<select name="minute[]">
		<?php for($x=0;$x<60;$x = $x + 15) { ?>
			<option value="<?= $x ?>"<?= ($min== $x ? ' selected="selected"' : '') ?>><?= $x ?></option>	
		<?php } ?>
	</select> 
</td>
<td></td>
</tr>	
	<? }	
while($stageevents_array = mysql_fetch_assoc($result));

	}

else { ?>

<tr class="stageevent_playdate">

<td class="stageevent_dts">
	<select name="month[]">
		<?php for($x=1;$x<=12;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("n") == $x ? ' selected="selected"' : '') ?>><?= date('F', mktime(0,0,0,$x,1)) ?></option>	
		<?php } ?>
	</select>
	<select name="day[]">
		<?php for($x=1;$x<=31;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("j") == $x ? ' selected="selected"' : '') ?>><?= $x ?></option>	
		<?php } ?>
	</select>
	<select name="year[]">
		<?php for($x=2010;$x<=2015;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("Y") == $x ? ' selected="selected"' : '') ?>><?= $x ?></option>	
		<?php } ?>		
	</select> / 
	<select name="hour[]">
		<?php for($x=0;$x<=23;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("g") == $x ? ' selected="selected"' : '') ?>><?= $x ?></option>	
		<?php } ?>
	</select>	
	<select name="minute[]">
		<?php for($x=0;$x<60;$x = $x + 15) { ?>
			<option value="<?= $x ?>"<?= (date("i") == $x ? ' selected="selected"' : '') ?>><?= $x ?></option>	
		<?php } ?>
	</select> 
</td>
<td></td>
</tr>	

<?php }

	}


?>