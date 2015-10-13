<?php

require_once('inc/config.php');
require_once('inc/database.php');

if (isset($_POST['username'])) {
  $valid_username = trim($_POST['username']);

    try {
      $validate_name = $db->prepare('SELECT name FROM user_name WHERE name = ?');
      $validate_name->bindValue(1,$valid_username);
      $validate_name->execute();
    } catch (Exception $e) {
      echo "Data was not retrieved from the database successfully 26.";
      exit;
    }

    $user_valid = $validate_name->rowCount();

	  if ($user_valid == 0) {
			$valid_name = "false";
		} else {
			$valid_name = "true";
		}

		echo $valid_name;

}