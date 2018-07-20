$(function() {
	$("#gt_search").on({
		click: function() {
			gt_search();
		}
	});
	$("#gametag").on({
		keyup: function(e) {
			if (e.keyCode == 13) {
				gt_search();
			}
		}
	});
});
function gt_search() {
	var g = $("#game").val();
	if (g == "fortnite") {
		var u = $("#gametag").val();
		var p = $("#platform").val();
		$.getJSON("../ajax/getGameInfo.php", {g: g, u: u, p: p})
		.done(function(json) {
			//console.log(json);
			if (!json.error) {
				var html = '<div>accountId: <span id="accountID">' + json.accountId.replace(/-/g, "") + '</span></div>';
				html += '<div>epicUserHandle: <span id="epicUserHandle">' + json.epicUserHandle + '</span></div>';
				html += '<div>platformName: <span id="platformName">' + json.platformNameLong + '</span></div>';
				html += '<div>Total wins: ' + json.lifeTimeStats[8].value + '</div>';
				if (json.claimed == 0) {
					html += '<div><button id="gt_claim">Claim user</button><span id="gt_claim_response" style="margin-left: 20px;"></span></div>';
					html += '<script src="../js/ajax/gt_claim.js"></script>';
				} else {
					html += '<div>User has been claimed.</div>';
				}
			} else {
				var html = '<div>Error: ' + json.error + '</div>';
			}
			$("#result").html(html);
		})
		.fail(function(jqxhr, textStatus, error) {
			var err = textStatus + ", " + error;
			console.log("Request Failed: " + err);
		});
	}
}