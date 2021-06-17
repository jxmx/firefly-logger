<?php

function getGetVar($id) {
    return filter_var(trim($_GET[$id]), FILTER_SANITIZE_STRING);
}

$hidebuttons = getGetVar("hidebuttons");


include("db.php");

$insqry = "select * from ( select * from qso order by date desc limit 10 ) sub order by date desc;";
if($res = $db->query($insqry)){
	while( $row = $res->fetch_assoc()){
		print "<tr>\n";
		printf("<td scope=\"row\" class=\"align-middle\">%s</td>\n", $row["date"]);
		printf("<td class=\"align-middle\">%s</td>\n", $row["callsign"]);
		printf("<td class=\"align-middle\">%s</td>\n", $row["class"]);;
		printf("<td class=\"align-middle\">%s</td>\n", $row["section"]);
		printf("<td class=\"align-middle\">%s</td>\n", $row["band"]);
		printf("<td class=\"align-middle\">%s</td>\n", $row["mode"]);
		printf("<td class=\"align-middle\">%s</td>\n", $row["operator"]);
		print "<td class=\"align-middle\">\n";
		
		if( $hidebuttons == "y" ){
			print "&nbsp;";

		} else {
			print "<div cass=\"btn-group\" role=\"group\">\n";
			printf("<button type=\"button\" class=\"btn btn-primary btn-warning btn-sm\" onclick=\"editQSO('%d')\">Edit</button>\n", 
				$row["qkey"]);
			printf("<button type=\"button\" class=\"btn btn-primary btn-danger btn-sm\" onclick=\"delQSO('%d','%s')\">Del</button>\n", 
			$row["qkey"], $row["callsign"]);
			print "</div>\n";
		}
		print "</td>\n";
		print "</tr>\n";

	}
} else {
	print "<tr><td colspan=\"7\">No QSOs! Welcome to Field Day! Get Going!</td></tr>";
}

$db->close();
?>
