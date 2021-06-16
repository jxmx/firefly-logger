//
// Functions for editqso.php
//

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


