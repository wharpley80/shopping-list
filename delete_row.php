<?php
include('inc/config.php');
require_once(ROOT_PATH . 'inc/database.php');

if(isset($_POST['id'])) {
  $id = $_POST['id'];  
  $sql = $db->prepare("DELETE FROM grocery_item WHERE id = ?");
  $sql->bindParam(1,$id, PDO::PARAM_INT);
  $sql->execute();
}
?>