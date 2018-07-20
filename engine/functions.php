<?php
// get active page for sidebar
function getActivePage($page, $profile = "0") {
	$active_page = $_REQUEST["page"];
	if ($active_page == $page) {
		if ($profile == "0" or $profile == strtolower(getUserInfo(0, "user_id", 0))) {
			return "class=\"active\"";
		}
	} 
}

// terms of service
function getTos() {
	include "database.php";
	$stmt = $pdo->prepare("SELECT * FROM tos ORDER BY id LIMIT 1");
	$stmt->execute();
	$arr = $stmt->fetch();
	return $arr;
	$stmt = null;
}

// user object
function getUserInfo($user, $field, $raw = 1) {
	include "database.php";

	if ($user == "0") {
		$user = $_SESSION['userData']['id'];
		$stmt = $pdo->prepare("SELECT * FROM users WHERE fb_id = ?");
	} else {
		if (is_numeric($user)) {
			$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
		} else {
			$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
		}
		
	}
	
	$stmt->execute([$user]);
	$arr = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($field == "user_id" && $raw != "0") {
		$parts = preg_split("/(,?\s+)|((?<=[a-z])(?=\d))|((?<=\d)(?=[a-z]))/i", $arr[$field]);
		//echo "<b style=\"font-weight: bold; color: rgb(251, 174, 28);\">" . $parts[0] . "</b>" . $parts[1];
		return "<span class=\"font-weight-400\"><b style=\"font-weight: bold; color: rgb(251, 174, 28);\">" . $parts[0] . "</b>#" . $parts[1] . "</span>";
	} else {
		return $arr[$field];
	}     
	
	$stmt = null;
}

// user groups, getUserGroups("") for active user, else use local id. Returns json object {"g1": "group_name"} - $object->g1;
function getUserGroups($user) {
	include "database.php";
	$groups = array();
	$user = ($user == "") ? $_SESSION['userData']['uid'] : $user;
	$stmt = $pdo->prepare("SELECT gid FROM user_group_members WHERE uid = ?");
	$stmt->execute([$user]);
	while ($row = $stmt->fetch()) {
		$gid = $row['gid'];
		$stmt1 = $pdo->prepare("SELECT name FROM user_groups WHERE id = ?");
		$stmt1->execute([$gid]);
		$row1 = $stmt1->fetch();
		$gname = $row1['name'];
		$groups["g".$gid] = $gname;
		$stmt1 = null;
	}
	$stmt = null;
	return json_encode($groups);
}

// user group, checkUserGroup("", group_id) for active user, else use local id. Returns 1 if user is member of group, else 0
function checkUserGroup($user, $group) {
	include "database.php";
	$user = ($user == "") ? $_SESSION['userData']['uid'] : $user;
	$stmt = $pdo->prepare("SELECT * FROM user_group_members WHERE uid = ? AND gid = ?");
	$stmt->execute([$user, $group]);
	$result = ($row = $stmt->fetch()) ? 1 : 0;
	$stmt = null;
	return $result;
}

