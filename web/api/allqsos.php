<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache');
header('X-Accel-Buffering: no');
include_once(__DIR__ . "/functions.php");

try{
	$qry = "SELECT * FROM qso ORDER BY date DESC";
	$stmt = $db->pdo()->query($qry);
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if( count($rows) > 0 ){
        print json_encode($rows);
	} else {
		print json_encode([]);
	}

} catch(Exception $e){
	http_response_code(400);
	print(returnError($e->getMessage()));
	exit;
}
exit;
?>