<?php

?>
<script type="text/javascript">
	$(function() {
		"use strict";
		var SweetAlert = function() {};
		var createcontent = '<div class="box-body">' +
								'<div id="swal-show-tos">' +
								'<span>Terms of Service</span>' +
								'</div>' +
								'</div>';
		//examples
		SweetAlert.prototype.init = function() {
			// show tos
			$('.show-tos').click(function(){
				swal({
					title: 'Vilkår',
					html: createcontent,
					showCancelButton: false,
					focusConfirm: true,
					reverseButtons: true,
					confirmButtonText: 'Luk',
					cancelButtonText: 'Annuller',
				}).then((result) => {
					if (result.value) {
						console.log(result.value);
						/*$.ajax({
							type: "POST",
							url: "/ajax/show_tos_result.php",
							success: function(response) {
								console.log(response);
							}
						});*/  
					}
					if (result.dismiss) {
						console.log(result.dismiss);
					}
				});
				//swal.disableConfirmButton()
			});
		},
		//init
		$.SweetAlert = new SweetAlert, $.SweetAlert.Constructor = SweetAlert
	}),
	//initializing 
	$(function() {
		"use strict";
		$.SweetAlert.init()
	});
</script>