// user gametag, usage: getGametag(0, 1)['name'];
function getGametag($user, $game, $platform) {
	include "database.php";
	if (!is_numeric($game)) {
		$stmt = $pdo->prepare("SELECT * FROM games WHERE tag = ?");
		$stmt->execute([$game]);
		$arr = $stmt->fetch();
		$game = $arr['id'];
	}
	if ($user == "0") {
		$user = $_SESSION['userData']['uid'];
		$sql = "SELECT * FROM game_tags WHERE uid = ? ORDER BY priority";
	} else {
		if (is_numeric($user)) {
			$sql = "SELECT * FROM game_tags WHERE uid = ?";
		} else {
			$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
			$stmt->execute([$user]);
			$arr = $stmt->fetch();
			$user = $arr['id'];
			$sql = "SELECT * FROM game_tags WHERE uid = ?";
		}
	}
	if ($game != "") {
		$sql .= " AND gid = ?";
	}
	if ($platform != "") {
		$sql .= " AND pid = ?";
	}
	$stmt = $pdo->prepare($sql);
	if ($game != "") {
		$stmt->execute([$user, $game, $platform]);
		$arr = $stmt->fetch();
	} else {
		$stmt->execute([$user]);
		$arr = $stmt->fetchAll();
	}
	$stmt = null;
	return $arr;
}
function getGametags($user) {
	include "engine/database.php";
	if ($user == "0") {
		$user = $_SESSION['userData']['uid'];
	} else {
		if (!is_numeric($user)) {
			$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
			$stmt->execute([$user]);
			$arr = $stmt->fetch();
			$user = $arr['id'];
		}
	}
	$stmt = $pdo->prepare("SELECT * FROM game_tags WHERE uid = ? ORDER BY priority");
	$stmt->execute([$user]);
	$rows = $stmt->fetchAll();
	$stmt = null;
	$arr = array();
	//var_dump($rows);
	foreach ($rows as $key => $val) {
		$arr[$val['gid']][] = $val;
	}
	foreach ($arr as $key => $val) {
		//var_dump($val);
		foreach ($val as $k => $v) {
			$gid = $v["gid"];
			$stmt = $pdo->prepare("SELECT * FROM games WHERE id = ?");
			$stmt->execute([$gid]);
			$row = $stmt->fetch();
			$game = $row["game"];
			$arr[$key][$k]["game"] = $game;
		}
	}
	//var_dump($arr);
	return $arr;
}
// team object
function getTeamInfo($team, $field) {
	include "database.php";

	if (is_numeric($team)) {
		$stmt = $pdo->prepare("SELECT * FROM team WHERE id = ?");
	} else {
		$stmt = $pdo->prepare("SELECT * FROM team WHERE name_id = ?");
	}

	$stmt->execute([$team]);
	$arr = $stmt->fetch(PDO::FETCH_ASSOC);
	return $arr[$field];
	
	$stmt = null;
}

// get teams members
function getTeamMembers($team) {
	include "database.php";

	$stmt = $pdo->prepare("SELECT user_id FROM team_assoc WHERE team_id = ?");
	$stmt->execute([$team]);
	while ($row = $stmt->fetch()) {
		$id = $row["user_id"];
		?>

		<div class="media">
		  <a class="avatar avatar-lg status-success" href="#">
			<img src="<?php echo getUserInfo($id, "picture");?>" alt="...">
		  </a>
		  <div class="media-body">
			<p>
			  <a href="/profile/<?php echo str_replace("#", "", getUserInfo($id, "user_id", 0)); ?>"><?php echo getUserInfo($id, "user_id"); ?></a>
			  <small class="sidetitle">Online</small>
			</p>
			<p>Designer</p>
		  </div>
		</div>
		<?php

	}
	$stmt = null;
}

// check if user exists - returns 1 for yes, 0 for no
function userExists($user) {
	include "database.php";

	$stmt = $pdo->prepare("SELECT user_id FROM users WHERE user_id = ?");
	$stmt->execute([$user]);
	return $stmt->rowCount();

	$stmt = null;
}

// check if team exists - returns 1 for yes, 0 for no
function teamExists($team) {
	include "database.php";

	$stmt = $pdo->prepare("SELECT name_id FROM team WHERE name_id = ?");
	$stmt->execute([$team]);
	return $stmt->rowCount();

	$stmt = null;
}

// check if tournament exists - returns 1 for yes, 0 for no
function tournamentExists($tournament) {
	include "database.php";

	$stmt = $pdo->prepare("SELECT id_long FROM tournaments WHERE id_long = ?");
	$stmt->execute([$tournament]);
	return $stmt->rowCount();

	$stmt = null;
}

function getTournamentInfo($id, $field) {
	include "database.php";

	$stmt = $pdo->prepare("SELECT * FROM tournaments WHERE id = ?");
	
	$stmt->execute([$id]);
	$arr = $stmt->fetch(PDO::FETCH_ASSOC);

	return $arr[$field];
	
	$stmt = null;
}

