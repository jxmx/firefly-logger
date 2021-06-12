//
// Display QSOs
//	

// update function
function updateDisplayLog(){
	$.ajax({
		type: "get",
		url: "api/displaylog.php",
		success: function(output) {
			$("#logdisplay").html(output);
		},
		failure: function(output) {
			$("#logdisplay").html(output);
		}
	});
}

// set the timer
window.addEventListener("load", function(){
	updateDisplayLog();
	setInterval(updateDisplayLog, 15000);
});


//
// Duplicate checking logic
//
function isDupeQSO() {
    var opband = document.getElementById("band").value;
    document.getElementById("opband").value = opband;

    var opmode = document.getElementById("mode").value;
    document.getElementById("opmode").value = opmode;

    var qsocall = document.getElementById("call").value;
    var qkey = murmurhash3_32_rp( qsocall + opband + opmode, 17);

	$.ajax({
		type:	"POST",
		url:	"api/checkdupe.php",
		data:	{ qkey: qkey },
		success: function(output) {
			console.log(output);
			if( output === "DUPE" ){
				return true;
			} else if( output === "OK" ){
			return false;
			} else {
				console.log(output);
				return true;
			}
		},
		failure: function(msg)  {
			console.log(msg);
		}
	});

}

