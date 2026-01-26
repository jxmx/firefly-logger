<?php

// This is the page title in <head>>. It's followed by "| Firefly"
$ff_page_title = "CSV Export";

// This is centered content in the header
$ff_header_content = <<<EOT
	<h2>CSV Export</h2>
	EOT;

include("header.php");
?>
<main>
	<div class="container-md mt-5 shadow rounded-3">
		<div class="row">
				<form id="CSV" name="CSV" method="POST" action="csv.php">
					<table class="table table-borderless">
						<tbody>
						<tr>
						<td colspan="2">
							<button type="submit" class="btn btn-primary btn-sm">Generate CSV File</button>
						</td>
						</tr>
						</tbody>
					</table>
				</form>
		</div>
	</div>
</main>
<?php include("footer.php"); ?>
