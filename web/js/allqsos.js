function qsoButtons(value, row, index){
  return `
    <button class="btn btn-sm btn-secondary asl-node-btn edit-btn"
        data-bs-toggle="tooltip" title="edit QSO ${value}"
        role="button" id="edit_${value}" value="${value}">
        <i class="bi bi-pencil-square"></i>
    </button>
    <button class="btn btn-sm btn-secondary asl-node-btn del-btn"
        data-bs-toggle="tooltip" title="delete QSO ${value}"
        role="button" id="delete_${value}" value="${value}">
        <i class="bi bi-trash"></i>
    </button>
    `;
}

// Redirect to the user editor when clicking on a button
$(document).on('click', '.edit-btn', function() {
  var qkey = $(this).val();
  window.open('editqso.php?qkey=' + qkey, '_blank');
});

// Redirect to the user editor when clicking on a button
$(document).on('click', '.del-btn', function() {
  var qkey = $(this).val();
  delQSO(qkey);
});

// Activate tooltips
function initTooltips() {
document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
    const existing = bootstrap.Tooltip.getInstance(el);
    if (existing) existing.dispose();
    bootstrap.Tooltip.getOrCreateInstance(el, { container: 'body' });
});
}

// Run after table body is rendered
$('#table').on('post-body.bs.table', function () {
initTooltips();
});

// Delete from index.js
const APIPrefix="api";

function delQSO(qkey){
	if( confirm(`Are you sure your want to delete QSO ID# ${qkey}`)){
	    $.ajax({
	        type:   "GET",
	        url:    `${APIPrefix}/delqso.php?qkey=${qkey}`,
			cache: false,
	        success: function(output) {
                alert(`Deleted QSO ${qkey}`);
                window.location.reload(true);
	        },
			error: function(xhr, status, error) {
				let msg = "";
				if( xhr.status === 0){
					msg = "Network error. Cannot connect to server? Offline?";
				} else {
					msg = `AJAX error: ${status}, ${error}`;
				}
				$("#modal-ajax-error").text(msg);
				const modal = new bootstrap.Modal(document.getElementById("errorModal"));
				modal.show();
				stopTimers();
			}
	    });
	}
}