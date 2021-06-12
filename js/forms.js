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

var sections = [
"CT", "EMA", "ME", "NH", "RI", "VT", "WMA", "ENY", "NLI", "NNJ", "NNY", "SNJ", "WNY", "DE", "EPA", "MDC", "WPA", "AL", "GA", "KY", "NC", "NFL", "SC", "SFL", "WCF", "TN", "VA", "PR", "VI", "AR", "LA", "MS", "NM", "NTX", "OK", "STX", "WTX", "EB", "LAX", "ORG", "SB", "SCV", "SDG", "SF", "SJV", "SV", "PAC", "AZ", "EWA", "ID", "MT", "NV", "OR", "UT", "WWA", "WY", "AK", "MI", "OH", "WV", "IL", "IN", "WI", "CO", "IA", "KS", "MN", "MO", "NE", "ND", "SD", "MAR", "NL", "PE", "QC", "ONE", "ONN", "ONS", "GTA", "MB", "SK", "AB", "BC", "NT", "DX" ]; 

$('#arrl-sections .typeahead').typeahead({
  hint: true,
  highlight: true,
  minLength: 1
},
{
  name: 'sections',
  source: substringMatcher(sections)
});

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

window.addEventListener("load", function(){
	updateLogTime();
	setInterval(updateLogTime,1000);
	});

//
// Field Validators
//

var submitOkCall = false;
var submitOkClass = false;
var submitOkSection = false;
var submitOkDupe = false;

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

function callCheckDupe() {
	var callf = document.getElementById("call");
	var dupe = isDupeQSO();
	if(dupe){
		submitOkDupe = false;	
		callf.classList.remove("is-valid");
		callf.classList.add("is-invalid");
	} else {
		submitOkDupe = true;
		callf.classList.remove("is-invalid");
		callf.classList.add("is-valid");
	}
}


$('#opclass').on('input', function() {
    var input=$(this);
    var re = /^[0-9]{1,2}[a-fA-F]$/;
    var is_valid = re.test(input.val());
    if(is_valid){
		submitOkClass = true;
        input.removeClass("is-invalid").addClass("is-valid");
    } else {
		submitOkClass= false;
        input.removeClass("is-valid").addClass("is-invalid");
    }
});

$('#section').on('input', function() {
    var input=$(this);
    if(sections.includes(input.val().toUpperCase())){
		submitOkSection = true;		
        input.removeClass("is-invalid").addClass("is-valid");
    } else {
		submitOkSection = false;
        input.removeClass("is-valid").addClass("is-invalid");
    }
});

$('#log').on('input', function() {
	if(checkSubmitOkStatus()){
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



//
// Submit LOG
//

function resetSubmitOkStatus() {
	submitOkCall = false;
	submitOkClass = false;
	submitOkSection = false;
	submitOkDupe = false;
	toggleLogButton(false);
}

function checkSubmitOkStatus() {
	if( submitOkCall && submitOkClass && submitOkSection ){ // && submitOkDupe )
		return true;
	} else {
		return false;
	}
}

function logSubmit() {

	if(!checkSubmitOkStatus()){
		console.log("Not ready to submit");
		return false;
	}
	console.log("Submit form");

	// Initialize the form
	var lform = document.getElementById("log");
	lform.method = "POST";
	lform.action = "#";

	// Generate and store the qkey hash 
	// Note this is useing murmurhash because it's not security-related
 	// also the seed value is irrelevant to anything

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
	var qkey = murmurhash3_32_rp( qsocall + opband + opmode, 17);
	console.log(qkey);
	document.getElementById("qkey").value = qkey;

	var fd = new FormData(lform);
	var fj = Object.fromEntries(fd.entries());

	$.ajax({
		type:	"post",
		url:	"api/storeqso.php",
		data:	fj,
		success: function(msg) {
			console.log(msg);
		},
		failure: function(msg) {
			console.log(msg);
		}
	});

	// Update the running log
	updateDisplayLog();
	
	// Note the action
	goodStatusMsg("Logged " + qsocall);

	// finally reset the log and start over
	logReset();
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
// Status Area Functions
// 
function goodStatusMsg(msg) {
	var statusbox = document.getElementById("statusarea");
	statusbox.classList.remove("invisible");
	statusbox.classList.add("bg-success");
	statusbox.innerHTML += "<p>" + msg + "</p>";
};

function clearStatusMsg(){
	var statusbox = document.getElementById("statusarea");
	statusbox.classList.remove("bg-success");
    statusbox.classList.add("invisible");
	statusbox.innerHTML = "";
}

function testMsg(){
	goodStatusMsg("This is a test!");
}

// 
// Other functions
//

function editQSO(qkey){
	alert("Function not implemented");
}

function delQSO(qkey){
	alert("Function not implemented");
}
