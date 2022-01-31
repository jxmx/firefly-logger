<?php
header('Content-Type: text/plain');

// helpers
function getGetVar($id) {
	return filter_var(trim($_GET[$id]), FILTER_SANITIZE_STRING);
}

$qkey = getGetVar("qkey");

include("db.php");

$insqry = sprintf("DELETE FROM qso WHERE qkey='%s'", $qkey);
$res = $db->query($insqry);
if($res){
	echo "OK";
} else {
	echo "ERROR " . $db->error;
}
$db->close();
?>
