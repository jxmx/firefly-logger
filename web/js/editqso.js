//
// Functions for editqso.php
//

// top level configuration variables
// prefix for where the API is located on the webserver
var APIPrefix = "api";

// master configuration for the application itself

let config = {
	"bands": null,
	"modes": null,
	"sections": {
		"sections": [ "XX" ]
	}
};

async function getConfig(configType){
	var url = `${APIPrefix}/config_${configType}.json`;
	let response = await fetch(url, { cache : "no-store" } );
	if(response.ok){
		let a = await response.json();
		switch(configType){
			case "general":
				config.general = a;
				break;
			case "bands":
				config.bands = a;
				break;
			case "modes":
				config.modes = a;
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
// On the load
//
window.addEventListener("load", async function(){
	await getConfig("general");
	await getConfig("bands");
	await getConfig("modes");
	await getConfig("sections");
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

// Add a custom rule called "callsign"
const callsignRegex = /^(?:[A-Z]{1,2}|[0-9][A-Z])\d{1,2}[A-Z]{1,4}$/i;

// Validation function for the callsign
$(function () {
    $.validator.addMethod("callsign", function(value, element) {
        return this.optional(element) || callsignRegex.test(value);
    });
}, "Valid callsign required");

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
}, "Valid class required");

// Validation function for the section
$(function (){
    $.validator.addMethod("section", function(value, element) {
        let retval = false;
        if(config.sections.sections.includes(value.toUpperCase())){
            retval = true;
        }
        return this.optional(element) || retval;
    });
}, "Valid section required");

// Validation
$(function (){
	$.validator.addMethod("logclock", function (value, element) {
		if (this.optional(element)) return true;

		// If time is missing seconds, append :00
		// Matches YYYY-MM-DD HH:MM
		const noSeconds = /^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/;

		if (noSeconds.test(value)) {
			value = value + ":00";
			$(element).val(value);   // update the field so the form uses the normalized value
		}

		// Now validate full format YYYY-MM-DD HH:MM:SS
		const full = /^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/;
		if (!full.test(value)) return false;

		// Deep validation using Date()
		const [datePart, timePart] = value.split(" ");
		const [year, month, day] = datePart.split("-").map(Number);
		const [hour, minute, second] = timePart.split(":").map(Number);

		const dt = new Date(year, month - 1, day, hour, minute, second);

		return (
			dt.getFullYear() === year &&
			dt.getMonth() === month - 1 &&
			dt.getDate() === day &&
			dt.getHours() === hour &&
			dt.getMinutes() === minute &&
			dt.getSeconds() === second
		);
	}, "Invalid date/time format (YYYY-MM-DD HH:MM or YYYY-MM-DD HH:MM:SS)");
});

$(function () {
    $("#editqso").validate({
		//debug: true,
        rules: {
            callsign: {
				required: true,
				callsign: true
			},
            date:     {
				required: true,
				logclock: true
			},
            band:     {
				required: true,
				minlength: 2,
				maxlength: 4
			},
            class:    {
				required: true,
				opclass: true
			},
            mode:     {
				required: true,
				minlength: 2
			},
            station:  {
				required: true,
				callsign: true
			},
            section:  {
				required: true,
				section: true
			},
            operator: {
				required: true,
				callsign: true
			},
        },
        errorClass: "is-invalid",
        validClass: "is-valid",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            error.insertAfter(element);
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
			const callsign = $('#callsign').val();
			const band     = $('#band').val();
			const mode     = $('#mode').val();
			const qkey = qkeyCalculate(callsign, band, mode);
			$('#newqkey').val(qkey);
			submitEdit();
		}
    });
});

function submitEdit() {
	var f = new FormData(document.getElementById("editqso"));
	var fj = Object.fromEntries(f.entries());

	$.ajax({
		type:	"POST",
		url:	"api/updateqso.php",
		data:	fj,
		success:	function(output) { handleSubmitEdit(output); },
		failure:	function(output) { console.log(output); }
	});
}

function handleSubmitEdit(msg) {
    if( msg === "OK" ){
        goodStatusMsg(msg);
		setTimeout(window.close, 2000);
    } else {
        alertStatusMsg(msg);
    }
}

function abandonEdit() {
	window.close();
}


