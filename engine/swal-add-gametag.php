<?php
?>
<script type="text/javascript">
	$(function() {
		"use strict";
		var SweetAlert = function() {};

		$("#agt_search").on({
			click: function() {
				console.log($("#agt_gametag").val());
			}
		});
		$("#agt_gametag").on({
			keyup: function(e) {
				if (e.keyCode == 13) {
					console.log($("#agt_gametag").val());
				}
			}
		});
		//examples
		SweetAlert.prototype.init = function(e) {
			if (e != "") {
				console.log(e);
			}
			var createcontent = 	'<div class="box-body">' +
									'<div class="form-group" id="swal-add-gametag">' +
									'<span>Gametag</span>' +
									//'<label class="control-label" id="game_label"></label>' +
									//'<select id="agt_game" class="form-control game">' +
									//'<?PHP getGames(); ?>' +
									//'</select>' +
									'<label class="control-label" id="platform_label"></label>' +
									'<select id="agt_platform" class="form-control platform">' +
									'<?PHP getPlatforms(); ?>' +
									'</select>' +
									'<label class="control-label" id="gametag_label"></label>' +
									'<input type="text" id="agt_gametag" id="" class="form-control gametag" placeholder="Gametag">' +
									'</div>';
			if (e != "") {
				createcontent += 	'<div>' + e + '</div>';
			}
			createcontent += 		'</div>';
			swal({
				title: 'Tilfør gametag',
				html: createcontent,
				allowOutsideClick: false,
				showCancelButton: false,
				focusConfirm: true,
				reverseButtons: true,
				confirmButtonText: 'Søg',
				allowEnterKey: true
			}).then((result) => {
				//console.log(result);
				if (result.value) {
					if ($("#agt_gametag").val() != "") {
						//console.log(result.value);
						//var game = $("#agt_game").val();
						var platform = $("#agt_platform").val();
						var gametag = $("#agt_gametag").val();
						var data =	{action: "check", platform: platform, gametag: gametag};
						$.ajax({
							type: "POST",
							url: "/ajax/add_gametag.php",
							data: data,
							success: function(r) {
								$("#agt_result").text(r);
								if (r == "invalid") {
									$.SweetAlert.init("Invalid user id.");
								} else if (r == 1) {
									$.SweetAlert.init("Only 1 gametag per platform.");
								} else if (r == 2) {
									$.SweetAlert.init("Gametag already claimed.");
								} else {
									var createcontent1 = 	'<div class="box-body">' +
															'<div class="form-group" id="swal-add-gametag">' +
															'<span>Vil du gemme dette gametag?</span><br /><br />' +
															'<span>Gametag: ' + gametag + '</span><br />' +
															'<span>Platform: ' + platform + '</span>' +
															'</div>' +
															'</div>';
									swal({
										title: 'Godkend gametag',
										html: createcontent1,
										showCancelButton: true,
										focusConfirm: true,
										reverseButtons: true,
										confirmButtonText: 'Gem',
										cancelButtonText: 'Tilbage',
										allowEnterKey: true
									}).then((result1) => {
										if (result1.value) {
											var data1 = {action: "claim", platform: platform, gametag: gametag, aid: r};
											$.ajax({
												type: "POST",
												url: "/ajax/add_gametag.php",
												data: data1,
												success: function(r1) {
													if (r1 == "claimed") {
														var createcontent2 = 	'<div class="box-body">' +
																				'<div class="form-group" id="swal-add-gametag">' +
																				'<span>Gemt!</span>' +
																				'</div>' +
																				'</div>';
														swal({
															title: 'Godkend gametag',
															html: createcontent2,
															showCancelButton: false,
															focusConfirm: true,
															reverseButtons: true,
															confirmButtonText: 'Luk',
															allowEnterKey: true
														}).then((result1) => {
														});
													} else {
														$.SweetAlert.init("Error claiming gametag.");
													}
												}
											});
										} else {
											$.SweetAlert.init("");
											console.log(result.dismiss);
										}
									});
								}
							}
						});
					} else {
						$.SweetAlert.init("Gametag can't be empty.");
					}
				}
			});
			//swal.disableConfirmButton();
		},
		//init
		$.SweetAlert = new SweetAlert, $.SweetAlert.Constructor = SweetAlert;
	});
	//initializing 
	$(function() {
		"use strict";
		$.SweetAlert.init("1");
	});
</script>
