<?php 

$timezone = date_default_timezone_get();
echo "The current server timezone is: " . $timezone;


$date = date('Y-m-d h:i:s a', time());
echo($date);

 ?>