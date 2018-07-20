<?php
session_start();
include "functions.php";
include "database.php";

$inviter = getUserInfo(0, "id");
$team = $_POST["team_id"];
$tournament = $_POST["tournament_id"];
$player = $_POST["player"];

$invite_id = $inviter . "_" . $tournament . "_" . time();

$status = 1;

foreach($player as $p) {
	// status: 0 = declined, 1 = not answered, 2 = accepted
	if($p == $inviter) {
		$status = 2;
	} else {
		$status = 1;
		$invitee = getUserInfo($p, "user_id");
	}
	$stmt = $pdo->prepare("INSERT INTO tournament_invite (invite_id, invitee, inviter, team, tournament, status) VALUES (?, ?, ?, ?, ?, ?)");
	$stmt->execute([
		$invite_id,
		$p,
		$inviter,
		$team,
		$tournament,
		$status
	]);
	$stmt = null;
}

echo "Du har inviteret " . $invitee . " til at detale i " . getTournamentInfo($tournament, "name") . " med " . getTeamInfo($team, "name");

//echo json_encode(array('player1' => $json_invitee, 'player2' => $json_team));

?>
