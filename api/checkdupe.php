<?php
header('Content-Type: text/plain');

function getPostVar($id) {
	return filter_var(trim($_GET[$id]), FILTER_SANITIZE_STRING);
}

$qkey = getPostVar("qkey");

$db = mysqli_connect("localhost","ffdl","ffdl","ffdl");
if($db->connect_errno){
	echo "ERROR Failed to connect to MariaDB instance";
	exit();	
}

$insqry = sprintf("SELECT COUNT(qkey) FROM qso WHERE qkey=%d", $qkey);
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
