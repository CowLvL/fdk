<?php
session_start(); 
include "functions.php";
include "database.php";


if(!empty($_POST["team_id"])) {

    $team_id = $_POST["team_id"];

    $stmt = $pdo->prepare("SELECT * FROM team_assoc WHERE team_id LIKE ?");
    $stmt->execute([$team_id]);
    $result = $stmt->fetchAll();

    $id = 1;
    if($stmt->rowCount()) { ?>
    <div class="box">
    <div class="media-list media-list-divided media-list-hover">
        <?php foreach($result as $user_name) { 
            $username = preg_split("/(,?\s+)|((?<=[a-z])(?=\d))|((?<=\d)(?=[a-z]))/i", $user_name["user_id"]);
            $user_id = getUserInfo($user_name["user_id"], "id");
            ?>                
            <div class="media" style="padding: 5px;" <?php echo $onclick; ?> style="cursor: pointer;">
                <div class="avatar avatar-sm status-success" href="#" style="cursor: pointer;">
                  <img src="<?php echo getUserInfo($user_name["user_id"], "picture"); ?>" alt="...">
                </div>
                <div class="media-body" style="line-height: 29px; height: 29px;" style="cursor: pointer;">
                  <p style="line-height: 29px; height: 29px; text-align: left; font-size: 13px; font-family: Poppins;">
                    <strong style="cursor: pointer;">
                        <?php echo getUserInfo($user_name["user_id"], "user_id"); ?>
                        <?php 
                        if(getUserInfo($user_name["user_id"], "id") == getUserInfo(0, "id")) { ?>
                            <input type="checkbox" id="basic_checkbox_<?php echo $id ?>" name="player[]" value="<?php echo getUserInfo(0, "id"); ?>" checked/>
                        <?php } else { ?>
                            <input type="checkbox" id="basic_checkbox_<?php echo $id ?>" name="player[]" value="<?php echo getUserInfo($user_name["user_id"], "id"); ?>" />
                        <?php } ?>
                        
                        <label for="basic_checkbox_<?php echo $id ?>" style="float: right; line-height: 29px; height: 29px; margin-top: 4px;"></label>
                    </strong>
                  </p>
                </div>
            </div>
        <?php
        $id++;
        }
        ?>
        </div>
        </div>
    <?php 
    } 
}
?>