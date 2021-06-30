<?php
//header('Content-Type: application/octet-stream');
//header('Content-disposition: inline; filename=fieldday.adx');
header('Content-Type: text/plain');

function AL($tag, $val){
	return sprintf("<%s:%d>%s\n",	$tag, strlen($val), $val);
}

include("api/db.php");

print AL("adif_ver", "3.1.2");
print AL("programid", "Firefly Field Day Logger");
print "<EOH>\n\n";

$qry = "SELECT * FROM qso ORDER BY date ASC";

if($res = $db->query($qry)){
	while( $row = $res->fetch_assoc()){
		$dt = explode(" ", $row['date']);
		print AL("QSO_DATE", preg_replace('/\-/', '', $dt[0]));
		print AL("TIME_ON", preg_replace('/:/', '', $dt[1]));
		print AL("CALL", strtoupper($row['callsign']));
		print AL("BAND", strtoupper($row['band'])); 			// see eumeration
		print AL("MODE", strtoupper($row['mode']));			// see eumeration
		print AL("STATION_CALLSIGN", strtoupper($row['station']));
		print AL("OPERATOR", strtoupper($row['operator']));
		print AL("ARRL_SECT", strtoupper($row['section']));
		print AL("CLASS", strtoupper($row['class']));
		print AL("COMMENT", 
			"ARRL Field Day 2021 - " . strtoupper($row['class']) . " " . strtoupper($row['section']),
			3);
		print "<EOR>\n\n";
	}
} else {
	print "DB ERROR";
}
$db->close();

?>
