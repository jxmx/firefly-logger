<?php

// This is the page title in <head>>. It's followed by "| Firefly"
$ff_page_title = "ADIF Export";

// This is centered content in the header
$ff_header_content = <<<EOT
	<h2>ADIF Export</h2>
	EOT;

include("header.php");
?>
<main>
	<div class="container-md mt-5 shadow rounded-3">
		<div class="row">
			<form id="adif" name="adif" method="POST" action="exports/adif.php">
				<table class="table table-borderless">
					<tbody>
					<tr>
						<td class="align-top">
							<b>Comment Field Text</b><br>
							<i>Appears after Field Day type label</i>
						</td>
						<td class="align-top">
							<input type="text" size=20 name="adifcomment" id="adifcomment" class="form-control" autocomplete="off">
						</td>
					</tr><tr>
						<td class="align-top">
							<b>Field Day Type</b>
						</td>
						<td class="align-top">
							<select size=1 name="adifcontest" id="adifcontest" class="form-control">
								<option value="ARRL-FD" selected>ARRL Field Day</option>
								<option value="WFD">Winter Field Day</option>
							</select>
						</td>
					</tr>
					<tr>
					<td colspan="2">
						<button type="submit" class="btn btn-primary btn-sm">Generate ADIF File</button>
					</td>
					</tr>
					</tbody>
				</table>
			</form>
		</div>
	</div>
</main>
<?php include("footer.php"); ?>
