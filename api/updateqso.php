<?php
header('Content-Type: text/plain');

// helpers
function getPostVar($id) {
	return filter_var(trim($_POST[$id]), FILTER_SANITIZE_STRING);
}

$qkey = getPostVar("qkey");
$callsign = getPostVar("callsign");
$date = getPostVar("date");
$station = getPostVar("station");
$class = getPostVar("class");
$section = getPostVar("section");
$operator = getPostVar("operator");
$band = getPostVar("band");
$mode = getPostVar("mode");

$db = mysqli_connect("localhost","ffdl","ffdl","ffdl");
if($db->connect_errno){
	echo "ERROR Failed to connect to MariaDB instance";
	exit();	
}

$qry = sprintf("UPDATE qso SET callsign='%s', date='%s', band='%s', class='%s', mode='%s', " .
	"station='%s', section='%s', operator='%s' WHERE qkey=%d;",
	$callsign, $date, $band, $class, $mode, $station, $section, $operator, $qkey);

$res = $db->query($qry);
if($res){
	echo "OK";
} else {
	echo "ERROR " . $db->error;
}
$db->close();
?>
