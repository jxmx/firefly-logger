<?php
header('Content-Type: text/html');
include_once("functions.php");

$stationCall = getPostVar("stationCall");
$fdType = getPostVar("fdType");
$multiOp = getPostVar("multiOp");

if( strlen($stationCall) == 0 || strlen($fdType) == 0 ){
	print "Missing at least one of stationCall, fdType, multiOp";
	return 1;
}

$j['stationCall'] = strtoupper($stationCall);
$j['fdType'] = $fdType;

if( $multiOp == "on" ){
	$j['multiOp'] = "true";
} else {
	$j['multiOp'] = "false";
}


$f = fopen("config_general.json", "w");
fwrite($f, json_encode($j));
fclose($f);

header('Location: /configmgr.html');
?>
