<?php
if(isset($_POST["team_name"]))
{
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        die();
    }
    
    include "database.php";
   
    $team_name = filter_var($_POST["team_name"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
   
    $stmt = $pdo->prepare("SELECT name FROM team WHERE name = ?");
    $stmt->execute([$_POST["team_name"]]);

    $team_with_dash = str_replace("-", " ", $_POST["team_name"]);
    $stmt2 = $pdo->prepare("SELECT name FROM team WHERE name = ?");
    $stmt2->execute([$team_with_dash]);

    if($stmt->fetch() || $stmt2->fetch()){
        die('has-danger');
    }else{
        die('has-success');
    }
}
?>