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
	strtoupper($qkey),strtoupper($call),$logclock,strtoupper($band),strtoupper($opclass),
	strtoupper($mode),strtoupper($callsign),strtoupper($section),strtoupper($operator)
);

try {
	$res = $db->query($insqry);
	if($res){
		echo "OK";
	} else {
		echo "ERROR " . $db->error;
	}
} catch(Exception $e) {
	echo "ERROR - DB - " . $e->getMessage();
} finally {
	$db->close();
}
?>
