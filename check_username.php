<?php

require_once('inc/config.php');
require_once('inc/database.php');

if (isset($_POST['newname'])) {
  $pre_newname = trim($_POST['newname']);

	try {
		$check_name = $db->prepare('SELECT name FROM user_name WHERE name = ?');
		$check_name->bindParam(1,$pre_newname);
		$check_name->execute();
	} catch (Exception $e) {
		echo 'Data could not be retrieved from the database 24.';
		exit;
	}

	$user_check = $check_name->rowCount();

  if ($user_check == 0) {
		$valid = "true";
	} else {
		$valid = "false";
	}

	echo $valid;

}