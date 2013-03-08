<?php 

require "files/header.php";
require "files/nav.php";

// connect to db
dbconnect();

// Check if User is logged in & if not send to login page
// checklogin();


 

if(($_GET) == FALSE) 
{
	echo "No event selected";
}
$event_id = $_GET['event_id'];


// get event info
$event = geteventdata($event_id);
$source_url = $event['source_url'];
$sourceev = $event['source'];

//var_dump($event);
extract($event);

$user_id = $created_by;

// prepare price
if($price_free == 1) {
	$price = "Free";
	$priceflag = 1;}
if(($price_min == 0) && ($price_free == 0)) {
	$price = "";
	$priceflag = 0;}
if(($price_min != 0) && ($price_max == 0)){
	$price = $price_min."$";
	$priceflag = 1;}
if(($price_min != 0) && ($price_max != 0)) {
	$price = $price_min." - ".$price_max."$";
	$priceflag = 1;
	}

// prepare donation
if($donation == 1) {$donation_text = "Donation";}


// get locationdata
$location = getlocationdata($location_id);

// get userdata
$user = getuserdata($user_id);


//check if user is active
isuseractive($user_id);



$format = "D, M j, g a";
$format2= "M j";
$format3 = "g a";

$status = feedbackmail();

?>

<!-- <html xmlns="http://www.w3.org/1999/xhtml"> -->
<html xmlns:fb="http://ogp.me/ns/fb#">
 <head>
  <title>Schmittens - <?php echo($title); ?> - <?php $phpdate = strtotime($starttime); echo(date($format, $phpdate)); ?> @ <?php echo $location['l_name'] ?></title>
   <meta property="og:title" content="Schmittens - <?php echo($title); ?> - <?php $phpdate = strtotime($starttime); echo(date($format, $phpdate)); ?> @ <?php echo $location['l_name'] ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="http://www.schmittens.net/event.php?event_id=<?php echo($event_id); ?>" />
	<meta property="og:image" content="http://www.schmittens.net/img/event/<?php echo($event_id);?>_480.jpg" />
	<meta property="og:site_name" content="Schmittens - Event yourself!" />
	<meta property="fb:admins" content="558392998" />
 </head>
 <body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script> 

 	
<div class="columnleft">

<?php if($status != FALSE) {?>
<div class="hint"><?php echo($status); ?></div>
<?php } ?>

<div class="event">
<img src="/img/event/<?php echo($event['img_480']) ?>" alt="" />

	<div class="event_txt">
	<h3><?php echo($title); ?> - <i><?php echo($event['type']); echo" - "; echo(getgenrename($event['genre']));?></i></h3>
	<p><?php $phpdate = strtotime($starttime); echo(date($format, $phpdate)); ?>	
	<?php if($set_endtime == 1) { ?> to <?php $phpdate = strtotime($endtime); echo(date($format3, $phpdate));} else { echo"";  ?>	<?php } ?></p>
	<?php if(isset($artist) == TRUE) { ?><p><?php echo($artist); ?></p> <?php } ?>

	<?php if($description != NULL) {	?><p><?php echo($description); ?></p> <?php } ?>
	<?php if($priceflag == 1) { ?><p><?php echo($price); } if(($priceflag == 1) && ($donation == 1)) {echo" | ";}?><?php if($donation == 1) { ?><?php echo($donation_text); }?> <?php if($ticket_url != NULL) { ?><p><a href="<?php echo($ticket_url); ?>" target="blank">Get Tickets</a></p>	<?php } ?>
	<?php if($event['url'] != NULL) { ?><p><a href="<?php echo($event['url']); ?>" target="blank">Website</a></p><?php } ?>	
	<?php if($verified != 0) {	?><p>Verified Event</p><?php } ?>
	<?php if($source_url != "") { ?><p>Source: <a href="<?php echo($source_url); ?>" target="_blank" ><?php echo($sourceev);?></a></p> <?php } ?>

	</div>
<?php display_youtube($youtube); 

if($user_id == $_SESSION['id']) { ?>
<br/>
<form action="e_event.php" method="post">
	<input type="hidden" name="event_id" value="<?php echo($event_id) ?>" />				
	<input type="submit" value="Edit event" />		

</form>

<?php } ?>
</div>
<br />



<?php

 feedbackdisplay($status); ?>

</div>

<div class="columnright">
<div class="eventlocation">
<?php displaylocation_right($location_id); ?>
</div>
<?php display_eventcreator($event_id,$genre); ?>	
	
	<br>
	
	<br />
	<div class="socialmedia">
	Share this event:
	<div class="twittershare">
	<a href="https://twitter.com/share" class="twitter-share-button" data-via="SchmittensEvent" data-hashtags="eventsmtl">Tweet</a>
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	</div>
	<div class="facebookshare">	
	<fb:like send="true" layout="button_count" width="240" show_faces="false"></fb:like>
	</div>
	<div class="pinterest">
	<?php pinterest($event_id,$title,$img_480);?>
	</div>
	</div>


</div>

<div class="footer">
<?php include"files/footer.php";  ?>
</div>
 </body>
 </html>	
		