<?php
session_start();
include "functions.php";
include "database.php";

$response = $_POST["response"];
$tournament_id = $_POST["tournament_id"];
$invite_id = $_POST["invite_id"];
$responder_id = getUserInfo(0, "id");

// get tournaments size
$tour_size = $pdo->prepare("SELECT type FROM tournaments WHERE id = ?");
$tour_size->execute([$tournament_id]);
$arr = $tour_size->fetch(PDO::FETCH_ASSOC);
if($arr["type"] == "duo") {
	$size = 2;
} elseif($arr["type"] == "squad") {
	$size = 4;
}

if($response == "accept") {

	// get tournaments signups to check if there is room
	$tour_signup_check = $pdo->prepare("SELECT signups FROM tournaments WHERE id = ?");
	$tour_signup_check->execute([$tournament_id]);
	$arr = $tour_signup_check->fetch(PDO::FETCH_ASSOC);

	if(($size == 2 && $arr["signups"] < 98) || ($size == 4 && $arr["signups"] < 96)) {

		// accept invite by setting status to 2
		$stmt = $pdo->prepare("UPDATE tournament_invite SET status = ? WHERE invite_id = ? AND invitee = ?");
		$stmt->execute([
			2,
			$invite_id,
			$responder_id
		]);
		$stmt = null;

		// check if all have accepted
		$stmt2 = $pdo->prepare("SELECT * FROM tournament_invite WHERE invite_id = ? AND status = ?");
		$stmt2->execute([
			$invite_id,
			2
		]);
		if($stmt2->rowCount() == $size) {
			// everyone have acepted the invite - delete all other invites the users have to this tournament

			// set all invite status to 3
			$stmt_done = $pdo->prepare("UPDATE tournament_invite SET status = ? WHERE invite_id = ?");
			$stmt_done->execute([
				3,
				$invite_id
			]);
			$stmt_done = null;


			// select all the lines (users) from this invite id
			$stmt3 = $pdo->prepare("SELECT * FROM tournament_invite WHERE invitee = ? AND tournament = ?");
			$stmt3->execute([$responder_id, $tournament_id]);
			while ($row = $stmt3->fetch()) {
				// delete all the users other invites to this tournament
				$stmt4 = $pdo->prepare("DELETE FROM tournament_invite WHERE invite_id = ? AND invite_id != ?");
				$stmt4->execute([
					$row["invite_id"],
					$invite_id
				]);
				$stmt4 = null;
			}
			$stmt3 = null;

			// update tournament signups
			$stmt5 = $pdo->prepare("UPDATE tournaments SET signups = signups + ? WHERE id = ?");
			$stmt5->execute([
				$size,
				$tournament_id
			]);
			$stmt5 = null;


		} else {
			// everyone have not accepted - delete all the users other invites to this tournament
			$stmt3 = $pdo->prepare("DELETE FROM tournament_invite WHERE (invitee = ? OR inviter = ?) AND tournament = ? AND invite_id != ?");
			$stmt3->execute([
				$responder_id,
				$responder_id,
				$tournament_id,
				$invite_id
			]);
			$stmt3 = null;
		}
		$stmt2 = null;

		echo "accepted";

	} else {

		// tournemant is full, delete all invites
		$stmt_full = $pdo->prepare("DELETE FROM tournament_invite WHERE tournament = ? AND status != ?");
		$stmt_full->execute([
			$tournament_id,
			3
		]);
		$stmt_full = null;

		echo "full";
	}

} elseif($response == "decline") {

	$stmt_decline = $pdo->prepare("DELETE FROM tournament_invite WHERE invite_id = ?");
	$stmt_decline->execute([
		$invite_id
	]);
	$stmt_decline = null;

	echo "declined";

}

?>
