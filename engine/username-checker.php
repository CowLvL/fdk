<?php
/*if(isset($_POST["user_name"]))
{
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        die();
    }
    
    include "database.php";
   
    $team_name = filter_var($_POST["user_name"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
   
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE user_id = ?");
    $stmt->execute([$_POST["user_name"]]);
    if($stmt->fetch()){
        die('has-danger');
    }else{
        die('has-success');
    }
}*/
include "functions.php";
include "database.php";

if(!empty($_POST["keyword"])) {

    $keyword = $_POST["keyword"] . "%";
    $keyword = str_replace("#","", $keyword);

    $team_name = $_POST["team_name"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id LIKE ?");
    $stmt->execute([$keyword]);
    $result = $stmt->fetchAll();

    //print_r($result);

    if($stmt->rowCount()) { ?>
    <div class="box">
    <div class="media-list media-list-divided media-list-hover" style="cursor: pointer;">
        <?php foreach($result as $user_name) { 
            $username = preg_split("/(,?\s+)|((?<=[a-z])(?=\d))|((?<=\d)(?=[a-z]))/i", $user_name["user_id"]);
            $user_id = getUserInfo($user_name["user_id"], "id");
            
            // check if user is member
            $stmt2 = $pdo->prepare("SELECT * FROM team_assoc WHERE user_id = ? AND team_id = ?");
            $stmt2->execute([
                $user_id,
                $team_name
            ]);
            $arr = $stmt2->fetch(PDO::FETCH_ASSOC);

            // check if user is invited
            $stmt3 = $pdo->prepare("SELECT * FROM team_invite WHERE invitee = ? AND team = ?");
            $stmt3->execute([
                $user_id,
                $team_name
            ]);
            $arr2 = $stmt3->fetch(PDO::FETCH_ASSOC);

            if(getUserInfo($user_name["user_id"], "id") != $arr["user_id"] && getUserInfo($user_name["user_id"], "id") != $arr2["invitee"]) {
                $onclick = "onclick=\"selectUsername('" . $username[0] . "#" . $username[1] . "');\"";
            }

            ?>                
            <div class="media" style="padding: 5px;" <?php echo $onclick; ?> style="cursor: pointer;">
                <div class="avatar avatar-sm status-success" href="#" style="cursor: pointer;">
                  <img src="<?php echo $user_name["picture"]; ?>" alt="..." style="cursor: pointer;">
                </div>
                <div class="media-body" style="line-height: 29px; height: 29px;" style="cursor: pointer;">
                  <p style="line-height: 29px; height: 29px; text-align: left; font-size: 13px; font-family: Poppins;" style="cursor: pointer;">
                    <strong style="cursor: pointer;">
                        <?php 
                            if(getUserInfo($user_name["user_id"], "id") == $arr["user_id"]) {
                                echo getUserInfo($user_name["user_id"], "user_id") . " - Er medlem";
                            } else {
                                if(getUserInfo($user_name["user_id"], "id") == $arr2["invitee"]) {
                                    echo getUserInfo($user_name["user_id"], "user_id") . " - Er inviteret";
                                } else {
                                    echo getUserInfo($user_name["user_id"], "user_id");
                                }
                            }
                        ?>
                    </strong>
                  </p>
                </div>
            </div>
        <?php } ?>
        </div>
        </div>
    <?php 
    } 
}
?>