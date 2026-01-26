<?php

/* XSS/SQLInject Filtering for GET/POST variables */
function cleanString($string) {
	return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5);
}

/* get GET vars */
function getGetVar($id){
	if(isset($_GET[$id])){
		return cleanString(trim($_GET[$id]));
	} else {
		return false;
	}
}

/* get POST vars */
function getPostVar($id){
	if(isset($_POST[$id])){
		return cleanString(trim($_POST[$id]));
	} else {
		return false;
	}
}

/* JSON helpers for errors */
function returnOK($msg){
	$a = [
		'ok' => $msg
	];
	return json_encode($a);
}

function returnError($msg){
	$a = [
		'error' => $msg
	];
	return json_encode($a);
}

require_once(__DIR__ . "/db.php");

?>
