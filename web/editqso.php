<?php


include_once("api/functions.php");
include("api/db.php");

$qkey = getGetVar("qkey");

$qry = sprintf("SELECT * FROM qso WHERE qkey='%s';", $qkey);

?>

<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Edit QSO - Firefly Field Day Logger</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/local.css" rel="stylesheet">
	<link rel="shortcut icon" href="img/firefly.svg" sizes="any" type="image/svg+xml">
	
</head>
<body>
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
  <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.9
7 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
  </symbol>
  <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.3
46-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0
1 1 0-2 1 1 0 0 1 0 2z"/>
  </symbol>
  <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.5
66zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
  </symbol>
</svg>
<main>
	<div class="titlebars" style="height: 75px;">
		<table width="100%" height="100%">
			<tr class="titlebars">
				<td width="20%" style="vertical-align: middle; text-align: center;">
					<img src="img/firefly.svg" height="45px"><span class="px-2">Firefly Logger</span>
				</td>
				<td width="60%" style="vertical-align: middle; text-align: center;">
					<span class="fs-1 fw-bold"><b>Edit QSO</b></span>
				</td>
				<td width="20%" style="vertical-align: middle; text-align: center;">
					<img src="img/firefly.svg" height="45px"><span class="px-2">Firefly Logger</span>
				</td>
			</tr>
		</table>
	</div>
	<div class="container-md qso-entry-container shadow">
		<div class="row">
			<div class="alert alert-warning"><b>NOTE:</b> There is NO field validation in this form. Edit carefully!</div>
		</div>
		<div class="row">
		<form id="editqso" name="editqso">
			<table class="table">
				<tr>
					<th><b>Field</b></th>
					<th><b>Value</b></th>
				</tr>
<?php
if($res = $db->query($qry)){
	while( $row = $res->fetch_assoc()){
		foreach($row as $key => $val){
	
			print "<tr>\n";
			printf("<td><b>%s</b></td>\n", $key);
			if( $key == "qkey" ){
				printf("<td><input id=\"%s\" name=\"%s\" type=\"hidden\" value=\"%s\">%s</td>\n", $key, $key, $val, $val);
			} else {
				printf("<td><input id=\"%s\" name=\"%s\" type=\"test\" size=\"15\" class=\"form-control\" " .
					"autocomplete=\"off\" value=\"%s\"></td>\n", $key, $key, $val);

			}
			print "</tr>\n";

		}
	}
	print "<tr><td colspan=\"2\">\n";
	print "<button id=\"editsub\" name=\"editsub\" type=\"button\" class=\"btn btn-danger mx-2\" onclick=\"submitEdit()\">Save</button>";
	print "<button id=\"abandonedit\" name=\"abandonedit\" type=\"button\" class=\"btn btn-misc mx-2\" onclick=\"abandonEdit()\">Abandon</button>";
	print "</td></tr>\n";

} else {
	printf("<tr><td colspan=\"2\"><div class=\"alert alert-danger\">No such QSO ID %s</div></td></tr>", $qkey);
}
$db->close();
?>
		<tr>
			<td colspan="2">
				<div id="d-flex">
    	            <div id="statusarea" class="invisible alert d-flex align-items-center alert-dismissable fade show" 
						role="alert"></div>
	            </div>
			</td>
		</tr>

		</form>
		</div>
	</div>
	<script src="js/bootstrap.bundle.min.js"></script>
	<script src="js/jquery-3.6.0.min.js"></script>
	<script src="js/statusmsg.js"></script>
	<script src="js/editqso.js"></script>
</main>
</body>
</html>
