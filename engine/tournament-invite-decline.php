<?php
session_start();
include "functions.php";
include "database.php";

$tournament_id = $_POST["tournament_id"];
$responder_id = getUserInfo(0, "id");

// get tournaments size
$tour_size = $pdo->prepare("SELECT type FROM tournaments WHERE id = ?");
$tour_size->execute([$tournament_id]);
$arr = $tour_size->fetch(PDO::FETCH_ASSOC);
if($arr["type"] == "duo") {
	$size = 2;
} elseif($arr["type"] == "squad") {
	$size = 4;
} elseif($arr["type"] == "solo") {
	$size = 1;
}

// get invite id
$tour_invite_id = $pdo->prepare("SELECT invite_id FROM tournament_invite WHERE tournament = ? AND invitee = ?");
$tour_invite_id->execute([
	$tournament_id,
	$responder_id
]);
$arr_invite_id = $tour_invite_id->fetch(PDO::FETCH_ASSOC);
$invite_id = $arr_invite_id["invite_id"];

// delete all invites with invite_id
$stmt_delete = $pdo->prepare("DELETE FROM tournament_invite WHERE invite_id = ?");
$stmt_delete->execute([
	$invite_id
]);
$stmt_delete = null;

// remove $size from the tournament
$stmt_update = $pdo->prepare("UPDATE tournaments SET signups = signups - ? WHERE id = ?");
$stmt_update->execute([
	$size,
	$tournament_id
]);
$stmt_update = null;
?>