<?php

// Username
$DBUSER = "ffdl";

// Password
$DBPASS = "ffdl";

// Database Hostname 
$DBHOST = "localhost";

// MariaDB Database
$DBDB = "ffdl";

$db = mysqli_connect("localhost","ffdl","ffdl","ffdl");
if($db->connect_errno){
	echo "ERROR Failed to connect to MariaDB instance";
	exit();	
}
?>
