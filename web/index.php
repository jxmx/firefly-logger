<?php

// This is the page title in <head>>. It's followed by "| Firefly"
$ff_page_title = "Log Entry";

// This is centered content in the header
require_once("station-info-form.php");
$ff_header_content = $ff_station_info_form;

$ff_additional_scripts = <<<EOT
	<script src="js/jquery.validate-1.22.0.min.js"></script>
	<script src="js/statusmsg.js"></script>
	<script src="js/logdisplay.js"></script>
	<script src="js/index.js"></script>
EOT;

//$ff_additional_css = <<<EOT
//EOT;

include("header.php");
?>
<main data-logclock-mode="auto">
	<div class="container-md qso-entry-container shadow rounded-3">
		<div class="row mt-3">
			<div class="col-12 d-flex justify-content-center">
				<form id="log" name="log" class="needs-validation w-100" novalidate>

					<div class="row mt-2 g-3 align-items-start">

						<!-- Date/Time -->
						<div class="col-12 col-md-2">
							<label for="logclock" class="form-label">Date/Time</label>
							<input type="text"
								id="logclock"
								name="logclock"
								class="form-control"
								tabindex="-1"
								autocomplete="off">
						</div>

						<!-- Callsign -->
						<div class="col-12 col-md-2">
							<label for="call" class="form-label">Callsign</label>
							<input type="text"
								id="call"
								name="call"
								class="form-control"
								autocomplete="off"
								onkeyup="this.value = this.value.toUpperCase();">
						</div>

						<!-- Class -->
						<div class="col-12 col-md-2">
							<label for="opclass" class="form-label">Class</label>
							<input type="text"
								id="opclass"
								name="opclass"
								class="form-control"
								autocomplete="off"
								onkeyup="this.value = this.value.toUpperCase();">
						</div>

						<!-- Section -->
						<div class="col-12 col-md-2">
							<label for="section" class="form-label">Section</label>
							<div id="arrl-sections" class="position-relative">
								<input type="text"
									id="section"
									name="section"
									class="form-control"
									autocomplete="off">
								<div id="section-hint" class="hint-overlay"></div>
							</div>
						</div>

						<!-- Log Button -->
						<div class="col-6 col-md-1 d-grid">
							<label class="form-label opacity-0">Log</label>
							<button type="submit"
									id="logsubmit"
									name="logsubmit"
									class="btn btn-primary">
								Log
							</button>
						</div>

						<!-- Clear Button -->
						<div class="col-6 col-md-1 d-grid">
							<label class="form-label opacity-0">Clear</label>
							<button type="button"
									id="logclear"
									name="logclear"
									class="btn btn-secondary"
									onclick="logReset()"
									tabindex="-1">
								Clear
							</button>
						</div>

						<!-- Future expansion -->
						<div class="col-12 col-md-2"></div>

					</div>

					<!-- Hidden fields -->
					<input type="hidden" id="opcallsign" name="opcallsign">
					<input type="hidden" id="opoperator" name="opoperator">
					<input type="hidden" id="opband" name="opband">
					<input type="hidden" id="opmode" name="opmode">
					<input type="hidden" id="qkey" name="qkey">

				</form>
			</div>
		</div>
		<div class="row">
			<div id="statusarea"
				class="alert d-flex align-items-center fade show opacity-0 m-3"
				role="alert"></div>
		</div>
	</div>
	<div class="container-md shadow rounded-3 log-disp-container mb-3">
		<div class="row">
			<div class="col">
				<div class="d-flex titlebars  rounded-3 p-2"><b>Last 10 QSOs</b></div>
				<table class="table table-hover">
					<thead>
						<tr>
							<th scope="col">Date/Time</th>
							<th scope="col">Call</th>
							<th scope="col">Class</th>
							<th scope="col">Section</th>
							<th scope="col">Band</th>
							<th scope="col">Mode</th>
							<th scope="col">Oper</th>
							<th scope="col"></th>
						</tr>
					</thead>
					<tbody id="logdisplay" name="logdisplay">
					</tbody>
				</table>
				<div class="d-flex p-2"><i>Log updates every 15 seconds with last ten QSOs</i></div>
			</div>
		</div>
	</div>
</main>
<?php require_once("footer.php"); ?>