// tournament invite widget
function getTournamentInvitesWidget($tournament) {
	include "database.php";

	$user = getUserInfo(0, "id");

	$stmt = $pdo->prepare("SELECT * FROM tournament_invite WHERE tournament = ? AND invitee = ?)");
	$stmt->execute([$tournament, $user]);
	while ($row = $stmt->fetch()) {

	}
}

// getTournamentInvites
function getTournamentInvites($tournament, $user, $size) {
	include "database.php";

	$is_inviter = 0;
	$is_invited = 0;

	$stmt = $pdo->prepare("SELECT * FROM tournament_invite WHERE tournament = ? AND (inviter = ? OR invitee = ?)");
	$stmt->execute([$tournament, $user, $user]);
	if($stmt->rowCount() > 0) {
		$is_invited = 1;
	}

	if($is_invited == 1) {

		$stmt = $pdo->prepare("SELECT * FROM tournament_invite WHERE tournament = ? AND inviter = ? LIMIT 1");
		$stmt->execute([$tournament, $user]);
		while ($row = $stmt->fetch()) {
			$is_inviter = 1;
			$tournament = $row["tournament"];
			$invitee = $row["invitee"];
			$inviter = $row["inviter"];
			$invite_id = $row["invite_id"];
			// check if all have accepted invite
			$stmt2 = $pdo->prepare("SELECT * FROM tournament_invite WHERE tournament = ? AND invite_id = ? AND status = ?");
			$stmt2->execute([$tournament, $invite_id, 3]);
			if($stmt2->rowCount() == $size) {
				// all have accepted
				?>
				<li><button data-user-id="<?php echo getTournamentInfo($tournament, "id"); ?>" type="button" class="btn btn-block btn-success btn-lg sa-assign-decline">Tilmeldt</button></li>
				<?php
			} else {
				?>
				<li><button data-user-id="<?php echo getTournamentInfo($tournament, "id"); ?>" type="button" class="btn btn-block btn-warning btn-lg sa-assign-duo-status">Afventer</button></li>
				<?php
			}
		}
		$stmt = null;

		// user haven't invited anyone to tournament
		if($is_inviter == 0) {
			$stmt = $pdo->prepare("SELECT * FROM tournament_invite WHERE tournament = ? AND invitee = ? LIMIT 1");
			$stmt->execute([$tournament, $user]);
			while ($row = $stmt->fetch()) {
				$tournament = $row["tournament"];
				$invitee = $row["invitee"];
				$inviter = $row["inviter"];
				$invite_id = $row["invite_id"];
				// check if all have accepted invite
				$stmt2 = $pdo->prepare("SELECT * FROM tournament_invite WHERE tournament = ? AND invitee = ? AND status = ? LIMIT 1");
				$stmt2->execute([$tournament, $user, 3]);
				if($stmt2->rowCount() > 0) {
					// all have accepted
					?>
					<li><button data-user-id="<?php echo getTournamentInfo($tournament, "id"); ?>" type="button" class="btn btn-block btn-success btn-lg sa-assign-decline">Tilmeldt</button></li>
					<?php
				} else {
					?>
					<li>
						<button data-user-id="<?php echo getTournamentInfo($tournament, "id"); ?>" type="button" class="btn btn-block btn-warning btn-lg sa-assign-duo-answer">
							Inviteret
						</button>
					</li>
					<?php
				}
			}
		}
	} else {
		?>
		<li><button data-user-id="<?php echo getTournamentInfo($tournament, "id"); ?>" type="button" class="btn btn-block btn-default btn-lg sa-assign-duo">Tilmeld <?php echo ucfirst(getTournamentInfo($tournament, "type")); ?></button></li>
		<?php
	}

}

