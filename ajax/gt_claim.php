<?PHP
	session_start();
	$aid = $_POST['aid'];
	$name = $_POST['name'];
	$platform = $_POST['platform'];
	if ($aid != "") {
		$uid = $_SESSION['userData']['uid'];
		include "../engine/database.php";
		$stmt = $pdo->prepare("SELECT * FROM game_tags WHERE aid = ?");
		$stmt->execute([$aid]);
		if ($stmt->rowCount() > 0) {
			echo "ERROR: Account already claimed";
		} else {
			$stmt = $pdo->prepare("SELECT * FROM platforms WHERE platform = ?");
			$stmt->execute([$platform]);
			$row = $stmt->fetch();
			$pid = $row['id'];
			$stmt = $pdo->prepare("SELECT * FROM game_tags WHERE uid = ? AND pid = ?");
			$stmt->execute([$uid, $pid]);
			$row = $stmt->fetch();
			if ($stmt->rowCount() > 0) {
				echo "ERROR: Only one account per platform";
			} else {
				$dateU = date("U");
				$stmt = $pdo->prepare("INSERT INTO game_tags (uid, gid, pid, aid, name) VALUES (?, ?, ?, ?, ?)");
				$stmt->execute([
					$_SESSION['userData']['uid'],
					1,
					$pid,
					$aid,
					$name
				]);
				$id = $pdo->lastInsertId();
				$stmt = $pdo->prepare("INSERT INTO stats_fortnite (uid, gid, window, unix) VALUES (?, ?, ?, ?), (?, ?, ?, ?), (?, ?, ?, ?)");
				$stmt->execute([
					$uid,
					$id,
					"alltime",
					$dateU,
					$uid,
					$id,
					"season4",
					$dateU,
					$uid,
					$id,
					"season5",
					$dateU
				]);
				$stmt = $pdo->prepare("INSERT INTO stats_update (gid, window, unix) VALUES (?, ?, ?), (?, ?, ?), (?, ?, ?)");
				$stmt->execute([
					$id,
					"alltime",
					$dateU,
					$id,
					"season4",
					$dateU,
					$id,
					"season5",
					$dateU
				]);
				echo "SUCCESS";
			}
		}
		$stmt = null;
	} else {
		echo "ERROR";
	}
?>