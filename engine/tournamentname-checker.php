<?php
if(isset($_POST["tournament_name"]))
{
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        die();
    }
    
    include "database.php";
   
    $tournament_name = filter_var($_POST["tournament_name"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
   
    $stmt = $pdo->prepare("SELECT name FROM tournaments WHERE name = ?");
    $stmt->execute([$tournament_name]);
    if($stmt->fetch()){
        die('has-danger');
    }else{
        die('has-success');
    }
}
?>