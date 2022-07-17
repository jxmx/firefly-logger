var APIPrefix = "/api";	

let config = {
    "stationCall": null,
    "fdType": null,
    "multiOp": null
};

// load configuration from JSON files
function getConfig(){
	var xmlhttp = new XMLHttpRequest();
	var url = `${APIPrefix}/config_general.json`;
	xmlhttp.onreadystatechange = function () {
		if( this.readyState == 4 && this.status == 200 ){
			config = JSON.parse(this.responseText);
			setGeneralConfig();
		} else if( this.readyState == 4 && this.status != 200 ){
			console.log("Failed to retreive configuration: " + url);
			console.log("Ready State: " + this.readyState);
			console.log("HTTP Status: " + this.status);
			alert("Error retriving configuration file - check console");
		}
	};
	xmlhttp.open("GET", url, true);
	xmlhttp.send();
}

//
// apply general configuration - used inside getConfig() only because it's async
//
function setGeneralConfig(){
	// set the station callsign
	document.getElementById("stationCall").value = config.stationCall;
    document.getElementById("fdType").value = config.fdType;

    if( String(config.multiOp) === "false"){
        document.getElementById("multiOp").checked = false;
    } else {
        document.getElementById("multiOp").checked = true;
    }
}

window.addEventListener("load", function(){
    getConfig();
});