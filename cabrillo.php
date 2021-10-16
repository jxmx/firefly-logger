<?php
//header('Content-Type: application/octet-stream');
//header('Content-disposition: inline; filename=fieldday.log');
header('Content-Type: text/plain');

function getPostVar($id) {
	return filter_var(trim($_POST[$id]), FILTER_SANITIZE_STRING);
}

function AL($tag, $val){
	return sprintf("<%s:%d>%s\n",	$tag, strlen($val), $val);
}


$cab = array();
foreach($_POST as $k => $v){
	$cab[$k] = getPostVar($k);
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
printf("CLUB: %s\n", $cab['cabclub']);
printf("CALLSIGN: %s\n", $cab['cabcall']);
printf("LOCATION: %s\n", $cab['cabsection']);
printf("CATEGORY-OPERATOR: %s\n", $cab['cabop']);
printf("CATEGORY-STATION: %s\n", $cab['cabstation']);
printf("CATEGORY-TRANSMITTER: %s\n", $cab['cabtransmitter']);
printf("CLAIMED-SCORE: %s\n", $cab['cabscore']);
printf("NAME: %s\n", $cab['cabname']);
printf("ADDRESS: %s\n", $cab['cabstreet']);
printf("ADDRESS-CITY: %s\n", $cab['cabcity']);
printf("ADDRESS-STATE-PROVINCE: %s\n", $cab['cabstate']);
printf("ADDRESS-POSTALCODE: %s\n", $cab['cabzip']);
printf("ADDRESS-COUNTRY: %s\n", $cab['cabcountry']);
printf("EMAIL: %s\n", $cab['cabemail']);
print "CREATED-BY: Firefly Field Day Logger\n";
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
			$cab['cabtransmitter']. strtoupper($cab['cabstation']),
			strtoupper($cab['cabsection']),
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
