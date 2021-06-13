//
// All of the Station Setup information
//

//
// Field validators
//

var submitOkStationCall = false;
var submitOkOperCall = false;
var submitOkBand = false;
var submitOkMode = false;

// Is the station info completed
function checkStationSetOkStatus() {
	if( submitOkStationCall && submitOkOperCall && submitOkBand && submitOkMode ){
		return true;
	} else {
		return false;
	}
}

// Station Callsign
$('#callsign').on('input', function() {
    var input=$(this);
    var re = /^[a-zA-Z0-9\/]{2,9}[a-zA-Z]$/;
    var is_valid = re.test(input.val());
    if(is_valid){
        submitOkStationCall = true;
        input.removeClass("is-invalid").addClass("is-valid");
    } else {
        submitOkStationCall= false;
        input.removeClass("is-valid").addClass("is-invalid");
    }
});

// Operator Callsign
$('#operator').on('input', function() {
    var input=$(this);
    var re = /^[a-zA-Z0-9\/]{2,9}[a-zA-Z]$/;
    var is_valid = re.test(input.val());
    if(is_valid){
        submitOkOperCall = true;
        input.removeClass("is-invalid").addClass("is-valid");
    } else {
        submitOkOperCall= false;
        input.removeClass("is-valid").addClass("is-invalid");
    }
});

// Band
$('#band').on('input', function() {
    var input=$(this);
    if(input.val() !== "X" ){
        submitOkBand = true;
        input.removeClass("is-invalid").addClass("is-valid");
    } else {
        submitOkBand= false;
        input.removeClass("is-valid").addClass("is-invalid");
    }
});

// Mode
$('#mode').on('input', function() {
    var input=$(this);
    if(input.val() !== "X" ){
        submitOkMode = true;
        input.removeClass("is-invalid").addClass("is-valid");
    } else {
        submitOkMode= false;
        input.removeClass("is-valid").addClass("is-invalid");
    }
});


// Read the data from the cookie and set it on page load
setStationDataFromCookie();

// Generic set method
function setCookie(cname, cvalue, exdays) {
	var d = new Date();
	d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000 ));
	var expires = "expires=" + d.toUTCString();
	document.cookie = cname + "=" + cvalue + ";" + expires + "; Secure";
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
		setCookie("callsign", document.getElementById("callsign").value, 30);
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

	if( ( callsign = getCookie("callsign") ) && re.test(getCookie("callsign")) ){
		document.getElementById("callsign").value = callsign;
		submitOkStationCall = true;
	}

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

//
// Help Pop-up
//
function showHelp() {
	window.open('help.html');
};