// get teams / players
function getTournaments($game, $num = 0, $prrow = 3, $tour = 0) {
	include "database.php";

	$current_timestamp = time();

	if($tour == 0) {
		if($num == 0) {
			$stmt = $pdo->prepare("SELECT id FROM tournaments WHERE game = ? AND start_date > ? ORDER BY start_date ASC");
			$stmt->execute([$game, $current_timestamp]);
		} else {
			$stmt = $pdo->prepare("SELECT id FROM tournaments WHERE game = ? AND start_date > ? ORDER BY start_date ASC LIMIT ?");
			$stmt->execute([$game, $current_timestamp, $num]);
		}
	} else {
		$stmt = $pdo->prepare("SELECT id FROM tournaments WHERE id_long = ?");
		$stmt->execute([$tour]);
	}
	
	
	while ($row = $stmt->fetch()) {
		$id = $row["id"];
		?>
		<?php if($prrow == 3) { ?>
		<div class="col-lg-4 tour_playstation">
		<?php } elseif($prrow == 2) { ?>
		<div class="col-lg-6 tour_playstation">
		<?php } elseif($prrow == 1) { ?>
		<div class="tour_playstation">
		<?php } ?>
            <div class="box pull-up">
				<div class="ribbon ribbon-bookmark ribbon-right bg-secondary">
					<?php
					if (strtolower(getTournamentInfo($id, "platform")) == "pc") {
						echo "<i class=\"mdi mdi-windows\"></i>";
					} elseif (strtolower(getTournamentInfo($id, "platform")) == "playstation") {
						echo "<i class=\"mdi mdi-playstation font-size-18\"></i>";
					} elseif (strtolower(getTournamentInfo($id, "platform")) == "crossplay") {
						echo "<i class=\"fa fa-random\"></i>";
					}
					?>
					 <?php echo ucfirst(getTournamentInfo($id, "platform")); ?>
				</div>
				<?php if(getTournamentInfo($id, "card") == "") { ?>
					<div class="box-body bg-info bg-deathstar-white py-60-10" style="background-image: url(/images/card/tour-bg-<?php echo getTournamentInfo($id, "type"); ?>1.jpg);background-position: center;">
					<?php } else { ?>
						<div class="box-body bg-info bg-deathstar-white py-60-10" style="background-image: url(<?php echo getTournamentInfo($id, "card"); ?>);background-position: center;">
					<?php } ?>
            	 
	                <div class="flexbox">
	                  <div class="flex-grow">
	                    <h2><a class="text-white" href="/tournament/<?php echo getTournamentInfo($id, "id_long"); ?>"><?php echo getTournamentInfo($id, "name"); ?></a></h2>
	                    <div>
	                    	<?php
							$tournament_type = getTournamentInfo($id, "type");
	                    	if($tournament_type == "solo") { ?>
	                    		<i class="ion ion-person w-20px"></i>
	                    	<?php } elseif($tournament_type == "duo") { ?>
	                    		<i class="ion ion-person-stalker w-20px"></i>
	                    	<?php } elseif($tournament_type == "squad") { ?>
	                    		<i class="ion ion-ios-people w-20px font-size-18"></i>
	                    	<?php } ?>
	                    	<?php echo ucfirst($tournament_type); ?>
	                    </div>
	                    <div><i class="fa fa-fw fa-address-card w-20px"></i> <?php echo getTournamentInfo($id, "sponsor"); ?></div>
	                    <div>&nbsp;</div>
	                  </div>
	                </div>
	                <div class="flexbox align-items-center mt-25">
						<div>
							<p class="no-margin font-weight-600"><span class="text-yellow"><?php echo getTournamentInfo($id, "signups"); ?></span> / 100</p>
							<p class="no-margin">Tilmeldte</p>
						</div>
						<div class="text-right">
							<p class="no-margin font-weight-600"><span class="text-yellow">Tilmeldingsfrist</span></p><p class="no-margin">
								<?php echo calculate_time_span(getTournamentInfo($id, "start_date")) ?></p>
					    </div>
					</div>
	              </div>
	            <ul class="flexbox flex-justified text-center p-20">
	              	<li class="br-1 border-light"><span class="text-muted"><?php echo date("d/m - Y",getTournamentInfo($id, "start_date")); ?></span><br><span class="font-size-20"><?php echo ucfirst(strftime("%A",getTournamentInfo($id, "start_date"))); ?></span></li>
	                <li class="br-1 border-light"><span class="text-muted">Åben tilmelding</span><br><span class="font-size-20"><?php echo date("H:i",getTournamentInfo($id, "start_date")); ?></span></li>
	                <?php
	                if(getTournamentInfo($id, "signups") != 100) {
		                if(getTournamentInfo($id, "type") == "solo") { 
		                	// solo ?>
		                	<li><button id="" type="button" data-user-id="<?php echo getTournamentInfo($id, "id"); ?>" class="btn btn-block btn-default btn-lg">Tilmeld <?php echo ucfirst(getTournamentInfo($id, "type")); ?></button></li>
		                <?php } elseif(getTournamentInfo($id, "type") == "duo") { 
		                	// duo ?>
		                	<?php
		                	echo getTournamentInvites(getTournamentInfo($id, "id"), getUserInfo(0, "id", 0), 2);
		                	?>
		                <?php } elseif(getTournamentInfo($id, "type") == "squad") {
		                	// squad ?>
		                	<li><button id="sa-assign-squad" data-user-id="<?php echo getTournamentInfo($id, "id"); ?>" type="button" class="btn btn-block btn-default btn-lg">Tilmeld <?php echo ucfirst(getTournamentInfo($id, "type")); ?></button></li>
		                <?php }
	                } else { ?>
	                	<li><button data-user-id="<?php echo getTournamentInfo($id, "id"); ?>" type="button" class="btn btn-block btn-default btn-lg disabled">Fyldt</button></li>
	                <?php } ?>
            	</ul>
            </div>
          </div>
          <?php
	}
	$stmt = null;
}

