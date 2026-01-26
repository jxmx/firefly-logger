<?php
header('Content-Type: application/json');
include_once("functions.php");

$qkey = getGetVar("qkey");

$qry = "SELECT COUNT(qkey) FROM qso WHERE qkey=?";
$qry_params = [ $qkey ];

try {
    $stmt = $db->pdo()->prepare($qry);
    $stmt->execute($qry_params);
    $count = $stmt->fetchColumn();

    // Note: these return the opposite of what you might expect
    // because this is fed into jQuery Validate which is expecting
    // "true" if the form can proceed and "false" if it cannot
    if($count > 0){
        print("false");
    } else {
        print("true");
    }
} catch(Exception $e){
    http_response_code(400);
	print(returnError($e->getMessage()));
	exit;
}
exit;