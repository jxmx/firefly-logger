<?php
header('Content-Type: application/octet-stream');
header('Content-disposition: inline; filename=fd-summary.txt');
header('Content-Type: text/plain');

include_once(__DIR__ . "/../api/functions.php");

$dupe = array();
foreach($_POST as $k => $v){
	$dupe[$k] = getPostVar($k);
}

try{

	printf("%s - Summary Sheet Table\n", $dupe['dupecontest']);
	printf("Generated: %s\n", date("c"));
	print("--------------------------------------------------------\n");
	printf("%6s %15s %15s %15s\n", "Band", "CW", "Data", "Phone");
	print("--------------------------------------------------------\n");

	$qry = <<<EOT
		SELECT DISTINCT(band) FROM qso ORDER BY CASE band
		WHEN '160M' THEN 1 WHEN '80M' THEN 2 WHEN '40M' THEN 3
		WHEN '20M' THEN 4 WHEN '15M' THEN 5 WHEN '10M' THEN 6
		WHEN '6M' THEN 7 WHEN '2M' THEN 8 WHEN '1.25M' THEN 9 WHEN
		'70CM' THEN 10 ELSE 20 END
		EOT;

	$stmt = $db->pdo()->query($qry);
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if( count($rows) > 0 ){
		foreach( $rows as $band){
			printf("%6s ", $band['band']);
			$modes = array("CW", "DATA", "PHONE");
			foreach($modes as &$mode){
				$qry = "SELECT COUNT(qkey) FROM qso WHERE band=? AND mode=?";
				$qry_params = [ $band['band'] , $mode ];
				$stmt = $db->pdo()->prepare($qry);
				$stmt->execute($qry_params);
				$count = $stmt->fetchColumn();
				printf("%15s ", $count);
			}
			print "\n";
		}
	} else {
		print "DB ERROR";
	}

	print("--------------------------------------------------------\n");
	printf("Comment: %s\n", $dupe['dupecomment']);

} catch (Exception $e){
	print($e->getMessage());
}
exit;
?>
