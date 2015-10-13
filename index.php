<?php 

session_start();
session_destroy();

session_start();

if (!(isset($_SESSION['login']) && $_SESSION['login'] != '')) {
	header("Location: signin/");
	exit();
} else {
	$userid = ($_SESSION['userid']);
}

require_once('inc/config.php');
require_once(ROOT_PATH . 'inc/header.php');
require_once(ROOT_PATH . 'inc/database.php');
require_once(ROOT_PATH . 'inc/footer.php');
?> 

 
 
 

  