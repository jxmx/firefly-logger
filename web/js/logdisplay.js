//
// Display QSOs
//

// update function
function updateDisplayLog(hide){

	var path = window.location.pathname;
	var page = path.split("/").pop();
	const hpages = [ "board.php" ];

	if( hpages.includes(page) ){
		var qsa = "?hidebuttons=y";
	} else {
		var qsa = "";
	}

    $.ajax({
        type: "get",
        url: "api/displaylog.php" + qsa,
        success: function(output) {
            $("#logdisplay").html(output);
        },
        failure: function(output) {
            $("#logdisplay").html(output);
        }
    });
}

const logdisplay_timers = [];

function logdisplay_startTimers() {
    logdisplay_timers.push(setInterval(updateDisplayLog, 15000));
}

function logdisplay_stopTimers() {
    for (const id of logdisplay_timers) {
        clearInterval(id);
        clearTimeout(id);
    }
    logdisplay_timers.length = 0;
}


// set the timer
window.addEventListener("load", function(){
    updateDisplayLog();
    logdisplay_startTimers();
});

// refresh the QSOs when the window comes back into focus (e.g. after edit)
window.addEventListener("focus", function(){
    updateDisplayLog();
});

