// For index.php / form id=log

// Add a custom rule called "callsign"
const callsignRegex = /^(?:[A-Z]{1,2}|[0-9][A-Z])\d{1,2}[A-Z]{1,4}$/i;

$(function () {
    $.validator.addMethod("callsign", function(value, element) {
        return this.optional(element) || callsignRegex.test(value);
    }, "Enter valid callsign");
});

$(function (){
    $.validator.addMethod("opclass", function(value, element) {
        if( config.general.fdType == "WFD"){
            re = /^[0-9]{1,2}[himoHIOM]$/;
        } else {
            re = /^[0-9]{1,2}[a-fA-F]$/;
        }
        return this.optional(element) || re.test(value);
    }, "Enter valid class");
});

$(function (){
    $.validator.addMethod("section", function(value, element) {
        let retval = false;
        if(config.sections.sections.includes(value.toUpperCase())){
            retval = true;
        }
        return this.optional(element) || retval;
    }, "Enter valid class");
});

$(document).ready(function () {
    $("#log").validate({
        //debug: true,
        rules: {
            "call": {
                required: true,
                callsign: true
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
});