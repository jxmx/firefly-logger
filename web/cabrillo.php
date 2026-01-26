<?php

// This is the page title in <head>>. It's followed by "| Firefly"
$ff_page_title = "Cabrillo Export";

// This is centered content in the header
$ff_header_content = <<<EOT
	<h2>Cabrillo Export</h2>
	EOT;

$ff_additional_scripts = <<<EOT
	<script src="js/jquery.validate-1.22.0.min.js"></script>
	<script src="js/cabrillo.js"></script>
EOT;

include("header.php");
?>
<main>
	<div class="container-md mt-5 shadow rounded-3">
		<div class="row p-5">
			<form id="cabrillo" name="cabrillo" method="POST" action="exports/cabrillo.php">
				<div class="container-fluid">

					<!-- Club Name -->
					<div class="row mb-3">
						<label for="cabclub" class="col-md-3 col-form-label fw-bold">
							Club Name
						</label>
						<div class="col-md-9">
							<input type="text" name="cabclub" id="cabclub" class="form-control" autocomplete="off">
						</div>
					</div>

					<!-- Callsign -->
					<div class="row mb-3">
						<label for="cabcall" class="col-md-3 col-form-label fw-bold">
							Callsign
						</label>
						<div class="col-md-9">
							<input type="text" name="cabcall" id="cabcall" class="form-control" autocomplete="off" autocapitalize="on">
						</div>
					</div>

					<!-- Name of submitter -->
					<div class="row mb-3">
						<label for="cabname" class="col-md-3 col-form-label fw-bold">
							Name<br><small class="text-muted">of the submitter</small>
						</label>
						<div class="col-md-9">
							<input type="text" name="cabname" id="cabname" class="form-control" autocomplete="off" autocapitalize="on">
						</div>
					</div>

					<!-- Email -->
					<div class="row mb-3">
						<label for="cabemail" class="col-md-3 col-form-label fw-bold">
							Email<br><small class="text-muted">for the submitter</small>
						</label>
						<div class="col-md-9">
							<input type="text" name="cabemail" id="cabemail" class="form-control" autocomplete="off" autocapitalize="on">
						</div>
					</div>

					<!-- Category Station -->
					<div class="row mb-3">
						<label for="cabstation" class="col-md-3 col-form-label fw-bold">
							Category Station<br>
							<small class="text-muted">Field Day: A–F, WFD: H,I,O</small>
						</label>
						<div class="col-md-9">
							<input type="text" name="cabstation" id="cabstation" class="form-control" autocomplete="off" autocapitalize="on">
						</div>
					</div>

					<!-- Category # Transmitters -->
					<div class="row mb-3">
						<label for="cabtransmitter" class="col-md-3 col-form-label fw-bold">
							Category # Transmitters
						</label>
						<div class="col-md-9">
							<input type="text" name="cabtransmitter" id="cabtransmitter" class="form-control" autocomplete="off" autocapitalize="on">
						</div>
					</div>

					<!-- ARRL Section Code -->
					<div class="row mb-3">
						<label for="cabsection" class="col-md-3 col-form-label fw-bold">
							ARRL Section Code
						</label>
						<div class="col-md-9">
							<input type="text" name="cabsection" id="cabsection" class="form-control" autocomplete="off" autocapitalize="on">
						</div>
					</div>

					<!-- Claimed Score -->
					<div class="row mb-3">
						<label for="cabscore" class="col-md-3 col-form-label fw-bold">
							Claimed Score
						</label>
						<div class="col-md-9">
							<input type="text" name="cabscore" id="cabscore" class="form-control" autocomplete="off" autocapitalize="on">
						</div>
					</div>

					<!-- Street Address -->
					<div class="row mb-3">
						<label for="cabstreet" class="col-md-3 col-form-label fw-bold">
							Street Address
						</label>
						<div class="col-md-9">
							<input type="text" name="cabstreet" id="cabstreet" class="form-control" autocomplete="off">
						</div>
					</div>

					<!-- City -->
					<div class="row mb-3">
						<label for="cabcity" class="col-md-3 col-form-label fw-bold">
							City
						</label>
						<div class="col-md-9">
							<input type="text" name="cabcity" id="cabcity" class="form-control" autocomplete="off">
						</div>
					</div>

					<!-- State / Province -->
					<div class="row mb-3">
						<label for="cabstate" class="col-md-3 col-form-label fw-bold">
							State / Province
						</label>
						<div class="col-md-9">
							<input type="text" name="cabstate" id="cabstate" class="form-control" autocomplete="off">
						</div>
					</div>

					<!-- Zip / Postal Code -->
					<div class="row mb-3">
						<label for="cabzip" class="col-md-3 col-form-label fw-bold">
							Zip / Postal Code
						</label>
						<div class="col-md-9">
							<input type="text" name="cabzip" id="cabzip" class="form-control" autocomplete="off" autocapitalize="on">
						</div>
					</div>

					<!-- Country -->
					<div class="row mb-3">
						<label for="cabcountry" class="col-md-3 col-form-label fw-bold">
							Country
						</label>
						<div class="col-md-9">
							<input type="text" name="cabcountry" id="cabcountry" class="form-control" autocomplete="off" autocapitalize="on">
						</div>
					</div>

					<!-- Field Day Type -->
					<div class="row mb-4">
						<label for="cabcontest" class="col-md-3 col-form-label fw-bold">
							Field Day Type
						</label>
						<div class="col-md-9">
							<select name="cabcontest" id="cabcontest" class="form-select">
								<option value="ARRL-FD" selected>ARRL Field Day</option>
								<option value="WFD">Winter Field Day</option>
							</select>
						</div>
					</div>

					<!-- Submit -->
					<div class="row">
						<div class="col-md-12">
							<button type="submit" class="btn btn-primary btn-sm">
								Generate Cabrillo File
							</button>
						</div>
					</div>

				</div>
			</form>

		</div>
	</div>
</main>
<?php include("footer.php"); ?>
