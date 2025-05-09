<?php
header('Content-Type: text/plain');
include_once("functions.php");

$qkey = getPostVar("qkey");
$callsign = getPostVar("callsign");
$date = getPostVar("date");
$station = getPostVar("station");
$class = getPostVar("class");
$section = getPostVar("section");
$operator = getPostVar("operator");
$band = getPostVar("band");
$mode = getPostVar("mode");

include("db.php");

$qry = sprintf("UPDATE qso SET callsign='%s', date='%s', band='%s', class='%s', mode='%s', " .
	"station='%s', section='%s', operator='%s' WHERE qkey='%s';",
	strtoupper($callsign), $date, strtoupper($band), strtoupper($class), strtoupper($mode), 
	strtoupper($station), strtoupper($section), strtoupper($operator), strtoupper($qkey));

$res = $db->query($qry);
if($res){
	echo "OK";
} else {
	echo "ERROR " . $db->error;
}
$db->close();
?>
