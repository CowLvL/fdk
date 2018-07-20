$(document).ready(function () {

	$(".sa-assign-duo-answer").hover(function () {
		$(this).text("Svar");
	},
	function() {
    	$(this).text("Inviteret");
	});

	$(".sa-assign-duo-status").hover(function () {
		$(this).text("Se status");
	},
	function() {
    	$(this).text("Afventer");	
	});

	$(".sa-assign-decline").hover(function () {
		$(this).text("Afmeld");
		$(this).removeClass("btn-success");
		$(this).addClass("btn-danger");
	},
	function() {
    	$(this).text("Tilmeldt");
		$(this).removeClass("btn-danger");	
		$(this).addClass("btn-success");
	});
});
