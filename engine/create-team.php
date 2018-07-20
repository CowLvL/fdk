<?php
session_start();
include "functions.php";
include "database.php";

$user_id = strtolower(getUserInfo(0, "id", 0));
$name_id = strtolower(str_replace(" ", "-", htmlentities($_POST["name"])));
$name = htmlentities($_POST["name"]);
$captain_id = $user_id;
$game = "fortnite";

// insert into team
$stmt = $pdo->prepare("INSERT INTO team (name_id, name, captain_id, game, picture) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([
	$name_id,
	$name,
	$captain_id,
	$game, 
	"/images/avatar/team/no-image.png"
]);

$team_id = $pdo->lastInsertId();

$stmt = null;

// insert into team
$stmt = $pdo->prepare("INSERT INTO team_assoc (user_id, team_id) VALUES (?, ?)");
$stmt->execute([
	$user_id,
	$team_id
]);
$stmt = null;

echo $name_id;

//header('Location: ../team/' . $team_id);
?>
