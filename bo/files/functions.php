<?php 
function checklogin() {
	// Check if User is logged in & if not send to login page
	if($_SESSION["in"] == FALSE) {
  	  	echo("<a href=\"login.php\">Log in!</a>"); 
  	  		$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "login.php";
			header("Location: http://$host$path$site");
			
			// header("Location: $_SERVER['HTTP_REFERER']");
			exit;	
   	} 
	}

function locationquery() {
	// prepare query		
	$locationquery = "SELECT location_id, l_name, street, streetnumber, postalcode, province, type FROM `location` WHERE location_id > 0";
	
	// execute query
	$locationqueryresult = mysql_query($locationquery) or die ("<br />no query");

	
	$result_array = mysql_fetch_assoc($locationqueryresult);
	return $result_array;	
	}

function getlocationids() {
	// generate # of events for each location with link									
	// prepare location_id
	// $location_id = ($locationresult_array["location_id"]);
	//php displays only require
	global $location_id;
	global $nevents;
	// prepare query
	$neventsquery = "SELECT COUNT(*) FROM `events` WHERE location_id = $location_id";
	
	// execute query
	$neventsqueryresult = mysql_query($neventsquery) or die ("<br />no nevents query");		
	
	$neventsresult_array = mysql_fetch_assoc($neventsqueryresult);	
	$nevents = $neventsresult_array["COUNT(*)"];

	return $nevents;
	}
	
	
function dbconnect() {
	if (($connection = mysql_connect(HOST, USER, PASS)) === FALSE)
			die("Could not connect to database");
		
	if (mysql_select_db(DB, $connection) === FALSE)
			die("Could not select database");

	mysql_set_charset("utf8");
	}





  function smart_resize_image($file,
                              $width = 0,
                              $height = 0,
                              $proportional = false,
                              $output = 'file',
                              $delete_original = true,
                              $use_linux_commands = false ) {
      
    if ( $height <= 0 && $width <= 0 ) return false;

    # Setting defaults and meta
    $info = getimagesize($file);
    $image = '';
    $final_width = 150; 
    $final_height = 0;
    list($width_old, $height_old) = $info;

    # Calculating proportionality
    if ($proportional) {
      if ($width == 0) $factor = $height/$height_old;
      elseif ($height == 0) $factor = $width/$width_old;
      else $factor = min( $width / $width_old, $height / $height_old );

      $final_width = round( $width_old * $factor );
      $final_height = round( $height_old * $factor );
    }
    else {
      $final_width = ( $width <= 0 ) ? $width_old : $width;
      $final_height = ( $height <= 0 ) ? $height_old : $height;
    }

    # Loading image to memory according to type
    switch ( $info[2] ) {
      case IMAGETYPE_GIF: $image = imagecreatefromgif($file); break;
      case IMAGETYPE_JPEG: $image = imagecreatefromjpeg($file); break;
      case IMAGETYPE_PNG: $image = imagecreatefrompng($file); break;
      default: return false;
    }
    
    
    # This is the resizing/resampling/transparency-preserving magic
    $image_resized = imagecreatetruecolor( $final_width, $final_height );
    if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
      $transparency = imagecolortransparent($image);

      if ($transparency >= 0) {
        $transparent_color = imagecolorsforindex($image, $trnprt_indx);
        $transparency = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
        imagefill($image_resized, 0, 0, $transparency);
        imagecolortransparent($image_resized, $transparency);
      }
      elseif ($info[2] == IMAGETYPE_PNG) {
        imagealphablending($image_resized, false);
        $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
        imagefill($image_resized, 0, 0, $color);
        imagesavealpha($image_resized, true);
      }
    }
    imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $final_width, $final_height, $width_old, $height_old);
    
    # Taking care of original, if needed
    if ( $delete_original ) {
      if ( $use_linux_commands ) exec('rm '.$file);
      else @unlink($file);
    }

    # Preparing a method of providing result
    switch ( strtolower($output) ) {
      case 'browser':
        $mime = image_type_to_mime_type($info[2]);
        header("Content-type: $mime");
        $output = NULL;
      break;
      case 'file':
        $output = $file;
      break;
      case 'return':
        return $image_resized;
      break;
      default:
      break;
    }
    
    # Writing image according to type to the output destination
    switch ( $info[2] ) {
      case IMAGETYPE_GIF: imagegif($image_resized, $output); break;
      case IMAGETYPE_JPEG: imagejpeg($image_resized, $output); break;
      case IMAGETYPE_PNG: imagepng($image_resized, $output); break;
      default: return false;
    }

    return true;
  }

function truncate($string, $limit, $break=" ", $pad="...") {
	 // return with no change if string is shorter than $limit 
	 if(strlen($string) <= $limit) return $string; 
	 // is $break present between $limit and the end of the string? 
	 if(false !== ($breakpoint = strpos($string, $break, $limit))) 
	 { 
	 	if($breakpoint < strlen($string) - 1) 
	 	{ 
	 		$string = substr($string, 0, $breakpoint) . $pad; 
	 	} 
	 } return $string; 
	 }


?>