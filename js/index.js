//
// Things that might require configuration
//

// Add/edit/delete the ARRL/RAC sections from this array as things change
// Keep everything ordered by size (i.e. 2 char sections first, then 3 char sections) and then alphabetical
const sections = [ "AB","AK","AL","AR","AZ","BC","CO","CT","DE","DX","EB","GA","IA","ID","IL","IN","KS","KY","LA","MB","ME","MI","MN","MO","MS","MT","NC","ND","NE","NH","NL","NM","NT","NV","OH","OK","OR","PE","PR","QC","RI","SB","SC","SD","SF","SK","SV","TN","UT","VA","VI","VT","WI","WV","WY","EMA","ENY","EPA","EWA","GTA","LAX","MAR","MDC","NFL","NLI","NNJ","NNY","NTX","ONE","ONN","ONS","ORG","PAC","SCV","SDG","SFL","SJV","SNJ","STX","WCF","WMA","WNY","WPA","WTX","WWA" ]


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

// QSO Call
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


// Duplicate checking logic
// Note: this has to be async because of XHR and then call a different function to lock stuff out
function isDupeQSO() {
    var opband = document.getElementById("band").value;
    document.getElementById("opband").value = opband;

    var opmode = document.getElementById("mode").value;
    document.getElementById("opmode").value = opmode;

    var qsocall = document.getElementById("call").value;
    var qkey = parseFloat(murmurhash3_32_rp( qsocall + opband + opmode, 17));

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


$('#opclass').on('input', function() {
    var input=$(this);
	var re;

	if( document.getElementById("fdtype").value == "WFD"){
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

$('#section').on('input', function() {
    var input=$(this);
	document.getElementById("section").value = input.val().toUpperCase();
    if(sections.includes(input.val().toUpperCase())){
		submitOkSection = true;		
        input.removeClass("is-invalid").addClass("is-valid");
    } else {
		submitOkSection = false;
        input.removeClass("is-valid").addClass("is-invalid");
    }
});

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



//
// Submit LOG
//

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
	var qkey = parseFloat(murmurhash3_32_rp( qsocall + opband + opmode, 17));
	document.getElementById("qkey").value = qkey;

	var fd = new FormData(lform);
	var fj = Object.fromEntries(fd.entries());

	$.ajax({
		type:	"post",
		url:	"api/storeqso.php",
		data:	fj,
		success: function(msg) {
			decayingGoodStatusMsg("Stored QSO for " + qsocall + "!  (QKEY: " + qkey + " , API Resp: " + msg + ")", 3);
		},
		failure: function(msg) {
			alertStatusMsg("Failed to store QSO for " + qsocall + "! (QKEY: " + qkey + " , API Resp: " + msg + ")");
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