function calculate_time_span($date){
    $seconds  = $date - strtotime(date('Y-m-d H:i:s'));

        $months = floor($seconds / (3600*24*30));
        $day = floor($seconds / (3600*24));
        $hours = floor($seconds / 3600);
        $mins = floor(($seconds - ($hours*3600)) / 60);
        $secs = floor($seconds % 60);

        if($seconds < 60)
            $time = $secs."s tilbage";
        else if($seconds < 60*60 )
            $time = $mins."m tilbage";
        else if($seconds < 24*60*60)
            $time = $hours."t tilbage";
        else 
            $time = $day."d tilbage";

        return $time;
}

function getInvites() {
	include "database.php";

	$stmt = $pdo->prepare("SELECT * FROM team_invite WHERE invitee = ?");
	$stmt->execute([getUserInfo(0, "id")]);
	while ($row = $stmt->fetch()) {
		$team = getTeamInfo($row["team"], "name");
		$team_id = getTeamInfo($row["team"], "id");
		$team_url = getTeamInfo($row["team"], "name_id");
		$inviter = getUserInfo($row["inviter"], "user_id");
		?>
		<li id="invite-block-<?php echo $team_id; ?>">
			<div>
				<h4>
					Invitation til <a href="/team/<?php echo $team_url; ?>" class="font-weight-600"><?php echo $team; ?></a>
				</h4>
				<span><?php echo $inviter; ?> har inviteret dig</span>
				<span>
					<form>
						<button type="button" class="btn btn-success btn-xs pull-left" style="width: 45%;" id="invite-accept" value="accept" onclick="inviteResponse($(this).val(), '<?php echo $team_id; ?>');">Accepter</button>
						<button type="button" class="btn btn-danger btn-xs pull-right" style="width: 45%;" id="invite-decline" value="decline" onclick="inviteResponse($(this).val(), '<?php echo $team_id; ?>');">Afvis</button>
					</form>
				</span>
			</div>
		</li>
        <?php
	}
	$stmt = null;
}

// get sidebar teams
function getSidebarTeams($profile) {
	include "database.php";

	$user_id = getUserInfo($profile, "id");

	$stmt = $pdo->prepare("SELECT team_id FROM team_assoc WHERE user_id = ?");
	$stmt->execute([$user_id]);
	if($stmt->rowCount() > 0) {
		while ($row = $stmt->fetch()) {
			$team_id = $row["team_id"];

			$stmt2 = $pdo->prepare("SELECT * FROM team WHERE id = ?");
			$stmt2->execute([$team_id]);
			$arr = $stmt2->fetch(PDO::FETCH_ASSOC);
			?>
			<li><a href="/team/<?php echo $arr["name_id"]; ?>"><?php echo $arr["name"]; ?></a></li>
			<?php
		}
	}
	
	$stmt = null;
}

