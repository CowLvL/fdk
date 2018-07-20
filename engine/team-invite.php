<?php
session_start();
include "functions.php";
include "database.php";

$invitee = $_POST["name"];
$invitee = str_replace("#","", $invitee);
$invitee = getUserInfo($invitee, "id");

$inviter = getUserInfo(0, "id", 0);
$team = $_POST["team"];

// insert into team
$stmt = $pdo->prepare("INSERT INTO team_invite (invitee, inviter, team) VALUES (?, ?, ?)");
$stmt->execute([
	$invitee,
	$inviter,
	$team
]);
$stmt = null;

$json_invitee = getUserInfo($invitee, "user_id");
$json_team = getTeamInfo($team, "name");

echo json_encode(array('invitee' => $json_invitee, 'team' => $json_team));
?>
