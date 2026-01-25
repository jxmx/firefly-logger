<?php

// This is the page title in <head>>. It's followed by "| Firefly"
$ff_page_title = "Scoreboard";

// This is centered content in the header
$ff_header_content = <<<EOT
	<h2>Scoreboard</h2>
	EOT;

include("header.php");
?>
  <main>
	<div class="container-md">
		<div class="row mt-5">
			<div class="col-md m-1 shadow h-auto d-inline-block">
				<div class="fs-1 text-center fw-bold">Total QSOs</div>
				<div id="total" name="total" class="fs-1 text-center">0</div>
			</div>
			<div class="col-md m-1 shadow h-auto d-inline-block">
				<div class="fs-1 text-center fw-bold">Voice</div>
				<div id="phone" name="phone" class="fs-1 text-center">0</div>
			</div>
			<div class="col-md m-1 shadow h-auto d-inline-block">
				<div class="fs-1 text-center fw-bold">CW</div>
				<div id="cw" name="cw" class="fs-1 text-center">0</div>
			</div>
			<div class="col-md m-1 shadow h-auto d-inline-block">
                <div class="fs-1 text-center fw-bold">Digital/Data</div>
                <div id="data" name="data" class="fs-1 text-center">0</div>
			</div>
		</div>
		<div class="row mt-5">
			<div class="col-md m-1 shadow h-auto d-inline-block">
				<div class="fs-1 text-center fw-bold">Total Pts</div>
				<div id="score" name="score" class="fs-1 text-center">0</div>
			</div>
			<div class="col-md m-1 shadow h-auto d-inline-block">
				<div class="fs-1 text-center fw-bold">Sections</div>
				<div id="sections" name="sections" class="fs-1 text-center">0</div>
			</div>
			<div class="col-md m-1 shadow h-auto d-inline-block">
				<div class="fs-1 text-center fw-bold">Distinct Calls</div>
				<div id="distinct" name="distinct" class="fs-1 text-center">0</div>
			</div>
			<div class="col-md m-1 h-auto d-inline-block">
                <div class="fs-1 text-center fw-bold"></div>
                <div class="fs-1 text-center"></div>
			</div>
		</div>
	</div>
	<div class="container-md shadow log-disp-container">
		<div class="row">
			<div class="col">
				<div class="d-flex titlebars p-2"><b>Last 10 QSOs</b></div>
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
	                        </tr>
	                    </thead>
	                    <tbody id="logdisplay" name="logdisplay"></tbody>
                </table>
            </div>


			<div id="logdisplay"></div>
		</div>

	</div>
</main>
<?php include("footer.php"); ?>