<?php
include('inc/config.php');
require_once(ROOT_PATH . 'inc/database.php');

if(isset($_POST['id']) && ($_POST['lis'])) {
  $id = $_POST['id']; 
  $lis = $_POST['lis'];

  $sql = $db->prepare('UPDATE grocery_item SET item = NULL WHERE name_id = ? AND list = ?');
  $sql->bindParam(1,$id, PDO::PARAM_INT);
  $sql->bindParam(2,$lis);
  $sql->execute();
}
?>