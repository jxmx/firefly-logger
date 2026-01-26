//
// For board.html
//

// fields to populate
const fields = [ "total" , "phone" , "cw" , "data"  , "sections" , "distinct", "score" ];

// timers
const timers = [];

function startTimers() {
    timers.push(setInterval(doFieldUpdates, 15000));
}

function stopTimers() {
    for (const id of timers) {
        clearInterval(id);
        clearTimeout(id);
    }
    timers.length = 0;
}

// load / timer
window.addEventListener("load", function(){
	doFieldUpdates();
	startTimers();
});


// function to do the iterations
function doFieldUpdates(){
	fields.forEach(getFieldData);
}

// Ajaxification
function getFieldData(field){
	$.ajax({
		type:   "get",
		url:    "api/board.php?q=" + field,
		success: function(data) {
			if(data.ok){
				updateFieldData(field, data.ok);
			} else {
				updateFieldData(field, data.error);
			}
		},
		error: function(xhr, status, error) {
			msg = `AJAX error: ${status}, ${error}`;
			$("#modal-ajax-error").text(msg);
			msg = `XHR reponse: ${xhr.responseText}`;
			$("#modal-xhr-error").text(msg);
			const modal = new bootstrap.Modal(document.getElementById("errorModal"));
			modal.show();
			stopTimers();
			logdisplay_stopTimers();
		}
    });
}

// abstrat this for no real reason
function updateFieldData(field, value) {
	document.getElementById(field).innerHTML = value;
}


