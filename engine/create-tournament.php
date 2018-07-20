<?php
session_start();
include "functions.php";
include "database.php";

$id_long = dechex(time());
$matchmaking_key = dechex(time());

if($_POST["name"] != "") {
	// name is set
	$name = htmlentities($_POST["name"]);
} else {
	// name is not set
	include "database.php";

	$name = "Fortnite Danmark #%";

	$stmt = $pdo->prepare("SELECT name FROM tournaments WHERE name LIKE ? ORDER BY id DESC LIMIT 1");

	$stmt->execute([$name]);

	$arr = $stmt->fetch(PDO::FETCH_ASSOC);

	$pieces = explode("#", $arr["name"]);

	$num = $pieces[1] + 1;

	$name = "Fortnite Danmark #" . $num;

	$stmt = null;
}

$manager = getUserInfo(0, "user_id", 0);
$sponsor = htmlentities($_POST["sponsor"]);
if($sponsor == "") {
	$sponsor = "Fortnite Danmark";
}

$sponsor_www = htmlentities($_POST["sponsor_www"]);
$description = htmlentities("");
$fee = $_POST["fee"];
$price = $_POST["prize"];
$prize_type = "credits";
$currency = "DKK";
$game = "fortnite";
$platform = htmlentities($_POST["platform"]);
$type = htmlentities($_POST["type"]);
$invite = htmlentities($_POST["invite"]);

if(isset($_POST["tour_advanced"])) {
	// advanced is checked

	$deadline_date = explode("/", $_POST["deadline_date"]);
	$deadline_time = explode(":", $_POST["deadline_time"]);
	// mktime(hour, minute, second, month, day, year);
	$deadline_date_db = mktime($deadline_time[0], $deadline_time[1], 0, $deadline_date[1], $deadline_date[0], $deadline_date[2]);

	$publish_date = explode("/", $_POST["publish_date"]);
	$publish_time = explode(":", $_POST["publish_time"]);
	// mktime(hour, minute, second, month, day, year);
	$publish_date_db = mktime($publish_time[0], $publish_time[1], 0, $publish_date[1], $publish_date[0], $publish_date[2]);

	$signup_date = explode("/", $_POST["signup_date"]);
	$signup_time = explode(":", $_POST["signup_time"]);
	// mktime(hour, minute, second, month, day, year);
	$signup_date_db = mktime($signup_time[0], $signup_time[1], 0, $signup_date[1], $signup_date[0], $signup_date[2]);
	
} else {
	// advanced is not checked

	// set default deadline (start_date - 30 mimnutes)
	$deadline_date = explode("/", $_POST["deadline_date"]);
	$deadline_time = explode(":", $_POST["deadline_time"]);
	// mktime(hour, minute, second, month, day, year);
	$deadline_date_db = mktime($deadline_time[0], $deadline_time[1], 0, $deadline_date[1], $deadline_date[0], $deadline_date[2]);
	$deadline_date_db = $deadline_date_db - 1800;

	$publish_date_db = 0;
	$signup_date_db = 0;
}

$start_date = explode("/", $_POST["start_date"]);
$start_time = explode(":", $_POST["start_time"]);

// mktime(hour, minute, second, month, day, year);
$start_date_db = mktime($start_time[0], $start_time[1], 0, $start_date[1], $start_date[0], $start_date[2]);

if($_FILES['file']['error'] == 0) {
	//Check if the file is well uploaded
	if($_FILES['file']['error'] > 0) { echo 'Error during uploading, try again'; }

	//We won't use $_FILES['file']['type'] to check the file extension for security purpose

	//Set up valid image extensions
	$extsAllowed = array('jpg', 'jpeg', 'png', 'gif');
		
	$extUpload = strtolower(substr(strrchr($_FILES['file']['name'], '.'), 1)) ;
	//Check if the uploaded file extension is allowed
	if (in_array($extUpload, $extsAllowed) ) { 
		//Upload the file on the server
		$card = "../images/card/" . time()  . "_{$_FILES['file']['name']}";
		move_uploaded_file($_FILES['file']['tmp_name'], $card);
	} else {
		echo 'File is not valid. Please try again - ' . $_FILES['file']['name'];
	}
} else {
	$card = "";
}

$stmt = $pdo->prepare("INSERT INTO tournaments (id_long, matchmaking_key, name, manager, sponsor, sponsor_www, description, fee, prize, prize_type, currency, game, platform, type, invite, start_date, publish_date, signup_date, deadline_date, signups, max_signups, custom_rules, card) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([
	$id_long, // id long
	$matchmaking_key, // matchmaking key
	$name, // name
	$manager, // manager
	$sponsor, // sponsor
	$sponsor_www, // sponsor www
	$description, // description
	$fee, // fee
	$price, // price
	$prize_type, // prize type
	$currency, // currency
	$game, // game
	$platform, // platform
	$type, // type
	$invite, // invite
	$start_date_db, // start date - tournament start - send out all info
	$start_date_db, // publish date - you can now see the tournament
	$start_date_db, // signup date - you can now signup for the tournament
	$deadline_date_db, // deadline date - you can no longer signup for the tournament
	0, // signups
	100, // max signups
	"", // custom rules
	$card // card
]);
$stmt = null;

echo $id_long;

?>