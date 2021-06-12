<?php
header('Content-Type: text/plain');

function getPostVar($id) {
	return filter_var(trim($_POST[$id]), FILTER_SANITIZE_STRING);
}

$qkey = getPostVar("qkey");

$db = mysqli_connect("localhost","ffdl","ffdl","ffdl");
if($db->connect_errno){
	echo "ERROR Failed to connect to MariaDB instance";
	exit();	
}

$insqry = sprintf("SELECT COUNT(qkey) FROM qso WHERE qkey=%d", $qkey);
$res = $db->query($insqry);
if($res){
	echo "DUPE";
} else {
	echo "OK";
}
$db->close();
?>
