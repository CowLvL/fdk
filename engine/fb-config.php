<?php
require_once "Facebook/autoload.php";

$FB = new \Facebook\Facebook([
	'app_id' => '1203589156446718',
	'app_secret' => '3f37aa81eee25d1af520fbe65046ef6c',
	'default_graph_version' => 'v2.10'
]);

$helper = $FB->getRedirectLoginHelper();
?>