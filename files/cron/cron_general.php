<?php 

// nightly cronjobs around 4 am
/*

Handled here are:
- Moving past events into seperate tables
- Daily backups

*/


// select all events that happened in the past and move them to events_old

$qry1=mysql_query("INSERT INTO `events_old` SELECT * FROM `events` WHERE starttime < NOW()");

$qry2=mysql_query("DELETE FROM `events` WHERE starttime < NOW()"); 


// backup complete database



?>