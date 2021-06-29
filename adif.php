<?php
header('Content-Type: application/octet-stream');
header('Content-disposition: inline; filename=fieldday.adx');

function xmlLine($tag, $val, $indent){

	$tabs = "";
	for($i = 0; $i <= $indent; $i++){
		$tabs .= "\t";
	}
	return sprintf("%s<%s>%s</%s>\n", $tabs, $tag, $val, $tag);
}

include("api/db.php");

print "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
print "<ADX>\n";
print "\t<HEADER>\n";
print xmlLine("ADIF_VER", "3.1.2", 2);
print xmlLine("PROGRAMID", "Firefly Field Day Logger", 2);
print xmlLine("PROGRAMVERSION", "1", 2);
print "\t</HEADER>\n";
print "\n";
print "\t<RECORDS>\n";

$qry = "SELECT * FROM qso ORDER BY date ASC";

if($res = $db->query($qry)){
	while( $row = $res->fetch_assoc()){
		$dt = explode(" ", $row['date']);
		print "\t\t<RECORD>\n";
		print xmlLine("QSO_DATE", $dt[0], 3);
		print xmlLine("TIME_ON", preg_replace('/:/', '', $dt[1]), 3);
		print xmlLine("CALL", strtoupper($row['callsign']), 3);
		print xmlLine("BAND", strtoupper($row['band']), 3); 			// see eumeration
		print xmlLine("MODE", strtoupper($row['mode']), 3);			// see eumeration
		print xmlLine("STATION_CALLSIGN", strtoupper($row['station']), 3);
		print xmlLine("OPERATOR", strtoupper($row['operator']), 3);
		print xmlLine("ARRL_SECT", strtoupper($row['section']), 3);
		print xmlLine("CLASS", strtoupper($row['class']), 3);
		print xmlLine("COMMENT", 
			"ARRL Field Day 2021 - " . strtoupper($row['class']) . " " . strtoupper($row['section']),
			3);
		print "\t\t</RECORD>\n";			
	}
} else {
	print "DB ERROR";
}
$db->close();

print "\t</RECORDS>\n";
print "</ADX>\n";
?>