// get user teams
function getProfileTeams($profile) {
	include "database.php";

	$user_id = getUserInfo($profile, "id");

	$stmt = $pdo->prepare("SELECT team_id FROM team_assoc WHERE user_id = ?");
	$stmt->execute([$user_id]);
	if($stmt->rowCount() > 0) {
		?>
		<div class="box">
			<div class="box-header with-border bg-sidebar">
				<h6 class="box-title">Teams</h6>

				<div class="box-tools">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				</div>
			</div>
			<div class="box-body no-padding mailbox-nav">
				<ul class="nav nav-pills flex-column">
				<?php
				while ($row = $stmt->fetch()) {
					$team_id = $row["team_id"];

					$stmt2 = $pdo->prepare("SELECT * FROM team WHERE id = ?");
					$stmt2->execute([$team_id]);
					$arr = $stmt2->fetch(PDO::FETCH_ASSOC);
					?>
					<li class="nav-item"><a class="nav-link" href="/team/<?php echo $arr["name_id"]; ?>"><i class="ion ion-star"></i> <?php echo $arr["name"]; ?></a></li>
					<?php
				}
				?>
				</ul>
			</div>
		</div>
			<?php
	}
	
	$stmt = null;
}

// get user teams for sweetalert
function getProfileTeamsSwal($profile) {
	include "database.php";

	$user_id = getUserInfo($profile, "id");

	$stmt = $pdo->prepare("SELECT team_id FROM team_assoc WHERE user_id = ?");
	$stmt->execute([$user_id]);
	if($stmt->rowCount() > 0) {
		?>
		'<div class="">' +
  		'<div class="box">' +
  		'<div class="media-list media-list-divided media-list-hover">' +
  		<?php
  		$id = 1;
		while ($row = $stmt->fetch()) {
			$team_id = $row["team_id"];

			$stmt2 = $pdo->prepare("SELECT * FROM team WHERE id = ?");
			$stmt2->execute([$team_id]);
			$arr = $stmt2->fetch(PDO::FETCH_ASSOC);
			?>
			'<div class="media" style="padding: 5px;" <?php echo $onclick; ?> style="cursor: pointer;">' +
                '<div class="avatar avatar-sm status-success" href="#" style="cursor: pointer;">' +
                  '<img src="<?php echo $arr["picture"];; ?>" alt="..." style="cursor: pointer;">' +
                '</div>' +
                '<div class="media-body" style="line-height: 29px; height: 29px;" style="cursor: pointer;">' +
                  '<p style="line-height: 29px; height: 29px; text-align: left; font-size: 13px; font-family: Poppins;" style="cursor: pointer;">' +
                    '<strong style="cursor: pointer;">' +
                    '<?php echo $arr["name"]; ?>' +
                    '<input name="tour_team" type="radio" class="with-gap" id="radio_<?php echo $id; ?>" value="<?php echo $arr["id"]; ?>" <?php if($id == 1) { echo 'checked="checked"'; } ?>/>' +
					'<label for="radio_<?php echo $id; ?>" style="float: right; line-height: 29px; height: 29px; margin-top: 4px;">&nbsp;</label>' +
                    '</strong>' +
                 ' </p>' +
                '</div>' +
            '</div>' +
        <?php 
        $id++;
    	} ?>
		'</div>' +
		'</div>' +
		'</div>'
		<?php
	}
	
	$stmt = null;
}

