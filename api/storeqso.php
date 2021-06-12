<?php
header('Content-Type: text/plain');

// helpers
function getPostVar($id) {
	return filter_var(trim($_POST[$id]), FILTER_SANITIZE_STRING);
}

$qkey = getPostVar("qkey");
$logclock = getPostVar("logclock");
$call = getPostVar("call");
$opclass = getPostVar("opclass");
$section = getPostVar("section");
$callsign = getPostVar("opcallsign");
$operator = getPostVar("opoperator");
$band = getPostVar("opband");
$mode = getPostVar("opmode");

$db = mysqli_connect("localhost","ffdl","ffdl","ffdl");
if($db->connect_errno){
	echo "ERROR Failed to connect to MariaDB instance";
	exit();	
}

$insqry = sprintf("INSERT INTO qso VALUES(%d,'%s','%s','%s','%s','%s','%s','%s','%s');",
	$qkey,$call,$logclock,$band,$opclass,$mode,$callsign,$section,$operator
);

$res = $db->query($insqry);
if($res){
	echo "OK";
} else {
	echo "ERROR " . $db->error;
}
$db->close();
?>
