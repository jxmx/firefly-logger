<?php
header('Content-Type: text/plain');
include_once("functions.php");

$qkey = getPostVar("qkey");
$logclock = getPostVar("logclock");
$call = getPostVar("call");
$opclass = getPostVar("opclass");
$section = getPostVar("section");
$callsign = getPostVar("opcallsign");
$operator = getPostVar("opoperator");
$band = getPostVar("opband");
$mode = getPostVar("opmode");

if( ! ( $qkey || $logclock || $call || $opclass || $section || $callsign || $operator || $band || $mode ) ){
	echo "ERROR missing param";
	exit;
}

include("db.php");

$insqry = sprintf("INSERT INTO qso VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s');",
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
