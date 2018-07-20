<?PHP
	session_start();
	$action = (isset($_POST['action'])) ? $_POST['action'] : "";
	$gametag = (isset($_POST['gametag'])) ? $_POST['gametag'] : "";
	if ($action != "" && $gametag != "") {
		include "../engine/database.php";
		$aid = (isset($_POST['aid'])) ? $_POST['aid'] : "";
		$game = (isset($_POST['game'])) ? $_POST['game'] : 1;
		$platform = (isset($_POST['platform'])) ? $_POST['platform'] : 1;
		$stmt = $pdo->prepare("SELECT * FROM platforms WHERE tag = ?");
		$stmt->execute([$platform]);
		$row = $stmt->fetch();
		$pid = $row['id'];
		if ($game == 1) {
			if ($action == "check") {
				$stmt = $pdo->prepare("SELECT * FROM settings WHERE name = ?");
				$stmt->execute(["fortniteapi"]);
				$row = $stmt->fetch();
				$apikey = $row['content'];
				// check fortniteapi
				include("../fortnite-api/Autoloader.php");
				$api = new FortniteClient;
				$api->setKey($apikey);
				$api->user->id($gametag);
				$data = $api->user->stats($platform, "alltime");
				//var_dump($data);
				if ($data != "Invalid user id.") {
					$aid = $data->uid;
					$stmt = $pdo->prepare("SELECT * FROM game_tags WHERE uid = ? AND pid = ?");
					$stmt->execute([$uid, $pid]);
					$row = $stmt->fetch();
					if ($stmt->rowCount() > 0) {
						echo 1;
					} else {
						$stmt = $pdo->prepare("SELECT * FROM game_tags WHERE aid = ? AND pid = ?");
						$stmt->execute([$aid, $pid]);
						echo ($stmt->rowCount() > 0) ? 2 : $aid;
					}
				} else {
					echo "invalid";
				}
			} elseif ($action == "claim") {
				$stmt = $pdo->prepare("SELECT * FROM settings WHERE name = ?");
				$stmt->execute(["fortnitetracker"]);
				$row = $stmt->fetch();
				$apikey = $row['content'];
				// check fortnite tracker
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "https://api.fortnitetracker.com/v1/profile/".$platform."/".$gametag);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($ch, CURLOPT_HEADER, FALSE);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'TRN-Api-Key: '.$apikey
				));
				$response = json_decode(curl_exec($ch));
				curl_close($ch);
				$gametag = (isset($response->epicUserHandle)) ? $response->epicUserHandle : $gametag;
				include "../engine/database.php";
				$dateU = date("U");
				$uid = $_SESSION['userData']['uid'];
				$stmt = $pdo->prepare("INSERT INTO game_tags (uid, gid, pid, aid, name) VALUES (?, ?, ?, ?, ?)");
				$stmt->execute([
					$uid,
					$game,
					$pid,
					$aid,
					$gametag
				]);
				$id = $pdo->lastInsertId();
				$stmt = $pdo->prepare("INSERT INTO stats_fortnite (uid, gid, window, unix) VALUES (?, ?, ?, ?), (?, ?, ?, ?)");
				$stmt->execute([
					$uid,
					$id,
					"alltime",
					$dateU,
					$uid,
					$id,
					"season5",
					$dateU
				]);
				$stmt = $pdo->prepare("INSERT INTO stats_update (gid, window, unix) VALUES (?, ?, ?), (?, ?, ?)");
				$stmt->execute([
					$id,
					"alltime",
					$dateU,
					$id,
					"season5",
					$dateU
				]);
				echo "claimed";
			}
		}
		$stmt = null;
	}
?>