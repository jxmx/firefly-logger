<?php
// This is embedded in index.php and handkey.php instead
// of the centered page title

$ff_station_info_form = <<<EOT
	<div class="ff-stationset-box rounded-3 shadow">
		<form id="stationset" name="stationset">
			<table width="100%" height="100%" class="stationsett">
				<tbody>
					<td style="text-align: center;" class="p-2">
						<b>Station Info</b>
					</td>
					<td>
						<input type="text" id="callsign" name="callsign" size="10" class="form-control" placeholder="Callsign" autocomplete="off" readonly>
					</td>
					<td id="set-operator">
						<input type="text" id="operator" name="operator" size="10" class="form-control" placeholder="Operator" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();" >
					</td>
					<td>
						<select id="band" name="band" class="form-control">
							<option value="">Band...</option>
						</select>
					</td>
					<td>
						<select id="mode" name="mode" class="form-control">
							<option value="">Mode...</option>
						</select>
					</td>
					<td style="text-align: center;" class="p-2">
						<button type="submit" class="btn btn-primary btn-sm">Set</button>
					</td>
				</tbody>
			</table>
		</form>
	</div>
	EOT;