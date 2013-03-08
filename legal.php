<?php
session_start();

	require "files/functions.php";
	require "files/include.php";	
	require "files/datetimepicker.php";
	require "files/nav.php";

//	dbconnect();
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>Schmittens - Copyright & Legal</title>
 </head>
 <body>

<div class="columnleft">
<h1>Legal & Copyright</h1>
<p>Schmittens.net is currently in public alpha! The site is under heavy development and we don't give any guarantees of any kind about anything whatsoever!</p>
<p>Schmittens.net is in no way responsible for any of the user- or third-party-generated content on this site.</p>
<p>With that out of the way: We will of course do our best and try to keep the site functional at any time. As we add more features over time we aim to improve the service and usability.</p>

<p>If you choose to use the service in any capacity you must accept the <a href="tou.php">Terms of Use</a>. Failure to follow these terms may result in termination of the user account.</p>
</div>

<div class="columnright"></div>
 
<div class="footer">
<?php include"files/footer.php";  ?>
</div>
 </body>
</html>