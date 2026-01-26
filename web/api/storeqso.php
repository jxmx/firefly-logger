<?php
header('Content-Type: application/json');
include_once("functions.php");

$qkey = getPostVar("qkey");
$logclock = getPostVar("logclock");
$call = getPostVar("call");
$opclass = getPostVar("opclass");
$section = getPostVar("section");
$callsign = getPostVar("opcallsign");
$operator = getPostVar("opoperator");
$band = getPostVar("opband");
$mode = getPostVar("opmode");

if( ! ( $qkey || $logclock || $call || $opclass || $section || $callsign || $operator || $band || $mode ) ){
	returnError("one or more required values missing");
	exit;
}

$retmsg = "";

try{
	$qry = <<<EOT
		INSERT INTO qso VALUES(?,?,?,?,?,?,?,?,?);
		EOT;

	$qry_params = [
		strtoupper($qkey),
		strtoupper($call),
		$logclock,
		strtoupper($band),
		strtoupper($opclass),
		strtoupper($mode),
		strtoupper($callsign),
		strtoupper($section),
		strtoupper($operator)
	];

	$res = $db->run($qry, $qry_params);
	if( $res != 1){
		throw new RuntimeException("insertion error");
	}

	$retmsg = "updated 1 record";

} catch(Exception $e){
	http_response_code(400);
	print(returnError($e->getMessage()));
	exit;
}

print(returnOK($retmsg));
exit;
?>
