<?php 

function exhibitioncreator($ex_id,$location_id) {
	
//	echo"<br /><br /><br />";
	
	//var_dump($_POST);
	// check if exhibition has opening hours. 
	
	$exohcheck = "SELECT * FROM exhibitions WHERE ex_id = '$ex_id'";
	$exohcheck_result = mysql_query($exohcheck) or die ("no exohcheck query");
	$exohcheck_array = mysql_fetch_assoc($exohcheck_result);
	
//	echo"<br />EXOHCHECK: ";
//	var_dump($exohcheck_array);
	
	//initialise step variable
	/* 0: Beginning of script 
		1: no exhibition-opening hours - go to location
		2: exhibition-opening hours present
		3: no location-opening hours - panic
		4: location-opening hours present
	*/
	$step = 0;
	
	if(($exohcheck_array['monday_s'] == '00:00:00') &&
		 ($exohcheck_array['tuesday_s'] == '00:00:00') &&
		 ($exohcheck_array['wednesday_s'] == '00:00:00') &&
		 ($exohcheck_array['thursday_s'] == '00:00:00') &&
		 ($exohcheck_array['friday_s'] == '00:00:00') &&
		 ($exohcheck_array['saturday_s'] == '00:00:00') &&
		 ($exohcheck_array['sunday_s'] == '00:00:00')) 
		 {
		 	$step = 1;
		 }
	else { $step = 2; }
	
		// if so - use these
		
	
		// if not - check if location has opening hours
		
		if($step == 1) {
			
			//echo"<br />Step: ".$step."<br />";
			
			// check if location has opening hours
			$loohcheck = "SELECT * FROM location WHERE location_id = '$location_id'";
			$loohcheck_result = mysql_query($loohcheck) or die ("no exohcheck query");
			$loohcheck_array = mysql_fetch_assoc($loohcheck_result);
			
			//echo"<br />LOOHCHECK:";
			//var_dump($loohcheck_array);
			
			if(($loohcheck_array['monday_s'] == '00:00:00') &&
				 ($loohcheck_array['tuesday_s'] == '00:00:00') &&
				 ($loohcheck_array['wednesday_s'] == '00:00:00') &&
				 ($loohcheck_array['thursday_s'] == '00:00:00') &&
				 ($loohcheck_array['friday_s'] == '00:00:00') &&
				 ($loohcheck_array['saturday_s'] == '00:00:00') &&
				 ($loohcheck_array['sunday_s'] == '00:00:00'))
				 {
				 	$step = 3;
				 }			
			else { $step = 4; }
			
			
		}
			
		if($step == 2) {
			
		//echo"<br />Step: ".$step."<br />";
			
			// do stuff
			// prepare variables with data from exohcheck_array
			$monday_s = $exohcheck_array['monday_s'];
			$monday_e = $exohcheck_array['monday_e'];
			$tuesday_s = $exohcheck_array['tuesday_s'];
			$tuesday_e = $exohcheck_array['tuesday_e'];
			$wednesday_s = $exohcheck_array['wednesday_s'];
			$wednesday_e = $exohcheck_array['wednesday_e'];
			$thursday_s = $exohcheck_array['thursday_s'];
			$thursday_e = $exohcheck_array['thursday_e'];
			$friday_s = $exohcheck_array['friday_s'];
			$friday_e = $exohcheck_array['friday_e'];
			$saturday_s = $exohcheck_array['saturday_s'];
			$saturday_e = $exohcheck_array['saturday_e'];
			$sunday_s = $exohcheck_array['sunday_s'];
			$sunday_e = $exohcheck_array['sunday_e'];
			$step = 5;
			// set step to 5
			
		}
			
		if($step == 3) { 
		
		//echo"<br />Step: ".$step."<br />";
		// panic & forward to edit-site to edit exhibition opening hours

			// Set $_POST['err_noopen']
			$_POST['err_noopen'] = TRUE;
			
			//forward
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "e_exhibition.php?ex_id=";
			header("Location: http://$host$path$site$ex_id");
		
		}
		
		if($step == 4) {
		
		//echo"<br />Step: ".$step."<br />";
		
			// do stuff
			// prepare variables with data from loohcheck_array
			$monday_s = $loohcheck_array['monday_s'];
			$monday_e = $loohcheck_array['monday_e'];
			$tuesday_s = $loohcheck_array['tuesday_s'];
			$tuesday_e = $loohcheck_array['tuesday_e'];
			$wednesday_s = $loohcheck_array['wednesday_s'];
			$wednesday_e = $loohcheck_array['wednesday_e'];
			$thursday_s = $loohcheck_array['thursday_s'];
			$thursday_e = $loohcheck_array['thursday_e'];
			$friday_s = $loohcheck_array['friday_s'];
			$friday_e = $loohcheck_array['friday_e'];
			$saturday_s = $loohcheck_array['saturday_s'];
			$saturday_e = $loohcheck_array['saturday_e'];
			$sunday_s = $loohcheck_array['sunday_s'];
			$sunday_e = $loohcheck_array['sunday_e'];
			$step = 5;
			// set step to 5
			
		}
		
		if($step == 5) {
			
			
			//prepare general variables
			
			$title = $exohcheck_array['e_title'];
			$startdate = $exohcheck_array['startdate'];
			$enddate = $exohcheck_array['enddate'];
			$url = $exohcheck_array['url'];
			$ticket_url = $exohcheck_array['ticket_url'];
			$artist = $exohcheck_array['artist'];
			$price_min = $exohcheck_array['price_min'];
			$price_max = $exohcheck_array['price_max'];
			$price_free = $exohcheck_array['price_free'];
			$donation = $exohcheck_array['donation'];
			$type = "Exhibition";
			$genre = $exohcheck_array['genre'];
			$description = $exohcheck_array['description'];
			$prio = $exohcheck_array['prio'];
			$img_480 = $exohcheck_array['img_480'];
			$img_240 = $exohcheck_array['img_240'];
			$img_160 = $exohcheck_array['img_160'];
			$img_present = $exohcheck_array['img_present'];
			$youtube = $exohcheck_array['youtube'];
			$created_by = $exohcheck_array['created_by'];
			$changed = $exohcheck_array['changed'];
			
			// delete previous events with the same ex_id
			
			$eventdelete = "DELETE FROM `events` WHERE `ex_id` = '$ex_id'";
			//echo"<br />Eventdelete: ".$eventdelete."<br />";
			$eventdelete_result = mysql_query($eventdelete) or die ("no eventdelete");
			

			//create events		
			
			// create weekday-array for all days that have opening hours
			
			//echo($startdate);
			
			$day1 = date('l', strtotime( $startdate));
			$day2 = date('l', strtotime( $startdate." + 1 day"));	
			$day3 = date('l', strtotime( $startdate." + 2 day"));	
			$day4 = date('l', strtotime( $startdate." + 3 day"));	
			$day5 = date('l', strtotime( $startdate." + 4 day"));	
			$day6 = date('l', strtotime( $startdate." + 5 day"));	
			$day7 = date('l', strtotime( $startdate." + 6 day"));	
			
			$week_array = array(
				0 => $day1,
				1 => $day2,
				2 => $day3,
				3 => $day4,
				4 => $day5,
				5 => $day6,
				6 => $day7,
			);
			
			//echo"<br/>Week_Array: ";
			//var_dump($week_array);
			
			$offset = date('w', strtotime($day1));
			//echo"<br />".$offset;
			$iterations = $offset - 1;
			//echo"<br />Iterations: ";			
			//echo($iterations);
			//echo"<br />";
			/*
			echo"<br />";
			echo($day1);
			echo" - ";
			echo($offset);
			echo"<br />";
			echo($startdate);
			echo" - ";
			echo($enddate);
			echo"<br />";
			*/
			
			// define array for starting times

			$week_s = array(
				0 => $monday_s,
				1 => $tuesday_s,
				2 => $wednesday_s,
				3 => $thursday_s,
				4 => $friday_s,
				5 => $saturday_s,
				6 => $sunday_s,
			);
			
	
	// rearrange array according to first day in exhibition-week
	
	$week_s = dayshuffle($week_s, $iterations);


	
			// define array for ending times
			$week_e = array(
				0 => $monday_e,
				1 => $tuesday_e,
				2 => $wednesday_e,
				3 => $thursday_e,
				4 => $friday_e,
				5 => $saturday_e,
				6 => $sunday_e,
			);
			
	// rearrange array according to first day in exhibition-week
			
	$week_e = dayshuffle($week_e, $iterations);
			
			$week_add = array(
				'Monday' => 0,
				'Tuesday' => 1,
				'Wednesday' => 2,
				'Thursday' => 3,
				'Friday' => 4,
				'Saturday' => 5,
				'Sunday' => 6,				
			
			);
			//echo"<br />Week_S: ";
			//var_dump($week_s);


			$x = 0;	


			// for each week in the daterange
			for ($i = 0; $i < ((strtotime($enddate) - strtotime($startdate)) / 604800); $i++){ 
					
					
				// for each weekday
				foreach ($week_s as $key => $value)
					{
						$z = 0;

					
						// for each day: calculate date and write if opening hours are present
						if(strtotime($value) != strtotime('00:00:00')) { 

							$y = $x;							

							$nextweek = $y*86400;
							//	echo($nextweek);
							$start = strtotime($startdate) + $nextweek;

							$end = strtotime($startdate) + $nextweek;

							$format = "Y-m-d";
							//echo"<br />".$key." ";

							//echo"<br />".$key.": ".$value."<br />";
							$date1 = date($format, $start);
							$datetimestart = $date1." ".$value;
							$date2 = date($format, $end);
							$value2 = $week_e[$key];
							$datetimeend = $date2." ".$value2;
							//echo"<br />Time: ".$datetimestart." - ".$datetimeend." Z: ".$z;
							
							if(strtotime($enddate) >= strtotime($date1)) {
							
							$eventwrite = "INSERT INTO `events` (`location_id`, `ex_id`, `title`, `url`, `ticket_url`, `artist`, 
																			 `price_min`, `price_max`, `price_free`, `donation`, `type`, `genre`, 
																			 `starttime`, `endtime`, `set_endtime`, 
																			 `description`, `prio`, `img_original`, `img_480`, `img_320`, 
																			 `img_240`, `img_160`, `img_present`, `youtube`, 
																			 `created_by`, `created`, `modified`, `verified`, 
																			 `source`, `source_id`, `source_url`) 
																VALUES ('$location_id', '$ex_id', '$title', '$url', '$ticket_url', '$artist',
																		  '$price_min', '$price_max', '$price_free', '$donation', '$type', '$genre',
																		  '$datetimestart', '$datetimeend', '1',
																		  '$description', '$prio', '$img_original', '$img_480', '$img_320',
																		  '$img_240', '$img_160', '$img_present', '$youtube', 
																		  '$created_by', '$created', '$modified', '$verified',
																		  '$source', '$source_id', '$source_url')";
							
							//echo"<br />".$eventwrite;
							$eventwriteresult = mysql_query($eventwrite) or die ("no eventwrite");
							
												
							}
							
							
							}
					$x++;	
					$z++;

					
					}
			}
				return TRUE;	
		}
		

	
	}


?>