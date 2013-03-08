<?php

$host = "localhost"; 
$user = "schmitte_piter"; 
$pass = "RdR;M3Tg#T?t"; 

$r = mysql_connect($host, $user, $pass);

if (!$r) {
    echo "Could not connect to server\n";
    trigger_error(mysql_error(), E_USER_ERROR);
} else {
    echo "Connection established\n"; 
}

echo mysql_get_server_info() . "\n"; 

mysql_close();

?>