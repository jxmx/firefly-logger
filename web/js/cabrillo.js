$(document).ready(function () {
    // Add a custom rule called "callsign"
    const callsignRegex = /^(?:[A-Z]{1,2}|[0-9][A-Z])\d{1,2}[A-Z]{1,4}$/i;

    // Validation function for the callsign
    $(function () {
        $.validator.addMethod("callsign", function(value, element) {
            return this.optional(element) || callsignRegex.test(value);
        }, "Valid callsign required");
    });

    $("#cabrillo").validate({
        rules: {
            cabclub:        { required: true, minlength:1, maxlength: 50 },
            cabcall:        { required: true, callsign: true },
            cabname:        { required: true, minlength:1, maxlength: 50 },
            cabemail:       { required: true, email: true, maxlength: 80 },
            cabstation:     { required: true, minlength:1, maxlength: 1 },
            cabtransmitter: { required: true, digits:true, maxlength: 3 },
            cabsection:     { required: true, maxlength: 3 },
            cabscore:       { required: true, digits: true },
            cabstreet:      { required: true, minlength:1,  maxlength: 50 },
            cabcity:        { required: true, minlength:1, maxlength: 40 },
            cabstate:       { required: true, minlength:1, maxlength: 20 },
            cabzip:         { required: true, minlength:1, maxlength: 15 },
            cabcountry:     { required: true, minlength:1, maxlength: 40 },
            cabcontest:     { required: true, minlength:1}
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
        }
    });

});
