<?php

// This is the page title in <head>>. It's followed by "| Firefly"
$ff_page_title = "CSV Export";

// This is centered content in the header
$ff_header_content = <<<EOT
	<h2>All QSOs</h2>
	EOT;

$ff_additional_scripts = <<<EOT
<script src="js/jquery.validate-1.22.0.min.js"></script>
<script src="js/bootstrap-table-1.26.0.min.js"></script>
<script src="js/allqsos.js"></script>
EOT;

$ff_additional_css = <<<EOT
<link rel="stylesheet" href="css/bootstrap-table-1.26.0.min.css">
EOT;

include("header.php");
?>
<main>
	<div class="container-md mt-5 shadow rounded-3">
		<div class="row justify-content-center">
			<div class="col table-responsive-md">
				<!-- Bootstrap Tables -->
				<table id="table" class="table"
					data-toggle="table"
					data-pagination="true"
					data-search="true"
					data-search-highlight="true"
					data-search-on-enter-key="true"
					data-show-search-button="true"
					data-show-search-clear-button="true"
					data-url="api/allqsos.php"
					class="table table-sm asl-users-table">
					<thead>
					<tr>
						<th data-field="date">Date/Time</th>
						<th data-field="callsign">Callsign</th>
						<th data-field="band">Band</th>
						<th data-field="class">Class</th>
						<th data-field="section">Section</th>
						<th data-field="mode">Mode</th>
						<th data-field="operator">Operator</th>
						<th data-field="qkey" data-formatter="qsoButtons" class="text-center"></th>
					</tr>
					</thead>
				</table>
		    </div>
  		</div>
	</div>
</main>
<div class="modal fade" id="errorModal"
	data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
	aria-labelledby="errorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger" id="errorModalLabel">Backend Data Service Error</h5>
      </div>
      <div class="modal-body">
        <p class="text-danger">An unexpected error occurred. Information is not updating. Check server and reload.</p>
		<div id="modal-ajax-error"></div>
		<div id="modal-xhr-error"></div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<?php include("footer.php"); ?>
