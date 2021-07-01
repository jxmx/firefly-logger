<?php
header('Content-Type: application/octet-stream');
header('Content-disposition: inline; filename=fieldday.log');
//header('Content-Type: text/plain');

function AL($tag, $val){
	return sprintf("<%s:%d>%s\n",	$tag, strlen($val), $val);
}

$band = array(
	"160M" => "1800",
	"80M" => "3500",
	"40M" => "7000",
	"20M" => "14000",
	"15M" => "21000",
	"10M" => "28000",
	"6M" => "50",
	"2M" => "144"
);

include("api/db.php");

print "START-OF-LOG: 3.0\n";
print "CONTEST: ARRL-FD\n";
print "CATEGORY-BAND: ALL\n";
print "CATEGORY-MODE: MIXED\n";
print "CATEGORY-OPERATOR: MULTI-OP\n";
print "CATEGORY-POWER: LOW\n";
print "CATEGORY-STATION: 5A\n";
print "CREATED-BY: Firefly Field Day Logger\n";
print "LOCATION: OH\n";
print "\n";

$qry = "SELECT * FROM qso ORDER BY date ASC";

if($res = $db->query($qry)){
	while( $row = $res->fetch_assoc()){

		$dt = explode(" ", $row['date']);

		if( $row['mode'] == "PHONE" ){
			$m = "PH";
		} else if($row ['mode'] == "DATA" ){
			$m = "DG";
		} else {
			$m = "CW";
		}

		// QSO: ***** ** yyyy-mm-dd nnnn ************* nnn ****** ************* nnn ****** n
		
		printf("QSO: %5s %2s %10s %4s %13s %3s %6s %13s %3s %6s 0\n",
			$band[strtoupper($row['band'])],
			$m,
			strtoupper($dt[0]),
			preg_replace('/:/', '', $dt[1]),
			strtoupper($row['station']),
			"5A",
			"OH",
			strtoupper($row['callsign']),
			strtoupper($row['class']),
			strtoupper($row['section'])
		);
	}

	print "END-OF-LOG:\n";
} else {
	print "DB ERROR";
}
$db->close();

?>
