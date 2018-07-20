<?php
session_start();
include "functions.php";
include "database.php";

$response = $_POST["response"];
$team_id = $_POST["team_id"];
$responder_id = getUserInfo(0, "id");

if($response == "accept") {
	$stmt = $pdo->prepare("INSERT IGNORE INTO team_assoc (user_id, team_id) VALUES (?, ?)");
	$stmt->execute([
		$responder_id,
		$team_id
	]);
	$stmt = null;
}

$stmt = $pdo->prepare("DELETE FROM team_invite WHERE invitee = ? AND team = ?");
$stmt->execute([
	$responder_id,
	$team_id
]);
$stmt = null;

echo $team_id;
?>
