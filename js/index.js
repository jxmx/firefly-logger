//
// Configuration Helpers
//


// top level configuration variables
var APIPrefix = "/api";			// prefix for where the API is located on the webserver

// master configuration for the application itself
let config = {					
	"general": [ 
		{"stationCall" : "" },
		{ "fdType": ""},
		{ "multiOp": ""}
	],
	"bands": null,
	"modes": null,
	"sections": null,
};

// statuses and counters
var qsinceload = 0;

// load configuration from JSON files
function getConfig(configType){
	var xmlhttp = new XMLHttpRequest();
	var url = APIPrefix + "/config_" + configType + ".json";
	xmlhttp.onreadystatechange = function () {
		if( this.readyState == 4 && this.status == 200 ){
			var a = JSON.parse(this.responseText);
			switch(configType){
				case "general":
					console.log("get config general");
					config.general = a;
					console.log("set config general");
					setGeneralConfig();
					break;		
				case "bands":
					console.log("get config bands");
					config.bands = a;
					refreshBandList(config.bands.band);
					setStationDataFromCookie();
					break;
				case "modes":
					console.log("get config modes");
					config.modes = a;
					refreshModeList(config.modes.modes);
					setStationDataFromCookie();
					break;
				case "sections":
					console.log("get config sections");
					config.sections = a;
					break;
			}
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
	document.getElementById("callsign").value = config.general.stationCall;

	// show/hit the Operator box
	if( config.general.multiOp == false){
		document.getElementById("operator").readOnly = true;
		document.getElementById("operator").value = config.general.stationCall;
		submitOkOperCall = true;
	}
}

//
// On the load
//
window.addEventListener("load", function(){
	getConfig("general");
	getConfig("bands");
	getConfig("modes");
	getConfig("sections");
	setGeneralConfig();
	updateLogTime();
	setInterval(updateLogTime,1000);
	});

//
// QKey
//
function qkeyCalculate(qsocall, opband, opmode){
	let a = qsocall.toUpperCase();
	let b = opband.toUpperCase();
	let c = opmode.toUpperCase();
	return(a+b+c);
}

// 
// Logging clock for QSOs
//
function currentTimestamp() {
	function pad(n) { return n<10 ? '0'+n : n }
	var d = new Date();
	return d.getUTCFullYear()+'-'
         + pad(d.getUTCMonth()+1)+'-'
         + pad(d.getUTCDate())+' '
         + pad(d.getUTCHours())+':'
         + pad(d.getUTCMinutes())+':'
         + pad(d.getUTCSeconds())+' ';
};

function updateLogTime() {
	document.getElementById("logclock").value = currentTimestamp();
}



//
// Field Validators
//

var submitOkCall = false;
var submitOkClass = false;
var submitOkSection = false;
var submitOkDupe = false;

//
// QSO Call Validation - is callsign well-formed
//
$('#call').on('input', function() {
	var input=$(this);
	var re = /^[a-zA-Z0-9\/]{2,9}[a-zA-Z]$/;
	var is_valid = re.test(input.val());
	if(is_valid){
		submitOkCall = true;
		input.removeClass("is-invalid").addClass("is-valid");
	} else {
		submitOkCall= false;
		input.removeClass("is-valid").addClass("is-invalid");
	}
});

//
// Duplicate checking logic
// Note: this has to be async because of XHR and then call a different function to lock stuff out
//
function isDupeQSO() {
	var qsocall = document.getElementById("call").value;
	var re = /^[a-zA-Z0-9\/]{2,9}[a-zA-Z]$/;
	var is_valid = re.test(qsocall);
	if( !is_valid ){
		submitOkCall= false;
		return null;
	}
    var opband = document.getElementById("band").value;
    var opmode = document.getElementById("mode").value;
    var qkey = qkeyCalculate(qsocall, opband, opmode);

    $.ajax({
        type:   "GET",
        url:    "api/checkdupe.php?qkey=" + qkey,
        success: function(output) {
			handleIsDupeQSO(output);
        },
        failure: function(msg)  {
			alertStatusMsg("Unable to contact server with error: " + msg);
        }
    });

}

function handleIsDupeQSO(resp) {

	callf = document.getElementById("call")

	if(resp === "DUPE"){
		submitOkDupe = false;	
		callf.classList.remove("is-valid");
		callf.classList.add("is-invalid");
		callf.focus();
		alertStatusMsg("Duplicate QSO: Callsign + Band + Mode already in log");
	} else {
		submitOkDupe = true;
		callf.classList.remove("is-invalid");
		callf.classList.add("is-valid");
	}
}

//
// Validate the Operating Class for wellformedness
//
$('#opclass').on('input', function() {
    var input=$(this);
	var re;

	if( config.general.fdType == "WFD"){
		re = /^[0-9]{1,2}[hioHIO]$/;
	} else {
		re = /^[0-9]{1,2}[a-fA-F]$/;
	}  

    var is_valid = re.test(input.val());
    if(is_valid){
		submitOkClass = true;
        input.removeClass("is-invalid").addClass("is-valid");
    } else {
		submitOkClass= false;
        input.removeClass("is-valid").addClass("is-invalid");
    }
});

//
// Type-ahead searching for section box
//
var substringMatcher = function(strs) {
	return function findMatches(q, cb) {
	  var matches, substringRegex;
  
	  // an array that will be populated with substring matches
	  matches = [];
  
	  // regex used to determine if a string contains the substring `q`
	  substrRegex = new RegExp(q, 'i');
  
	  // iterate through the pool of strings and for any string that
	  // contains the substring `q`, add it to the `matches` array
	  $.each(strs, function(i, str) {
		if (substrRegex.test(str)) {
		  matches.push(str);
		}
	  });
  
	  cb(matches);
	};
  };

//
// Validate the Section for correctness
//
$('#arrl-sections .typeahead').typeahead({
	hint: true,
	highlight: true,
	minLength: 1
  },
  {
	name: 'sections',
	source: substringMatcher(config.sections)
  });

$('#section').on('input', function() {
    var input=$(this);
	document.getElementById("section").value = input.val().toUpperCase();
	config.sections.includes
    if(config.sections.sections.includes(input.val().toUpperCase())){
		submitOkSection = true;		
        input.removeClass("is-invalid").addClass("is-valid");
    } else {
		submitOkSection = false;
        input.removeClass("is-valid").addClass("is-invalid");
    }
});

//
// Store contact in log functions
//

// Prevent logging button from working if QSO isn't complete/valid
$('#log').on('input', function() {
	if(checkSubmitOkStatus() && checkStationSetOkStatus()){
		toggleLogButton(true);
	} else {
		toggleLogButton(false);
	}
});

function toggleLogButton(bbool) {
	var logbutton = document.getElementById("logsubmit");
	if (bbool) {
		logbutton.classList.remove("btn-danger");
		logbutton.classList.add("btn-success");
	} else {
		logbutton.classList.remove("btn-success");
		logbutton.classList.add("btn-danger");
	}
};

// intercept enter and escape for log entry/clearing
$(document).on('keyup', function(k) {
	if(k.key == "Enter") logSubmit();
	if(k.key == "Escape") logReset();
	
});


// reset function status
function resetSubmitOkStatus() {
	submitOkCall = false;
	submitOkClass = false;
	submitOkSection = false;
	submitOkDupe = false;
	toggleLogButton(false);
}

// check if it's ready to submit
function checkSubmitOkStatus() {
	if( submitOkCall && submitOkClass && submitOkSection ){ // && submitOkDupe )
		return true;
	} else {
		return false;
	}
}

// submit the log
function logSubmit() {

	if(!checkStationSetOkStatus()){
		alertStatusMsg("Station information not set");
		return false;
	}

	if(!checkSubmitOkStatus()){
		decayingAlertStatusMsg("Entry not complete", 1);
		return false;
	}

	// Initialize the form
	var lform = document.getElementById("log");
	lform.method = "POST";
	lform.action = "#";

	// Generate and store the qkey hash 
	
 	// I have no idea whyu these can't be directly assigned by value.... Javascript is annoying
	var opcallsign=  document.getElementById("callsign").value;
	document.getElementById("opcallsign").value = opcallsign;

	var opoperator = document.getElementById("operator").value;
	document.getElementById("opoperator").value = opoperator

	var opband = document.getElementById("band").value;
	document.getElementById("opband").value = opband;

	var opmode = document.getElementById("mode").value;
	document.getElementById("opmode").value = opmode;

	var qsocall = document.getElementById("call").value;
	var qkey = qkeyCalculate(qsocall, opband, opmode);
	document.getElementById("qkey").value = qkey;

	var fd = new FormData(lform);
	var fj = Object.fromEntries(fd.entries());

	$.ajax({
		type:	"post",
		url:	"api/storeqso.php",
		data:	fj,
		success: function(msg) {
			decayingGoodStatusMsg("Stored QSO for " + qsocall + "!  (QKEY: " + qkey + " , API Resp: " + msg + ")", 3);
			qsinceload++;
			
			// See if we should reload since the DOM structure sometimes gets wonky after long use
			if( parseInt(qsinceload) > 25){
				setTimeout(window.location.reload(), 1000);
			} 

			// update the display and clear the entry
			updateDisplayLog();
			logReset();
		},
		failure: function(msg) {
			alertStatusMsg("Failed to store QSO for " + qsocall + "! (QKEY: " + qkey + " , API Resp: " + msg + ")");
		}
	});

	return true;
};

// reset the log form
function logReset() {
	clearStatusMsg();
	resetSubmitOkStatus();
    document.getElementById("log").reset();
    $('#log input').parent().find('input').removeClass("is-invalid").removeClass("is-valid");
    document.getElementById("call").focus();
};

// 
// Manage QSOs
//
function editQSO(qkey){
	window.open("editqso.php?qkey=" + qkey, "_blank");
}

function delQSO(qkey, qcall){
	if( confirm("Are you sue your want to delete QSO with " + qcall + "?\n(QSO ID# " + qkey + ")") ){
	    $.ajax({
	        type:   "GET",
	        url:    "api/delqso.php?qkey=" + qkey,
	        success: function(output) {
	            handleDelQSO(output, qkey, qcall);
	        },
	        failure: function(msg)  {
	            alertStatusMsg("Unable to contact server with error: " + msg);
	        }
	    });
	} 
}

function handleDelQSO(output, qkey, qcall){
	if( output === "OK" ){
		decayingAlertStatusMsg("Deleted QSO with " + qcall + " (key: " + qkey + ")", 3);
		updateDisplayLog();
	} else {
		alertStatusMsg("Error deleting QSO: " + output + " (key: " + qkey + ")");
	}		
}

//
// All of the Station Setup information
//

//
// Field validators
//

var submitOkOperCall = false;
var submitOkBand = false;
var submitOkMode = false;

// Is the station info completed
function checkStationSetOkStatus() {
	if( submitOkOperCall && submitOkBand && submitOkMode ){
		return true;
	} else {
		return false;
	}
}

// Operator Callsign
$('#operator').on('input', function() {
    var input=$(this);
    var re = /^[a-zA-Z0-9\/]{2,9}[a-zA-Z]$/;
    var is_valid = re.test(input.val());
    if(is_valid){
        submitOkOperCall = true;
        input.removeClass("is-invalid");
    } else {
        submitOkOperCall= false;
        input.addClass("is-invalid");
    }
});

//
// Band Configuration Dropdown
//
function refreshBandList(blist){
    var r = "<option value=\"X\">Select Band...</option>";
    blist.forEach(
        function(v) {
            r += `<option value="${v}">${v}</option>`;
        }
    )

    document.getElementById("band").innerHTML = r;
}

$('#band').on('input', function() {
    var input=$(this);
    if(input.val() !== "X" ){
        submitOkBand = true;
        input.removeClass("is-invalid");
    } else {
        submitOkBand= false;
        input.addClass("is-invalid");
    }
});

//
// Mode Configuration Dropdown
//
function refreshModeList(mlist){
    var r = "<option value=\"X\">Select Mode...</option>";
    for( let m of mlist){
        r += `<option value="${m['key']}">${m['value']}</option>`;
    }
    document.getElementById("mode").innerHTML = r;
}

$('#mode').on('input', function() {
    var input=$(this);
    if(input.val() !== "X" ){
        submitOkMode = true;
        input.removeClass("is-invalid");
    } else {
        submitOkMode= false;
        input.addClass("is-invalid");
    }
});


// Read the data from the cookie and set it on page load


// Generic set method
function setCookie(cname, cvalue, exdays) {
	var d = new Date();
	d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000 ));
	var expires = "expires=" + d.toUTCString();
	document.cookie = cname + "=" + cvalue + ";" + expires + "; SameSite=Lax;";
};

// Generic get method
function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

// Set Station from Form
function saveStationData() {
	if( checkStationSetOkStatus() ){
		setCookie("operator", document.getElementById("operator").value, 30);
		setCookie("band", document.getElementById("band").value, 30);
		setCookie("mode", document.getElementById("mode").value, 30);
	} else {
		alertStatusMsg("Station information not complete");
	}
};


// Set Station from Cookie on Load
function setStationDataFromCookie() {

	var re = /^[a-zA-Z0-9\/]{2,9}[a-zA-Z]$/;

	if( ( operator = getCookie("operator") ) && re.test(getCookie("operator")) ) {
		document.getElementById("operator").value = operator;
		submitOkOperCall = true;
	}

	if( ( band = getCookie("band") ) && ( getCookie("band") !== "X" ) ) {
		document.getElementById("band").value = band;
		submitOkBand = true;
	}

	if( ( mode = getCookie("mode") ) && ( getCookie("mode") !== "X" ) ) {
		document.getElementById("mode").value = mode;
		submitOkMode = true;
	}
}
