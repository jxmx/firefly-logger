<?php
header('Content-Type: application/octet-stream');
header('Content-disposition: inline; filename=fieldday.csv');
//header('Content-Type: text/plain');

include("api/db.php");

$adif = array();

print("Qkey,Callsign,Date,Band,Mode,Class,Section,Station,Operator\n");

$qry = "SELECT * FROM qso ORDER BY date ASC";

if($res = $db->query($qry)){
	while( $row = $res->fetch_assoc()){
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
$db->close();

?>
