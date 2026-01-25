<?php
include_once("api/functions.php");
include("api/db.php");

$qkey = getGetVar("qkey");
$qry = sprintf("SELECT * FROM qso WHERE qkey='%s';", $qkey);

// This is the page title in <head>>. It's followed by "| Firefly"
$ff_page_title = "Edit QSO";

// This is centered content in the header
$ff_header_content = <<<EOT
	<h2>Edit QSO</h2>
	EOT;

include("header.php");
?>
<main>
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
</main>
<?php include("footer.php"); ?>