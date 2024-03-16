<?php
header('Content-Type: text/plain');
include("db.php");
include_once("functions.php");

$qkey = getGetVar("qkey");
$insqry = sprintf("SELECT COUNT(qkey) FROM qso WHERE qkey='%s'", $qkey);
if($res = $db->query($insqry)){
	$row = $res->fetch_row();
	if( $row[0] == 1 ){
		echo "DUPE";
	} else {
		echo "OK";
	}
} else {
	echo "ERROR";
}
$db->close();
?>
