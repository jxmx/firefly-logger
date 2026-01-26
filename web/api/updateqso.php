<?php
header('Content-Type: application/json');
include_once("functions.php");

$oldqkey = getPostVar("oldqkey");
$newqkey = getPostVar("newqkey");
$callsign = getPostVar("callsign");
$date = getPostVar("date");
$station = getPostVar("station");
$class = getPostVar("class");
$section = getPostVar("section");
$operator = getPostVar("operator");
$band = getPostVar("band");
$mode = getPostVar("mode");

if( ! ( $oldqkey || $newqkey || $callsign || $date ||
		$station || $class || $section || $operator || $band || $mode ) ){
	http_response_code(400);
	returnError("one or more required values missing");
	exit;
}


$retmsg = "";

try{
	if( $oldqkey === $newqkey){
		$qry = <<<EOT
			UPDATE qso SET callsign=?, date=?, band=?, class=?, mode=?,
			station=?, section=?, operator=? WHERE qkey=?;
			EOT;
		$qry_params = [
			strtoupper($callsign),
			$date,
			strtoupper($band),
			strtoupper($class),
			strtoupper($mode),
			strtoupper($station),
			strtoupper($section),
			strtoupper($operator),
			strtoupper($oldqkey)
		];

		$res = $db->run($qry, $qry_params);
		if( $res != 1){
			throw new RuntimeException("no rows modified");
		}
		$retmsg = "updated 1 record - same qkey";
	} elseif( $oldqkey != $newqkey){
		$db->pdo()->beginTransaction();

		$qry = <<<EOT
			DELETE FROM qso WHERE qkey=?;
			EOT;
		$qry_params = [ $oldqkey];
		$db->run($qry, $qry_params);

		$qry = <<<EOT
			INSERT INTO qso VALUES(?,?,?,?,?,?,?,?,?);
			EOT;
		$qry_params = [
			strtoupper($newqkey),
			strtoupper($callsign),
			$date,
			strtoupper($band),
			strtoupper($class),
			strtoupper($mode),
			strtoupper($station),
			strtoupper($section),
			strtoupper($operator),
		];
		$db->run($qry, $qry_params);

		$db->pdo()->commit();
		$retmsg = "del/insert for different qkeys";
	} else {
		RuntimeException("operation unsupported");
	}


} catch(Exception $e){
	if( $db->pdo()->inTransaction() ){
		$db->pdo()->rollBack();
	}
	http_response_code(400);
	print(returnError($e->getMessage()));
	exit;
}

print(returnOK($retmsg));
exit;
?>
