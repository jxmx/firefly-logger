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

$ff_additional_scripts = <<<EOT
	<script src="js/jquery.validate-1.22.0.min.js"></script>
	<script src="js/statusmsg.js"></script>
	<script src="js/editqso.js"></script>
EOT;

include("header.php");
?>
<main>
    <div class="container qso-entry-container shadow rounded-3 p-4 my-3">

        <form id="editqso" name="editqso" class="row g-3">
<?php

if($res = $db->query($qry)){
	while( $row = $res->fetch_assoc()){
		foreach($row as $key => $val){
			if( $key == "qkey" ){
				print <<<EOT
					<input type="hidden" id="oldqkey" name="oldqkey" value="{$qkey}">
					<input type="hidden" id="newqkey" name="newqkey" value="{$qkey}">
				EOT;
			} else {
				print <<<EOT
					<!-- {$key} -->
					<div class="col-md-6">
						<label for="{$key}" class="form-label fw-bold">{$key}</label>
						<input id="{$key}" name="{$key}" type="text" class="form-control"
							autocomplete="off" value="{$val}">
					</div>
				EOT;
			}
		}
	}
?>
            <!-- Buttons -->
            <div class="col-12 mt-3">
                <button id="editsub" name="editsub" type="submit"
                        class="btn btn-danger me-2">Save</button>

                <button id="abandonedit" name="abandonedit" type="button"
                        class="btn btn-misc" onclick="abandonEdit()">Abandon</button>
            </div>

            <!-- Status area -->
            <div class="col-12">
                <div class="d-flex">
                    <div id="statusarea"
                         class="invisible alert d-flex align-items-center alert-dismissable fade show"
                         role="alert"></div>
                </div>
            </div>
<?php
} else {
	printf("<tr><td colspan=\"2\"><div class=\"alert alert-danger\">No such QSO ID %s</div></td></tr>", $qkey);
}
$db->close();
?>
        </form>
    </div>
</main>
<?php include("footer.php"); ?>