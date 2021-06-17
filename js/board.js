//
// For board.html
//

// fields to populate
const fields = [ "total" , "phone" , "cw" , "digital"  , "sections" , "distinct", "score" ];

// load / timer
window.addEventListener("load", function(){
	doFieldUpdates();
	setInterval(doFieldUpdates, 15000);
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
		success: function(data) { updateFieldData(field, data); },
		failure: function(msg) { console.log(msg); }
    });
}

// abstrat this for no real reason 
function updateFieldData(field, value) {
	document.getElementById(field).innerHTML = value;
}


