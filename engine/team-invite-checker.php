<?php
session_start();
include "functions.php";

echo getInvites();

//echo json_encode(array("invites" => "$invites", "numInvites" => 1));
//echo "hej";
?>