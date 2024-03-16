<?php
header('Content-Type: text/plain');
include_once("functions.php");
include("db.php");

$qkey = getGetVar("qkey");
if(!$qkey){
	echo "ERROR missing qkey var";
	exit;
}

$insqry = sprintf("DELETE FROM qso WHERE qkey='%s'", $qkey);
$res = $db->query($insqry);
if($res){
	echo "OK";
} else {
	echo "ERROR " . $db->error;
}
$db->close();
?>
