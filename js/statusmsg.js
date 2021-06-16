//
// Status Area Functions
//
function goodStatusMsg(msg) {
    var statusbox = document.getElementById("statusarea");
    clearStatusMsg();
    statusbox.classList.remove("invisible");
    statusbox.classList.add("alert-success");
    statusbox.innerHTML += "<svg class=\"bi flex-shrink-0 me-2\" width=\"24\" height=\"24\" role=\"img\" aria-label=\"Success:\"><use xlink:href=\"#check-circle-fill\"/></svg>";
    statusbox.innerHTML += "<div class=\"d-inline-flex\">" + msg + "</div>";
    statusbox.innerHTML += "<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>";
};

function decayingGoodStatusMsg(msg, secs) {
    setTimeout(clearStatusMsg, secs * 1000);
    goodStatusMsg(msg);
}

function alertStatusMsg(msg) {
    var statusbox = document.getElementById("statusarea");
    clearStatusMsg();
    statusbox.classList.remove("invisible");
    statusbox.classList.add("alert-danger");
    statusbox.innerHTML += "<svg class=\"bi flex-shrink-0 me-2\" width=\"24\" height=\"24\" role=\"img\" aria-label=\"Success:\"><use xlink:href=\"#exclamation-triangle-fill\"/></svg>";
    statusbox.innerHTML += "<div class=\"d-inline-flex\">" + msg + "</div>";
    statusbox.innerHTML += "<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>";
};

function decayingAlertStatusMsg(msg, secs) {
    setTimeout(clearStatusMsg, secs * 1000);
    alertStatusMsg(msg);
}

function clearStatusMsg(){
    var statusbox = document.getElementById("statusarea");
    statusbox.classList.remove("alert-success", "alert-danger");
    statusbox.classList.add("invisible");
    statusbox.innerHTML = "";
}

