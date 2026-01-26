<?php

// This is the page title in <head>>. It's followed by "| Firefly"
$ff_page_title = "Config Manager";

// This is centered content in the header
$ff_header_content = <<<EOT
	<h2>Config Manager</h2>
	EOT;

include("header.php");
?>
<main>
	<div class="container-md mt-5 shadow rounded-3">
		<div class="row">
				<form id="cabrillo" name="cabrillo" method="POST" action="api/configmgr.php">
					<table class="table table-borderless">
						<thead>
							<th scope="col" style="width: 20%">Config Item</th>
							<th scope="col" style="width: 80%">Value</th>
						</thead>
						<tbody>
						<tr>
							<td class="align-middle">
								<b>Station Callsign</b>
							</td>
							<td class="align-middle">
								<input type="text" size=20 name="stationCall" id="stationCall" class="form-control" autocomplete="off">
							</td>
						</tr><tr>
							<td class="align-middle">
								<b>Field Day Type</b>
							</td>
							<td class="align-middle">
								<select size=1 name="fdType" id="fdType" class="form-control">
                                    <option value="AFD">ARRL Field Day</option>
                                    <option value="WFD">Winter Field Day</option>
                                </select>
							</td>
						</tr><tr>
							<td class="align-middle">
								<b>Log Multiple Operators</b>
							</td>
							<td class="align-middle">
								<div class="form-switch">
									<input class="form-check-input" type="checkbox" role="switch" size=20 name="multiOp" id="multiOp" role="switch" autocomplete="off">
								</div>
							</td>
						</tr>
						<tr>
						<td>
							<button type="submit" class="btn btn-primary btn-sm">Save Config</button>
						</td>
						<td>
							<button type="button" class="btn btn-danger btn-sm" onclick="clearBrowserConfig()">Clear Local Browser Config</button>
						</td>
						</tr>
						</tbody>
					</table>
				</form>
		</div>
	</div>
</main>

<?php include("footer.php"); ?>