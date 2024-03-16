<?php

// Username
$DBUSER = "ffdl";

// Password
$DBPASS = "changeme";

// Database Hostname 
$DBHOST = "localhost";

// MariaDB Database
$DBDB = "ffdl";

$db = mysqli_connect($DBHOST, $DBUSER, $DBPASS, $DBDB);
if($db->connect_errno){
	echo "ERROR Failed to connect to MariaDB instance";
	exit();	
}
?>
