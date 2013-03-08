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
  <title>Schmittens - Contact</title>
 </head>
 <body>

<div class="columnleft">
<h1>Contact us</h1>

<h2>listings[at]schmittens.net</h2>
<p>Use this address if you...</p>
<ul>
	<li>... have updates to an existing event that you did not create yourself. If you created the event yourself you can simply log in and change what needs changing.</li>
	<li>... are an organizer or represent a location and want to send us your monthly/weekly newsletter. <b>Please note:</b> While we will do our best to input all the data we receive we can not guarantee that we can manage it all on our own! <b>Please enter your events yourself if you can!</b> This way you have more control, it's faster and you can make changes quickly yourself!</li>
</ul>

<h2>support[at]schmittens.net</h2>
<p>Use this address if you...</p>
<ul>
	<li>... experience a technical problem while using the site.</li>
	<li>... find a bug. Please tell us, and we'll fix it.</li>
	<li>... have any questions where functionality of the site is concerned.</li>
</ul>

<h2>contact[at]schmittens.net</h2>
<p>Use this address for everything else.
</div>

<div class="columnright"></div> 

<div class="footer">
<?php include"files/footer.php";  ?>
</div>
 
 </body>
</html>