// get team members for sweetalert
function getTeamsMembersSwal($profile) {
	include "database.php";

	$user_id = getUserInfo($profile, "id");

	$stmt = $pdo->prepare("SELECT team_id FROM team_assoc WHERE user_id = ?");
	$stmt->execute([$user_id]);
	if($stmt->rowCount() > 0) {
		?>
		'<div class="">' +
  		'<div class="box">' +
  		'<div class="media-list media-list-divided media-list-hover">' +
  		<?php
  		$id = 1;
		while ($row = $stmt->fetch()) {
			$team_id = $row["team_id"];

			$stmt2 = $pdo->prepare("SELECT * FROM team WHERE id = ?");
			$stmt2->execute([$team_id]);
			$arr = $stmt2->fetch(PDO::FETCH_ASSOC);
			?>
			'<div class="media" style="padding: 5px;" <?php echo $onclick; ?> style="cursor: pointer;">' +
                '<div class="avatar avatar-sm status-success" href="#" style="cursor: pointer;">' +
                  '<img src="<?php echo $arr["picture"]; ?>" alt="..." style="cursor: pointer;">' +
                '</div>' +
                '<div class="media-body" style="line-height: 29px; height: 29px;" style="cursor: pointer;">' +
                  '<p style="line-height: 29px; height: 29px; text-align: left; font-size: 13px; font-family: Poppins;" style="cursor: pointer;">' +
                    '<strong style="cursor: pointer;">' +
                    '<?php echo $arr["name"]; ?>' +
                    '<input name="group1" type="radio" class="with-gap" id="radio_<?php echo $id; ?>" value="<?php echo $arr["id"]; ?>" />' +
					'<label for="radio_<?php echo $id; ?>" style="float: right; line-height: 29px; height: 29px; margin-top: 4px;">&nbsp;</label>' +
                    '</strong>' +
                 ' </p>' +
                '</div>' +
            '</div>' +
        <?php 
        $id++;
    	} ?>
		'</div>' +
		'</div>' +
		'</div>'
		<?php
	}
	
	$stmt = null;
}

function getGames() {
	include "database.php";
	$stmt = $pdo->prepare("SELECT * FROM games");
	$stmt->execute();
	while ($row = $stmt->fetch()) {
		echo '<option value="'.$row['tag'].'">'.$row['game'].'</option>';
	}
	$stmt = null;
}

function getGame($game) {
	if ($game != "") {
		include "database.php";
		if (is_numeric($game)) {
			$stmt = $pdo->prepare("SELECT * FROM games WHERE id = ?");
		} else {
			$stmt = $pdo->prepare("SELECT * FROM games WHERE tag = ?");
		}
		$stmt->execute([$game]);
		$row = $stmt->fetch();
		return $row;
		$stmt = null;
	}
}

function getPlatforms() {
	include "database.php";
	$stmt = $pdo->prepare("SELECT * FROM platforms");
	$stmt->execute();
	while ($row = $stmt->fetch()) {
		echo '<option value="'.$row['tag'].'">'.$row['platform'].'</option>';
	}
	$stmt = null;
}

function getPlatform($platform) {
	if ($platform != "") {
		include "database.php";
		if (is_numeric($platform)) {
			$stmt = $pdo->prepare("SELECT * FROM platforms WHERE id = ?");
		} else {
			$stmt = $pdo->prepare("SELECT * FROM platforms WHERE tag = ?");
		}
		$stmt->execute([$platform]);
		$row = $stmt->fetch();
		//var_dump($row);
		return $row;
		$stmt = null;
	}
}

