
/**
* Configuration
*/


// top level configuration variables
// prefix for where the API is located on the webserver
var APIPrefix = "api";

// Is the session cookie there?
var stationcookieexists = false;
var stationInfoSet = false;

// master configuration for the application itself
let config = {
	"general": [
		{"stationCall" : "" },
		{ "fdType": ""},
		{ "multiOp": ""}
	],
	"bands": null,
	"modes": null,
	"sections": {
		"sections": [ "XX" ]
	}
};

// statuses and counters
var qsinceload = 0;
var thispage = location.href.split("/").slice(-1);

if(thispage == "handkey.html")
	var isHandKey = true;

// load configuration from JSON files
async function getConfig(configType){
	var url = `${APIPrefix}/config_${configType}.json`;
	let response = await fetch(url, { cache : "no-store" } );
	if(response.ok){
		let a = await response.json();
		switch(configType){
			case "general":
				config.general = a;
				setGeneralConfig();
				break;
			case "bands":
				config.bands = a;
				refreshBandList(config.bands.band);
				setStationDataFromCookie();
				break;
			case "modes":
				config.modes = a;
				refreshModeList(config.modes.modes);
				setStationDataFromCookie();
				break;
			case "sections":
				config.sections = a;
				break;
		}
		return true;
	}

	console.log(`getConfig() error status ${response.status} ${response.statusText}`);
	return false;
}

//
// apply general configuration - used inside getConfig() only because it's async
//
function setGeneralConfig() {
    const { stationCall, multiOp } = config.general;

    // Set station callsign
    $("#callsign").val(stationCall);

    // Handle operator field for single‑op mode
    if (multiOp === "false") {
        $("#operator")
            .prop("readOnly", true)
            .val(stationCall);
    }
}

