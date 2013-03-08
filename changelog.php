<?php
session_start();

	require "files/functions.php";
	require "files/include.php";	
	require "files/datetimepicker.php";

//	dbconnect();
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>Schmittens - Event yourself! - Changelog</title>
 </head>
 <body>
<div class="">
<?php include "files/nav.php" ?>
</div>

<div class="columnleft">
<h1>Schmittens Beta - Changelog</h1>

<h2>Current version: 0.2.1</h2>
<ul>
	<li>Updated functionality for multi-date events (exhibition & theatre)</li>
	<li>Added event-genres</li>
	<li>Improved UI</li>
</ul>


<h2>Known bugs</h2>



<h2>Planned features</h2>
<ul>
	<li>Festivals</li>
	<li>Search/Filter by Genre</li>
	<li>Promoter-Accounts</li>
	<li>Forum-integration</li>
	<li>Outputting user pictures</li>
	<li>Mobile app</li>
</ul>


<h2>Version history</h2>

<h3>0.1</h3>
<p>Alpha-build, proof of concept</p>

</div>



<div class="columnright"></div>
 
<div class="footer">
<?php include"files/footer.php";  ?>
</div>
 </body>
</html>