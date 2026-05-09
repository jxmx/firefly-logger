<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache');
header('X-Accel-Buffering: no');
include_once(__DIR__ . "/functions.php");
try {
    $qry = "SELECT DISTINCT UPPER(section) AS s FROM qso ORDER BY section ASC";
    $stmt = $db->pdo()->query($qry);
    $rows = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    print json_encode($rows ?: []);
} catch (Exception $e) {
    http_response_code(400);
    print(returnError($e->getMessage()));
    exit;
}
exit;
?>