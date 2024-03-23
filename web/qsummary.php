<?php
header('Content-Type: application/octet-stream');
header('Content-disposition: inline; filename=fd-dupesheet.txt');
//header('Content-Type: text/plain');

include_once("api/functions.php");

$dupe = array();
foreach($_POST as $k => $v){
	$dupe[$k] = getPostVar($k);
}

include("api/db.php");


$qry = "SELECT DISTINCT(band) FROM qso ORDER BY CASE band
	WHEN '160M' THEN 1 WHEN '80M' THEN 2 WHEN '40M' THEN 3 
	WHEN '20M' THEN 4 WHEN '15M' THEN 5 WHEN '10M' THEN 6 
	WHEN '6M' THEN 7 WHEN '2M' THEN 8 WHEN '70CM' THEN 9 ELSE 20 END";

if($resb = $db->query($qry)){

	printf("%s - Summary Sheet Table\n", $dupe['dupecontest']);
	printf("Generated: %s\n", date("c"));
	print("--------------------------------------------------------\n");
	printf("%6s %15s %15s %15s\n", "Band", "CW", "Data", "Phone");
	print("--------------------------------------------------------\n");
	
	
	while( $bands = $resb->fetch_assoc()){
		printf("%6s ", $bands['band']);
		$modes = array("CW", "DATA", "PHONE");
		foreach($modes as &$mode){
			$qry = sprintf("SELECT COUNT(qkey) FROM qso WHERE band='%s' AND mode='%s'", $bands['band'], $mode);
			$resc = $db->query($qry);
			$count = $resc->fetch_assoc();
			printf("%15s ", $count["COUNT(qkey)"]);
		}
		print "\n";
	}		
} else {
	print "DB ERROR";
}
$db->close();

print("--------------------------------------------------------\n");
printf("Comment: %s\n", $dupe['dupecomment']);
?>
