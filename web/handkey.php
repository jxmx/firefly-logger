<?php

// This is the page title in <head>>. It's followed by "| Firefly"
$ff_page_title = "Config Manager";

// This is centered content in the header
require_once("station-info-form.php");
$ff_header_content = $ff_station_info_form;

include("header.php");
?>
  <main>
	<div class="container-md qso-entry-container shadow">
		<div class="row">
			<form id="log" name="log">
				<table class="table table-borderless">
					<thead>
						<th scope="col" style="width: 15%">Date<br><i>YYYY-MM-DD</i></th>
						<th scope="col" style="width: 10%">Time<br><i>HHMM</i></th>
						<th scope="col" style="width: 15%">Callsign</th>
						<th scope="col" style="width: 10%">Class</th>
						<th scope="col" style="width: 10%">Section</th>
						<th scope="col" style="width: 10%">&nbsp;</th>
						<th scope="col" style="width: 10%">&nbsp;</th>
						<th scope="col" style="width: 15%"></th>


					</thead>
					<tbody>
						<td class="align-top">
							<input type="text" size=12 name="logclockdate" id="logclockdate" class="form-control"
								tabindex="-1" autocomplete="off">
						</td>
						<td class="align-top">
							<input type="text" size=5 name="logclocktime" id="logclocktime" class="form-control"
								autocomplete="off">
						</td>
						<td class="align-top">
							<input id="call" name="call" type="text" size="10" class="form-control"
								onkeyup="this.value = this.value.toUpperCase();" onblur="isDupeQSO()" autocomplete="off">
						</td>
						<td class="align-top">
							<input id="opclass" name="opclass" type="text" size="10" class="form-control"
									onkeyup="this.value = this.value.toUpperCase();" autocomplete="off">
						</td>
						<td class="align-top">
							<div id="arrl-sections">
							<input id="section" name="section" class="typeahead form-control" type="text"
									size="10" autocomplete="off"  onkeyup="this.value = this.value.toUpperCase();">
							</div>
						</td>
						<td class="align-top" align="center">
							<button id="logsubmit" name="logsubmit" type="button" class="btn btn-danger"
								onclick="logSubmit()">Log</button>
						</td>
						<td class="align-top" align="left">
							<button id="logclear" name="logclear" type="button" class="btn btn-misc"
								onclick="logReset()" tabindex="-1">Clear</button>
						</td>
						<td><!-- future --> </td>
					</tbody>
				</table>
				<input type="hidden" id="logclock" name="logclock" value="">
				<input type="hidden" id="opcallsign" name="opcallsign" value="">
				<input type="hidden" id="opoperator" name="opoperator" value="">
				<input type="hidden" id="opband" name="opband" value="">
				<input type="hidden" id="opmode" name="opmode" value="">
				<input type="hidden" id="qkey" name="qkey" value="">
			</form>
		</div>
		<div class="row">
			<div id="d-flex">
				<div id="statusarea" class="invisible alert d-flex align-items-center alert-dismissable fade show" role="alert"></div>
			</div>
		</div>
	</div>
</main>

<?php include("footer.php"); ?>