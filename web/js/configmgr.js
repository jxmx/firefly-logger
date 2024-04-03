var APIPrefix = "api";	

let config = {
    "stationCall": null,
    "fdType": null,
    "multiOp": null
};

async function getConfig(){
    var url = `${APIPrefix}/config_general.json`;
    let response = await fetch(url, { cache : "no-store" } );
    if(response.ok){
        config = await response.json();
		document.getElementById("stationCall").value = config.stationCall;
    	document.getElementById("fdType").value = config.fdType;

    	if( String(config.multiOp) === "false"){
        	document.getElementById("multiOp").checked = false;
    	} else {
        	document.getElementById("multiOp").checked = true;
    	}
        return true;
    }
    console.log(`getConfig() error status ${response.status} ${response.statusText}`);
    return false;
}

window.addEventListener("load", async function(){
    await getConfig();
});
