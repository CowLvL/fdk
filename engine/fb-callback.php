<?php
	session_start();
	if (empty($_SESSION['access_token'])) {
		// require facebook login config
		require_once "fb-config.php";
		try {
			$accessToken = $helper->getAccessToken();
		} catch (\Facebook\Exceptions\FacebookRespnseException $e) {
			//echo "Response Exception: " . $e->getMessage();
			exit();
		} catch (\Facebook\Exceptions\FacebookSDKException $e) {
			//echo "SDK Exception: " . $e->getMessage();
			exit();
		}
		if (!$accessToken) {
			header('Location: ../dashboard');
			exit();
		}
		// get long lived access token
		$oAuth2Client = $FB->getOAuth2Client();
		if (!$accessToken->isLongLived()) {
			$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
		}
		// get user fields
		$response = $FB->get("/me?fields=id,first_name,last_name,email,picture.type(large)", $accessToken);
		$userData = $response->getGraphNode()->asArray();
		$_SESSION['userData'] = $userData;
		$_SESSION['access_token'] = (string) $accessToken;
		// check if first time login
		include "database.php";
		$stmt = $pdo->prepare("SELECT * FROM users WHERE fb_id = ?");
		$stmt->execute([$_SESSION['userData']['id']]);
		if ($stmt->rowCount() > 0){
			$row = $stmt->fetch();
			$_SESSION['userData']['uid'] = $row['id'];
		} else {
			// get all user_id with same first name
			$stmt = $pdo->prepare("SELECT * FROM users WHERE fb_id = ?");
			$stmt->execute([$_SESSION['userData']['id']]);
			$arr = $stmt->fetchAll();
			if(!$arr){
				// get all user_id with same first name
				$stmt = $pdo->prepare("SELECT user_id FROM users WHERE first_name = ?");
				$stmt->execute([$_SESSION['userData']['first_name']]);
				$arr = $stmt->fetchALL(PDO::FETCH_COLUMN);
				if(!$arr){
					// no users with same fist name
					($user_id = rand(00001,99999));
				} else {
					// more users with same first name
					while( in_array( ($user_id = rand(00001,99999)), $arr ) );
				}
			}
			$stmt = null;
			// download profile image from facebook
			$dir = "/images/avatar/";
			$img = md5($_SESSION['userData']['picture']['url']).'.jpg';
			$url = $_SESSION['userData']['picture']['url'];
			$ch = curl_init($url);
			$fp = fopen($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.$img, 'wb');
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_exec($ch);
			curl_close($ch);
			fclose($fp);
			$profile_picture = "/images/avatar/" . $img;
			// check if user has email
			if(!isset($_SESSION['userData']['email'])) {
				$email = "no email";
			} else {
				$email = $_SESSION['userData']['email'];
			}
			// replace spaces with dash in first name field
			$first_name = str_replace(' ', '-', $_SESSION['userData']['first_name']);
			$new_data = str_replace  ("'", "", $first_name);
			$first_name = preg_replace ('/[^\p{L}\p{N}]/u', '', $new_data);
			// new user, insert info into users table
			$stmt = $pdo->prepare("INSERT INTO users (fb_id, user_id, email, first_name, last_name, picture, rank, locked, created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$stmt->execute([
				$_SESSION['userData']['id'],
				$first_name . $user_id,
				$email,
				$_SESSION['userData']['first_name'],
				$_SESSION['userData']['last_name'],
				$profile_picture,
				0, // rank
				1,
				time() // locked - 0 false, 1 true
			]);
			$_SESSION['userData']['uid'] = $pdo->lastInsertId();
			$stmt = null;
		}
		$stmt = null;
		header('Location: ../dashboard');
	} else {
		header('Location: ../dashboard');
	}
	exit();
?>