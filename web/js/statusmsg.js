//
// Status Area Functions
//
function goodStatusMsg(msg) {
    var statusbox = document.getElementById("statusarea");
    clearStatusMsg();
    statusbox.classList.add("alert-success");
    statusbox.innerHTML += '<i class="bi bi-check-circle-fill my-2 msgbox-bi-bi"></i>';
    statusbox.innerHTML +=  `&nbsp;&nbsp${msg}`;
    statusbox.innerHTML += "<button type=\"button\" class=\"btn-close\" onclick=\"clearStatusMsg()\" aria-label=\"Close\"></button>";
    $("#statusarea").removeClass("opacity-0").addClass("opacity-100");
};

function decayingGoodStatusMsg(msg, secs) {
    setTimeout(clearStatusMsg, secs * 1000);
    goodStatusMsg(msg);
}

function alertStatusMsg(msg) {
    var statusbox = document.getElementById("statusarea");
    clearStatusMsg();
    statusbox.classList.add("alert-danger");
    statusbox.innerHTML += `<i class="bi bi-x-circle-fill msgbox-bi-bi"></i>`;
    statusbox.innerHTML += `&nbsp;&nbsp${msg}`;
    statusbox.innerHTML += "<button type=\"button\" class=\"btn-close\" onclick=\"clearStatusMsg()\" aria-label=\"Close\"></button>";
    $("#statusarea").removeClass("opacity-0").addClass("opacity-100");
};

function decayingAlertStatusMsg(msg, secs) {
    setTimeout(clearStatusMsg, secs * 1000);
    alertStatusMsg(msg);
}

function clearStatusMsg(){
    var statusbox = document.getElementById("statusarea");
    statusbox.classList.remove("alert-success", "alert-danger");
    statusbox.innerHTML = "";
    $("#statusarea").addClass("opacity-0").removeClass("opacity-100");
}

