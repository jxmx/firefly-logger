<?php

// This is the page title in <head>>. It's followed by "| Firefly"
$ff_page_title = "Cabrillo Export";

// This is centered content in the header
$ff_header_content = <<<EOT
	<h2>Cabrillo Export</h2>
	EOT;

include("header.php");
?>
<main>
	<div class="container-md mt-5 shadow rounded-3">
		<div class="row">
				<form id="cabrillo" name="cabrillo" method="POST" action="cabrillo.php">
					<table class="table table-borderless">
						<thead>
							<th scope="col" style="width: 20%">Header Item</th>
							<th scope="col" style="width: 80%">Value</th>
						</thead>
						<tbody>
						<tr>
							<td class="align-top">
								<b>Club Name</b>
							</td>
							<td class="align-top">
								<input type="text" size=20 name="cabclub" id="cabclub" class="form-control" autocomplete="off">
							</td>
						</tr><tr>
							<td class="align-top">
								<b>Callsign</b>
							</td>
							<td class="align-top">
								<input type="text" size=20 name="cabcall" id="cabcall" class="form-control" autocomplete="off" autocapitalize="on">
							</td>
						</tr><tr>
							<td class="align-top">
								<b>Name</b><br>of the submitter
							</td>
							<td class="align-top">
								<input type="text" size=20 name="cabname" id="cabname" class="form-control" autocomplete="off" autocapitalize="on">
							</td>
						</tr><tr>
							<td class="align-top">
								<b>Email</b><br>for the submitter
							</td>
							<td class="align-top">
								<input type="text" size=20 name="cabemail" id="cabemail" class="form-control" autocomplete="off" autocapitalize="on">
							</td>
						</tr><tr>
							<td class="align-top">
								<b>Category Station</b><br>Field Day: A-F, WFD: H,I,O
							</td>
							<td class="align-top">
								<input type="text" size=20 name="cabstation" id="cabstation" class="form-control" autocomplete="off" autocapitalize="on">
							</td>
						</tr><tr>
							<td class="align-top">
								<b>Category # Transmitters</b>
							</td>
							<td class="align-top">
								<input type="text" size=20 name="cabtransmitter" id="cabtransmitter" class="form-control" autocomplete="off" autocapitalize="on">
							</td>
						</tr><tr>
							<td class="align-top">
								<b>ARRL Section Code</b>
							</td>
							<td class="align-top">
								<input type="text" size=20 name="cabsection" id="cabsection" class="form-control" autocomplete="off" autocapitalize="on">
							</td>
						</tr><tr>
							<td class="align-top">
								<b>Claimed Score</b>
							</td>
							<td class="align-top">
								<input type="text" size=20 name="cabscore" id="cabscore" class="form-control" autocomplete="off" autocapitalize="on">
							</td>
						</tr><tr>
							<td class="align-top">
								<b>Street Address</b>
							</td>
							<td class="align-top">
								<input type="text" size=20 name="cabstreet" id="cabstreet" class="form-control" autocomplete="off">
							</td>
						</tr><tr>
							<td class="align-top">
								<b>City</b>
							</td>
							<td class="align-top">
								<input type="text" size=20 name="cabcity" id="cabcity" class="form-control" autocomplete="off">
							</td>
						</tr><tr>
							<td class="align-top">
								<b>State / Province</b>
							</td>
							<td class="align-top">
								<input type="text" size=20 name="cabstate" id="cabstate" class="form-control" autocomplete="off">
							</td>
						</tr><tr>
							<td class="align-top">
								<b>Zip / Postal Code</b>
							</td>
							<td class="align-top">
								<input type="text" size=20 name="cabzip" id="cabzip" class="form-control" autocomplete="off" autocapitalize="on">
							</td>
						</tr><tr>
							<td class="align-top">
								<b>Country</b>
							</td>
							<td class="align-top">
								<input type="text" size=20 name="cabcountry" id="cabcountry" class="form-control" autocomplete="off" autocapitalize="on">
							</td>
						</tr><tr>
							<td class="align-top">
								<b>Field Day Type</b>
							</td>
							<td class="align-top">
								<select size=1 name="cabcontest" id="cabcontest" class="form-control">
									<option value="ARRL-FD" selected>ARRL Field Day</option>
									<option value="WFD">Winter Field Day</option>
								</select>
							</td>
						</tr>
						<tr>
						<td colspan="2">
							<button type="submit" class="btn btn-primary btn-sm">Generate Cabrillo File</button>
						</td>
						</tr>
						</tbody>
					</table>
				</form>
		</div>
	</div>
</main>
<?php include("footer.php"); ?>
