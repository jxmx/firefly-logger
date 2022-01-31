<?php
header('Content-Type: text/plain');

function getPostVar($id) {
	return filter_var(trim($_GET[$id]), FILTER_SANITIZE_STRING);
}

$qkey = getPostVar("qkey");

include("db.php");

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
