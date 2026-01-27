<?php

// This is the page title in <head>>. It's followed by "| Firefly"
$ff_page_title = "Config Manager";

// This is centered content in the header
require_once("station-info-form.php");
$ff_header_content = $ff_station_info_form;

$ff_additional_scripts = <<<EOT
	<script src="js/jquery.validate-1.22.0.min.js"></script>
	<script src="js/statusmsg.js"></script>
	<script src="js/logdisplay.js"></script>
	<script src="js/index.js"></script>
EOT;

include("header.php");
?>
<main data-logclock-mode="manual">
<div class="container-md qso-entry-container shadow rounded-3">
		<div class="row mt-3 justify-content-center">
			<div class="col-12 col-lg-10">
				<form id="log" name="log" class="needs-validation w-100" novalidate>

					<div class="row mt-2 g-3 align-items-top justify-content-center">

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
							<div class="invalid-feedback d-block opacity-0">placeholder</div>
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
							<div class="invalid-feedback d-block opacity-0">placeholder</div>
						</div>
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
</main>

<?php include("footer.php"); ?>