// get stats, usage: getStats(userID, gameID, platformID, window);
function getStats($user, $game, $platform, $window) {
	if ($user == "" && $user != 0) {
		$user = $_SESSION['userData']['uid'];
	}
	// if fortnite
	if ($game == 1) {
		include "database.php";
		$row = array();
		if ($user == 0) {
			$stmt = $pdo->prepare("SELECT * FROM game_tags WHERE gid = ? AND pid = ?");
			$stmt->execute([$game, $platform]);
			$sql = "SELECT * FROM `stats_fortnite` WHERE (";
			while ($row = $stmt->fetch()) {
				$sql .= "`gid` = '".$row['id']."' OR ";
			}
			$sql = substr($sql, 0, -4);
			$sql .= ") AND `window` = '".$window."'";
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			$row = $stmt->fetchAll();
		} else {
			$stmt = $pdo->prepare("SELECT * FROM game_tags WHERE uid = ? AND pid = ?");
			$stmt->execute([$user, $platform]);
			if ($row = $stmt->fetch()) {
				$gid = $row['id'];
				$aid = $row['aid'];
				$stmt = $pdo->prepare("SELECT * FROM stats_fortnite WHERE gid = ? AND window = ?");
				$stmt->execute([$gid, $window]);
				$row = $stmt->fetch();
				$time15 = date("U")-900;
				$timeup = $time15-$row['unix'];
				//echo $timeup;
				if ($timeup > 0) {
					$platform = getPlatform($platform)['tag'];
					$return = getEpicInfo($aid, $platform, $window);
					//var_dump($return);
					//echo "-".$row['unix']."=".($time15-$row['unix']);
					$stmt = $pdo->prepare("SELECT * FROM stats_fortnite WHERE gid = ? AND window = ?");
					$stmt->execute([$gid, $window]);
					$row = $stmt->fetch();
				}
			} else {
				return 0;
			}
		}
		$stmt = null;
		return $row;
	}
}

function getStatsEnt($game) {
	include "database.php";
	$stmt = $pdo->prepare("SELECT tag FROM games WHERE id = ?");
	$stmt->execute([$game]);
	$row = $stmt->fetch();
	$tag = $row['tag'];
	$stmt = $pdo->query("SELECT * FROM stats_".$tag." LIMIT 0");
	for ($i = 0; $i < $stmt->columnCount(); $i++) {
		$col = $stmt->getColumnMeta($i);
		$columns[] = $col['name'];
	}
	$stmt = null;
	return $columns;
}

function getEpicInfo($aid, $platform, $window) {
	include "database.php";
	$stmt = $pdo->prepare("SELECT * FROM game_tags WHERE aid = ?");
	$stmt->execute([$aid]);
	$row = $stmt->fetch();
	$uid = $row['uid'];
	$gid = $row['id'];
	include("fortnite-api/Autoloader.php");
	$api = new FortniteClient;
	$api->setKey('1e96183636a3e31f0d8478f1006e5788');
	$api->user->uid = $aid;
	$data = $api->user->stats($platform, $window);
	if ($data != NULL) {
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
		//echo $sql;
		if ($window == "season5") {
			$stmt = $pdo->prepare("SELECT window FROM stats_fortnite WHERE gid = ? AND window = ?");
			$stmt->execute([$gid, $window]);
			if ($stmt->rowCount() < 1) {
				$dateU = date("U");
				$stmt = $pdo->prepare("INSERT INTO stats_fortnite (uid, gid, window, unix) VALUES (?, ?, ?, ?)");
				$stmt->execute([
					$uid,
					$gid,
					"season5",
					$dateU
				]);
			}
		}
		// query sql
		$stmt = $pdo->query($sql);
	}
	//return "done";
	return $data;
}

function saveEpicInfo($u, $p) {
	include "database.php";
	$data = getEpicInfo($u, $p);
	$stmt = $pdo->prepare("UPDATE users SET epic_ign = ?, epic_id = ? WHERE fb_id = ?");
	$stmt->execute([
		$data->epicUserHandle,
		str_replace("-", "", $data->accountId),
		$_SESSION['userData']['id']
	]);
	$stmt = null;
}

function GetTimeDiff($timestamp) {
	$how_long_ago = '';
	$seconds = time() - $timestamp; 
	$minutes = (int)($seconds / 60);
	$hours = (int)($minutes / 60);
	$days = (int)($hours / 24);
	if ($days >= 1) {
		$how_long_ago = $days . ' day' . ($days != 1 ? 's' : '');
	} else if ($hours >= 1) {
		$how_long_ago = $hours . ' hour' . ($hours != 1 ? 's' : '');
	} else if ($minutes >= 1) {
		$how_long_ago = $minutes . ' minute' . ($minutes != 1 ? 's' : '');
	} else {
		$how_long_ago = $seconds . ' second' . ($seconds != 1 ? 's' : '');
	}
	return $how_long_ago;
}
?>
