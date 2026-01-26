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
<main>
	<div class="container-md qso-entry-container shadow rounded-3">
		<div class="row">
			<div class="col-12 justify-content-center">
			<form id="log" name="log" role="form" class="needs-validation" novalidate>
				<table class="table table-borderless" width="100%">
					<thead>
						<th scope="col" style="width: 20%">Date/Time</th>
						<th scope="col" style="width: 15%">Callsign</th>
						<th scope="col" style="width: 15%">Class</th>
						<th scope="col" style="width: 10%">Section</th>
						<th scope="col" style="width: 10%">&nbsp;</th>
						<th scope="col" style="width: 10%">&nbsp;</th>
						<th scope="col" style="width: 30%"></th>
					</thead>
					<tbody>
						<td class="align-top">
							<input type="text" size=15 name="logclock" id="logclock" class="form-control"
								tabindex="-1" autocomplete="off">
						</td>
						<td class="align-top">
							<input id="call" name="call" type="text" size="10" class="form-control"
								onkeyup="this.value = this.value.toUpperCase();" autocomplete="off">
						</td>
						<td class="align-top">
							<input id="opclass" name="opclass" type="text" size="10" class="form-control"
									onkeyup="this.value = this.value.toUpperCase();" autocomplete="off">
						</td>
						<td class="align-top">
							<div id="arrl-sections" class="position-relative">
							<input id="section" name="section" class="form-control" type="text"
									size="10" autocomplete="off">
							<div id="section-hint" class="hint-overlay"></div>
							</div>
						</td>
						<td class="align-top text-center">
							<button id="logsubmit" name="logsubmit"
								type="submit" class="btn btn-primary">Log</button>
						</td>
						<td class="align-top text-center">
							<button id="logclear" name="logclear" type="button" class="btn btn-misc"
								onclick="logReset()" tabindex="-1">Clear</button>
						</td>
						<td><!-- future --> </td>
				</tbody>
				</table>
				<input type="hidden" id="opcallsign" name="opcallsign" value="">
				<input type="hidden" id="opoperator" name="opoperator" value="">
				<input type="hidden" id="opband" name="opband" value="">
				<input type="hidden" id="opmode" name="opmode" value="">
				<input type="hidden" id="qkey" name="qkey" value="">
			</form>
			</div>
		</div>
		<div class="row">
			<div id="d-flex">
				<div id="statusarea" class="invisible alert d-flex align-items-center alert-dismissable fade show" role="alert"></div>
			</div>
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
