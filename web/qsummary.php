<?php

// This is the page title in <head>>. It's followed by "| Firefly"
$ff_page_title = "Summary Export";

// This is centered content in the header
$ff_header_content = <<<EOT
	<h2>Summary Export</h2>
	EOT;

include("header.php");
?>
<main>
	<div class="container-md mt-5 shadow rounded-3">
		<div class="row">
				<form id="dupe" name="dupe" method="POST" action="qsummary.php">
					<table class="table table-borderless">
						<thead>
							<th scope="col" style="width: 20%">Header Item</th>
							<th scope="col" style="width: 80%">Value</th>
						</thead>
						<tbody>
						<tr>
							<td class="align-top">
								<b>Comment Field Text</b><br>
								<i>Appears after the table</i>
							</td>
							<td class="align-top">
								<input type="text" size=20 name="dupecomment" id="dupecomment" class="form-control" autocomplete="off">
							</td>
						</tr><tr>
							<td class="align-top">
								<b>Field Day Type</b>
							</td>
							<td class="align-top">
								<select size=1 name="dupecontest" id="dupecontest" class="form-control">
									<option value="ARRL-FD" selected>ARRL Field Day</option>
									<option value="WFD">Winter Field Day</option>
								</select>
							</td>
						</tr>
						<tr>
						<td colspan="2">
							<button type="submit" class="btn btn-primary btn-sm">Generate Summary Sheet</button>
						</td>
						</tr>
						</tbody>
					</table>
				</form>
		</div>
	</div>
</main>
<?php include("footer.php"); ?>