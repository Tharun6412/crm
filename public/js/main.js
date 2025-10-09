/**
 * Main JS
 * Includes dynamic modals, AJAX default preloaders
 */
// Load Bootstrap Modal
function loadModal (data, width) {
	var modalId = "myModal";
	if ($("#myModal").length > 0) {
		//modalId = "subModal";
		unLoadModal();
	}
	var modalElement = '<div class="modal fade" id="' + modalId + '">' + data + '</div>';
	$("body").append(modalElement);
	var myModal = new bootstrap.Modal($("#"+modalId), {backdrop: 'static', keyboard: false });
	myModal.show();
	const myModalEl = document.getElementById('myModal')
	myModalEl.addEventListener('hidden.bs.modal', event => {
		setTimeout(() => {
			unLoadModal();
		}, 300);
	})
}
// Unload Bootstrap Modal
function unLoadModal () {
	if ($("#subModal").length > 0) {
		$("#subModal").remove();
	}
	else {
		$("#myModal").remove();
		$(".modal-backdrop").remove();
		$("body").css("overflow", "auto");
	}
}
// Pre Loader
function preLoader() {
	var pre_loader = '<div class="spinner"><div class="pre-loader-position"><span class="loader-icon loader-logo"><span class="pre-loader"></span></span></div></div>';
	$('body').append(pre_loader);
	$('body').css('pointer-events', 'none');
}
// Close Pre Loader
function closePreLoader() {
	$('.spinner').remove();
	$('body').css('pointer-events', 'visible');
}
$(function(){
	// Loader for every Ajax request
	$.ajaxSetup({
		beforeSend: function() {
			preLoader();
		},
		complete: function() {
			closePreLoader();
		}
	});
})