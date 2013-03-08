<?php //error_reporting(0); //E_ALL & ~E_NOTICE
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
 	<link rel="stylesheet" href="style.css" media="screen" />
  <title>Schmittens - The fat cat knows what's going on</title>
 </head>
 <body>
 
<!-- <div class="header"> -->
<!-- <div class="menu"> --> 
 <ul id="nav" >
 	<li style="margin-left:-35px;	font-weight:bold; "><a href="/index.php" >schmittens.&alpha;</a></li>
 	<li><a href="/eventlist.php" >Listings</a>
 		<ul>
 			<li><a href="/eventlist.php" >All</a></li>
 			<li><a href="/eventlist.php?eventtype=1&time=1&list=List+events">Party</a></li>
 			<li><a href="/eventlist.php?eventtype=2&time=1&list=List+events">Concert</a></li>
 			<li><a href="/eventlist.php?eventtype=3&time=1&list=List+events">Stage</a></li>
 			<li><a href="/eventlist.php?eventtype=4&time=1&list=List+events">Art</a></li>
 			<li><a href="/eventlist.php?eventtype=5&time=1&list=List+events">Other</a></li>
 			<li> </li>
 			<li> </li>
 			<li><a href="/locationlist.php" >Locations</a></li>
 		</ul>
 	</li>
 	<?php if($_SESSION["in"] == TRUE) { ?>
 	<li><a href="/create_event.php">Create</a>
		<ul>
			<li><a href="/create_event.php" >Event</a></li>
			<li><a href="/c_location.php" >Location</a></li>
		</ul> 	
 	</li> <?php } ?>
	<li><a href="/about.php" >FAQ</a></li>	
	<?php if($_SESSION["in"] == TRUE) { echo
	"<li><a href='useradmin.php'>Account</a></li>"; } ?>
	<?php if(($_SESSION['in']) == FALSE) { echo
	"<li><a href='/login.php' >Login/Sign up</a></li>"; }
	else { echo
 	"<li><a href='/logout.php' >Logout</a></li>"; } ?>	
 	<li style="position: abolute; margin-top:7px;right: -60px;">
		<form action="search.php" method="post">
		<input type="text" name="searchfield" value="<?php if (isset($_POST['searchfield'])) { echo $searchfield; } ?>" size="8" />
		<input type="submit" value="Search">
		</form> 	
 	
 	
 	</li>
 </ul> 
<!-- </div> -->

 
 </body>
