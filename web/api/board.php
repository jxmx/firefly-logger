<?php
header('Content-Type: application/json');
include_once("functions.php");

function getElement($db, $qry){
	try{
		$qry_params = [];
		$stmt = $db->pdo()->prepare($qry);
		$stmt->execute($qry_params);
		return $stmt->fetchColumn();
		$count;
	} catch(Exception $e){
		http_response_code(400);
		return returnError($e->getMessage());
		exit;
	}
}

$q = getGetVar("q");

switch ($q){
	case "total":
		print returnOK(
			getElement($db, "SELECT COUNT(qkey) FROM qso")
		);
		break;
	case "phone":
		print returnOK(
			getElement($db, "SELECT COUNT(qkey) FROM qso WHERE mode='PHONE'")
		);
		break;

	case "cw":
		print returnOK(
			getElement($db, "SELECT COUNT(qkey) FROM qso WHERE mode='CW'")
		);
		break;

	case "data":
		print returnOK(
			getElement($db, "SELECT COUNT(qkey) FROM qso WHERE mode='DATA'")
		);
		break;

	case "distinct":
		print returnOK(
			getElement($db, "SELECT COUNT(DISTINCT(callsign)) FROM qso")
		);
		break;

	case "sections":
		print returnOK(
			getElement($db, "SELECT COUNT(DISTINCT(section)) FROM qso")
		);
		break;

	// print totals by default
	default:
		print(
			returnOK(
				sprintf("%d",
					getElement($db, "SELECT COUNT(qkey) FROM qso WHERE mode='PHONE'") +
					getElement($db, "SELECT COUNT(qkey) FROM qso WHERE mode='CW'") * 2 +
					getElement($db, "SELECT COUNT(qkey) FROM qso WHERE mode='DATA'") * 2
				)
			)
		);
		exit;
}
?>
