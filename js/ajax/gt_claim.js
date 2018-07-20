$(function() {
	$("#gt_claim").on({
		click: function() {
			var aid = $("#accountID").text();
			var name = $("#epicUserHandle").text();
			var platform = $("#platformName").text();
			$.ajax({
				method: "POST",
				url: "../ajax/gt_claim.php",
				data: {aid: aid, name: name, platform: platform}
			})
			.done(function(msg) {
				$("#gt_claim_response").text(msg);
			});
		}
	});
});