<?PHP
	ini_set('display_errors', 1); error_reporting(E_ALL);
	include "engine/database.php";
	$stmt = $pdo->prepare("SELECT * FROM stats_update ORDER BY id LIMIT 1");
	$stmt->execute();
	// if queue isn't empty
	if ($row = $stmt->fetch()) {
		$id = $row['id'];
		$gid = $row['gid'];
		$window = $row['window'];
		$unix = $row['unix'];
		// fetch gametag and info
		$stmt = $pdo->prepare("SELECT * FROM game_tags WHERE id = ?");
		$stmt->execute([$gid]);
		$row = $stmt->fetch();
		$game = $row['gid'];
		$pid = $row['pid'];
		$aid = $row['aid'];
		$name = $row['name'];
		// fetch platform
		$stmt = $pdo->prepare("SELECT * FROM platforms WHERE id = ?");
		$stmt->execute([$pid]);
		$row = $stmt->fetch();
		$platform = $row['tag'];
		// if fortnite
		if ($game == 1) {
			$stmt = $pdo->prepare("SELECT * FROM settings WHERE name = ?");
			$stmt->execute(["fortniteapi"]);
			$row = $stmt->fetch();
			$apikey = $row['content'];
			include("fortnite-api/Autoloader.php");
			$api = new FortniteClient;
			$api->setKey($apikey);
			$api->user->uid = $aid;
			$data = $api->user->stats($platform, $window);
			//print_r($data);
			//echo "<br /><br />";
			if ($aid != str_replace("-", "", $data->uid)) {
				$stmt = $pdo->prepare("UPDATE game_tags SET outdated = 1 WHERE id = ?");
				$stmt->execute([$gid]);
				//echo "outdated";
			} else {
				// prepare sql
				$ent = array(
					"username" => "",
					"kills_solo" => "stats",
					"placetop1_solo" => "stats",
					"placetop10_solo" => "stats",
					"placetop25_solo" => "stats",
					"matchesplayed_solo" => "stats",
					"kd_solo" => "stats",
					"winrate_solo" => "stats",
					"score_solo" => "stats",
					"minutesplayed_solo" => "stats",
					"kpg_solo" => "stats",
					"lastmodified_solo" => "stats",
					"kills_duo" => "stats",
					"placetop1_duo" => "stats",
					"placetop5_duo" => "stats",
					"placetop12_duo" => "stats",
					"matchesplayed_duo" => "stats",
					"kd_duo" => "stats",
					"winrate_duo" => "stats",
					"score_duo" => "stats",
					"minutesplayed_duo" => "stats",
					"kpg_duo" => "stats",
					"lastmodified_duo" => "stats",
					"kills_squad" => "stats",
					"placetop1_squad" => "stats",
					"placetop3_squad" => "stats",
					"placetop6_squad" => "stats",
					"matchesplayed_squad" => "stats",
					"kd_squad" => "stats",
					"winrate_squad" => "stats",
					"score_squad" => "stats",
					"minutesplayed_squad" => "stats",
					"kpg_squad" => "stats",
					"lastmodified_squad" => "stats",
					"kills" => "totals",
					"wins" => "totals",
					"matchesplayed" => "totals",
					"minutesplayed" => "totals",
					"hoursplayed" => "totals",
					"score" => "totals",
					"winrate" => "totals",
					"kd" => "totals",
					"lastupdate" => "totals"
				);
				$sql = "UPDATE `stats_fortnite` SET ";
				foreach ($ent as $key => $val) {
					if ($key == "kpg_duo") {
						$kills_duo = ($data->stats->matchesplayed_duo != 0) ? $data->stats->kills_duo/$data->stats->matchesplayed_duo : 0;
						$kills_duo = ($kills_duo != 0) ? number_format($kills_duo, 2, '.', '') : 0;
						$sql .= "`kpg_duo` = '".$kills_duo."', ";
					} elseif ($key == "kpg_solo") {
						$kills_solo = ($data->stats->matchesplayed_solo != 0) ? $data->stats->kills_solo/$data->stats->matchesplayed_solo : 0;
						$kills_solo = ($kills_solo != 0) ? number_format($kills_solo, 2, '.', '') : 0;
						$sql .= "`kpg_solo` = '".$kills_solo."', ";
					} elseif ($key == "kpg_squad") {
						$kills_squad = ($data->stats->matchesplayed_squad != 0) ? $data->stats->kills_squad/$data->stats->matchesplayed_squad : 0;
						$kills_squad = ($kills_squad != 0) ? number_format($kills_squad, 2, '.', '') : 0;
						$sql .= "`kpg_squad` = '".$kills_squad."', ";
					} else {
						$sql .= ($val != "") ? "`".$key."` = '".$data->$val->$key."', " : "`".$key."` = '".$data->$key."', ";
					}
				}
				$sql .= "`unix` = '".date("U")."' WHERE `gid` = '".$gid."' AND `window` = '".$window."'";

				// query sql
				$stmt = $pdo->query($sql);
				//echo "done";
			}
		} else {
			//echo "error";
		}
		// query sql
		$stmt = $pdo->query("DELETE FROM `stats_update` WHERE `id` = '".$id."'");
	}
	$stmt = null;
?>