<?php
	$dsn = "mysql:host=*;dbname=*;charset=utf8mb4";
	$options = [
		PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // turn on errors in the form of exceptions
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // make the default fetch be an associative array
	];
	try {
		$pdo = new PDO($dsn, "*", "*", $options);
	} catch (Exception $e) {
		error_log($e->getMessage()); // in production
		exit('Database error!'); // something a user can understand
	}
?>
