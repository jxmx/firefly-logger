<?php
header('Content-Type: application/octet-stream');
header('Content-disposition: inline; filename=fieldday.adi');
//header('Content-Type: text/plain');

function getPostVar($id) {
	return filter_var(trim($_POST[$id]), FILTER_SANITIZE_STRING);
}

function AL($tag, $val){
	return sprintf("<%s:%d>%s\n",	$tag, strlen($val), $val);
}

$is_lsb = array("160M", "80M", "40M");
$is_fm = array("2M", "70CM");

include("api/db.php");

$adif = array();
foreach($_POST as $k => $v){
	$adif[$k] = getPostVar($k);
}

$adifcomment = $adif['adifcomment'];
$adifcontest = $adif['adifcontest'];

if( $adifcontest == "WFD"){
	$precontest = "Winter Field Day";
} else {
	$precontest = "ARRL Field Day";
}

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

		if( strtoupper($row['mode']) == "PHONE" ){
			if( in_array(strtoupper($row['band']), $is_lsb)){
				print AL("MODE", "LSB");
			} else if( in_array(strtoupper($row['band']), $is_fm)) {
				print AL("MODE", "FM");
			} else {
				print AL("MODE", "USB");
			}
		} else {
			print AL("MODE", strtoupper($row['mode']));			// see eumeration
		}
		print AL("STATION_CALLSIGN", strtoupper($row['station']));
		print AL("OPERATOR", strtoupper($row['operator']));
		print AL("ARRL_SECT", strtoupper($row['section']));
		print AL("CLASS", strtoupper($row['class']));
		print AL("COMMENT", $precontest . " - " . $adifcomment);
		print "<EOR>\n\n";
	}
} else {
	print "DB ERROR";
}
$db->close();

?>
