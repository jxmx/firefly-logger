<?php
header('Content-Type: application/octet-stream');
header('Content-disposition: inline; filename=fieldday.csv');
//header('Content-Type: text/plain');

include_once(__DIR__ . "/../api/functions.php");

$adif = array();

print("Qkey,Callsign,Date,Band,Mode,Class,Section,Station,Operator\n");

try{
	$qry = "SELECT * FROM qso ORDER BY date ASC";
	$stmt = $db->pdo()->query($qry);
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if( count($rows) > 0 ){
		foreach( $rows as $row){
			printf("%s,%s,%s,%s,%s,%s,%s,%s,%s\n",
				$row['qkey'],
				$row['callsign'],
				$row['date'],
				$row['band'],
				$row['mode'],
				$row['class'],
				strtoupper($row['section']),
				$row['station'],
				$row['operator'],
			);
		}
	} else {
		print "DB ERROR";
	}
} catch (Exception $e){
	print($e->getMessage());
}
exit;
?>
