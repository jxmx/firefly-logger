<?php
header('Content-Type: text/plain');
include_once("functions.php");

function getElement($qq){
	include("db.php");
	if($res = $db->query($qq)){
		$row = $res->fetch_row();
		return $row[0];
	} else {
		return "DB ERROR";
	}
	$db->close();
}

$q = getGetVar("q");

switch ($q){
	case "total":
		print getElement("SELECT COUNT(qkey) FROM qso");
		break;
	case "phone":
		print getElement("SELECT COUNT(qkey) FROM qso WHERE mode='PHONE'");
		break;

	case "cw":
		print getElement("SELECT COUNT(qkey) FROM qso WHERE mode='CW'");
		break;

	case "data":
		print getElement("SELECT COUNT(qkey) FROM qso WHERE mode='DATA'");
		break;

	case "distinct":
		print getElement("SELECT COUNT(DISTINCT(callsign)) FROM qso");
		break;

	case "sections":
		print getElement("SELECT COUNT(DISTINCT(section)) FROM qso");
		break;

	// print totals by default
	default:
		printf("%d", 
			getElement("SELECT COUNT(qkey) FROM qso WHERE mode='PHONE'") + 
			( getElement("SELECT COUNT(qkey) FROM qso WHERE mode='CW'") * 2 ) + 
			( getElement("SELECT COUNT(qkey) FROM qso WHERE mode='DATA'") * 2 )
		);
		exit;

}


?>
