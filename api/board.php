<?php
header('Content-Type: text/plain');

function getGetVar($id) {
	return filter_var(trim($_GET[$id]), FILTER_SANITIZE_STRING);
}

$q = getGetVar("q");

include("db.php");

switch ($q){
	case "total":
		$qry = "SELECT COUNT(qkey) FROM qso";
		break;
	case "phone":
		$qry = "SELECT COUNT(qkey) FROM qso WHERE mode='PHONE'";
		break;

	case "cw":
		$qry = "SELECT COUNT(qkey) FROM qso WHERE mode='CW'";
		break;

	case "data":
		$qry = "SELECT COUNT(qkey) FROM qso WHERE mode='DATA'";
		break;

	case "distinct":
		$qry = "SELECT COUNT(DISTINCT(callsign)) FROM qso";
		break;

	case "sections":
		$qry = "SELECT COUNT(DISTINCT(section)) FROM qso";
		break;

	// print totals by default
	default:
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ||
			 $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$myurl = sprintf("%s%s%s?q=", $protocol, $_SERVER['SERVER_NAME'], $_SERVER['SCRIPT_NAME']);
		$qsop  = file_get_contents($myurl . "phone");
		$qsoc  = file_get_contents($myurl . "cw");
		$qsod  = file_get_contents($myurl . "data");
		printf("%d", $qsop + ( $qsoc * 2 ) + ( $qsod * 2 ));
		exit;

}
if($res = $db->query($qry)){
	$row = $res->fetch_row();
	print $row[0];
} else {
	print "DB ERROR";
}
$db->close();
?>