//
// On the load
//
window.addEventListener("load", async function(){
	await getConfig("general");
	await getConfig("bands");
	await getConfig("modes");
	await getConfig("sections");
	setGeneralConfig();
	updateLogTime();
	initSectionSelect();
	setInterval(updateLogTime,1000);
	setInterval(testCookieExists,1000);

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
function updateLogTime() {
	let now = new Date().toISOString().slice(0, 19).replace('T', ' ');
	document.getElementById("logclock").value = now;
}

//
// Typehead for Section
//
function initSectionSelect() {
	const sections = config.sections.sections; // your uppercase array
	const input = document.getElementById('section');
	const hint  = document.getElementById('section-hint');

	input.addEventListener('input', function () {
		const q = input.value.toUpperCase();

		if (!q) {
			hint.textContent = '';
			return;
		}

		const match = sections.find(s => s.startsWith(q));

		hint.textContent = match || '';
	});

	input.addEventListener('blur', function () {
	    hint.textContent = '';
	});
}

//
// Store contact in log functions
//

// intercept enter and escape for log entry/clearing
// Only handle Enter/Escape inside the #log form
$("#log").on("keydown", function (k) {
    if (k.key === "Escape") {
        k.preventDefault();
        logReset();
    }
});

/**
 *
 * This is the block for JQuery Validate to test the inputs
 *
 */

// Add a custom rule called "callsign"
const callsignRegex = /^(?:[A-Z]{1,2}|[0-9][A-Z])\d{1,2}[A-Z]{1,4}$/i;

// Validation function for the callsign
$(function () {
    $.validator.addMethod("callsign", function(value, element) {
        return this.optional(element) || callsignRegex.test(value);
    });
});

// Validation function for the operator class
$(function (){
    $.validator.addMethod("opclass", function(value, element) {
        if( config.general.fdType == "WFD"){
            re = /^[0-9]{1,2}[himoHIOM]$/;
        } else {
            re = /^[0-9]{1,2}[a-fA-F]$/;
        }
        return this.optional(element) || re.test(value);
    });
});

// Validation function for the section
$(function (){
    $.validator.addMethod("section", function(value, element) {
        let retval = false;
        if(config.sections.sections.includes(value.toUpperCase())){
            retval = true;
        }
        return this.optional(element) || retval;
    });
});

// Actual validation of the forms
$(document).ready(function () {
	$("#log").validate({
		//debug: true,
		rules: {
			call: {
				required: true,
				callsign: true,
				remote: {
					url: "api/checkdupe.php",
					type: "GET",
					data: {
						qkey: function () {
							return qkeyCalculate(
								$("#call").val(),
								$("#band").val(),
								$("#mode").val()
							);
						}
					}
				}
			},
            "opclass": {
                required: true,
                opclass: true
            },
            "section": {
                required: true,
                section: true
            }
        },
		messages: {
        	call: {
				required: "Callsign is required",
				callsign: "Enter a valid callsign",
				remote: "Duplicate QSO in log"
        	},
			opclass: {
				required: "Class is required",
				opclass: "Invalid Class"
			},
			section: {
				required: "Section is required",
				section: "Invalid Section"
			}
    	},
        highlight: function (element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        },
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            error.insertAfter(element);
        },
        submitHandler: function(form) {
			// form is valid → run your existing logic
			logSubmit();
        }
    });

	$("#stationset").validate({
		//debug: true,
		rules: {
			"callsign": {
				required: true,
				callsign: true,
			},
			"operator": {
				required: true,
				callsign: true
			},
            "band": {
                required: true,
            },
            "mode": {
                required: true,
            }
        },
		errorPlacement: function () {},
		messages: {
			callsign: "",
			operator: "",
			band: "",
			mode: ""
		},
        highlight: function (element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        },
        submitHandler: function(form) {
           saveStationData();
		   stationInfoSet = true;
        }
    });
});

// submit the log from above
function logSubmit() {
	if(!stationInfoSet){
		alertStatusMsg("Station information not set");
		return false;
	}

	// Initialize the form
	var lform = document.getElementById("log");
	lform.method = "POST";
	lform.action = "#";


	["callsign","operator","band","mode"].forEach(id =>
    	$("#op" + id).val($("#" + id).val())
	);

	const qsocall = $("#call").val();
	$("#qkey").val(qkeyCalculate(qsocall, $("#band").val(), $("#mode").val()));

	var fd = new FormData(lform);
	var fj = Object.fromEntries(fd.entries());

	$.ajax({
		type:	"post",
		url:	"api/storeqso.php",
		data:	fj,
		success: function(msg) {
			disp_message = "Stored QSO for " + qsocall + "!  (QKEY: " + qkey + " , API Resp: " + msg + ")";
			decayingGoodStatusMsg(disp_message, 3);
			console.log(disp_message);
			qsinceload++;

			// See if we should reload since the DOM structure sometimes gets wonky after long use
			if( parseInt(qsinceload) > 25){
				setTimeout(window.location.reload(), 1000);
			}
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
    var form = $("#log");
    form[0].reset();
    form.validate().resetForm();
    form.find("input").removeClass("is-valid is-invalid");
    setTimeout(() => $("#call").focus(), 0);
}


//
// Manage QSOs
//
function editQSO(qkey){
	window.open("editqso.php?qkey=" + qkey, "_blank");
}

function delQSO(qkey, qcall){
	if( confirm("Are you sure your want to delete QSO with " + qcall + "?\n(QSO ID# " + qkey + ")") ){
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


/**
 *
 * All of the Station Setup information
 */

// Band Configuration Dropdown
function refreshBandList(blist){
    var r = "<option value=\"\">Band...</option>";
    blist.forEach(
        function(v) {
            r += `<option value="${v}">${v}</option>`;
        }
    )
    document.getElementById("band").innerHTML = r;
}

// Mode Configuration Dropdown
function refreshModeList(mlist){
    var r = "<option value=\"\">Mode...</option>";
    for( let m of mlist){
        r += `<option value="${m['key']}">${m['value']}</option>`;
    }
    document.getElementById("mode").innerHTML = r;
}

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
	setCookie("operator", document.getElementById("operator").value, 30);
	setCookie("band", document.getElementById("band").value, 30);
	setCookie("mode", document.getElementById("mode").value, 30);
	stationcookieexists = true;
    setTimeout(() =>
		$("#stationset").find(".form-control").removeClass("is-valid is-invalid")
		, 3000);
};

// Set Station from Cookie on Load
function setStationDataFromCookie() {
	var re = /^[a-zA-Z0-9\/]{2,9}[a-zA-Z]$/;
	if( ( operator = getCookie("operator") ) && re.test(getCookie("operator")) ) {
		document.getElementById("operator").value = operator;
	}
	if( ( band = getCookie("band") ) && ( getCookie("band") !== "X" ) ) {
		document.getElementById("band").value = band;
	}
	if( ( mode = getCookie("mode") ) && ( getCookie("mode") !== "X" ) ) {
		document.getElementById("mode").value = mode;
	}
	stationInfoSet = true;
}

// Test for the cookie
function testCookieExists(){
	if( stationcookieexists ){
		if(	getCookie("operator") === "" ||	getCookie("band") === "" ||	getCookie("mode") === "" ){
			alert("SYSTEM ERROR: Configuration Cookie Vanished!\nAre you using https:// ?\nStop logging and talk to the person who runs this logging instance!");
			stationcookieexists = false;
		}
	}
}
