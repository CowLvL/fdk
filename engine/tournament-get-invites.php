<?php
session_start(); 
include "functions.php";
include "database.php";


if(!empty($_POST["tournament_id"])) {

$tournament_id = $_POST["tournament_id"];
$invitee = getUserInfo(0, "id");

$stmt = $pdo->prepare("SELECT * FROM tournament_invite WHERE tournament = ? AND invitee = ?");
$stmt->execute([$tournament_id, $invitee]);
?>
<div class="box" id="tournamentInvitesForm">
<div class="media-list media-list-divided media-list-hover">
<?php
while ($row = $stmt->fetch()) {
?>
<div class="media" style="padding: 5px;" <?php echo $onclick; ?>>
    <div class="avatar avatar-xl status-success" href="#">
      <img src="<?php echo getTeamInfo($row["team"], "picture"); ?>" alt="...">
    </div>
    <div class="media-body" style="line-height: 29px; height: 55px;">
      <div style="line-height: 29px; height: 55px; text-align: left; font-size: 13px; font-family: Poppins; display: block;">
        <strong>
            <?php echo getTeamInfo($row["team"], "name") . " - " . getUserInfo($row["inviter"], "user_id"); ?>
            <br>
            <form>
              <button type="button" class="btn btn-success btn-xs pull-left" style="width: 45%;" id="invite-accept" value="accept,<?php echo $row["tournament"] . "," . $row["invite_id"]; ?>">Accepter</button>
              <button type="button" class="btn btn-danger btn-xs pull-right" style="width: 45%;" id="invite-decline" value="decline,<?php echo $row["tournament"] . "," . $row["invite_id"]; ?>">Afvis</button>
          </form>
        </strong>
      </div>
    </div>
</div>
<?php
}
?>
</div>
</div>
<?php
}
?>