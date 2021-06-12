//
// All of the Station Setup information
//

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
	setCookie("callsign", document.getElementById("callsign").value, 30);
	setCookie("operator", document.getElementById("operator").value, 30);
	setCookie("band", document.getElementById("band").value, 30);
	setCookie("mode", document.getElementById("mode").value, 30);
};


// Set Station from Cookie on Load
function setStationDataFromCookie() {
	if( callsign = getCookie("callsign")) document.getElementById("callsign").value = callsign;
	if( operator = getCookie("operator")) document.getElementById("operator").value = operator;
	if( band = getCookie("band")) document.getElementById("band").value = band;
	if( mode = getCookie("mode")) document.getElementById("mode").value = mode;
}

// Validate entry forms
$('#callsign').on('input', function() {
    var input=$(this);
    var re = /^[a-zA-Z0-9\/]{2,9}[a-zA-Z]$/;
    var is_valid = re.test(input.val());
    if(is_valid){
        input.removeClass("is-invalid").addClass("is-valid");
    } else {
        input.removeClass("is-valid").addClass("is-invalid");
    }
});

$('#operator').on('input', function() {
    var input=$(this);
    var re = /^[a-zA-Z0-9\/]{2,9}[a-zA-Z]$/;
    var is_valid = re.test(input.val());
    if(is_valid){
        input.removeClass("is-invalid").addClass("is-valid");
    } else {
        input.removeClass("is-valid").addClass("is-invalid");
    }
});

//
// Help Pop-up
//
function showHelp() {
	window.open('help.html');
};
