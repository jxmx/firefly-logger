<?php
header('Content-Type: application/json');
include_once("functions.php");

$qkey = getGetVar("qkey");

if(!$qkey){
	returnError("one or more required values missing");
	exit;
}

try{
		$qry = "DELETE FROM qso WHERE qkey = ?";
		$qry_params = [ $qkey ];
		$res = $db->run($qry, $qry_params);
		if( $res != 1){
			throw new RuntimeException("no rows modified");
		}
		$retmsg = "deleted record";
} catch(Exception $e){
	http_response_code(400);
	print(returnError($e->getMessage()));
	exit;
}
print(returnOK($retmsg));
exit;
?>
