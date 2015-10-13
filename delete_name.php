<?php
include('inc/config.php');
require_once(ROOT_PATH . 'inc/database.php');

if(isset($_POST['ids'])) {
  $id = $_POST['ids'];  
  
  $sql = $db->prepare("DELETE FROM grocery_item WHERE name_id = ?");
  $sql->bindParam(1,$id, PDO::PARAM_INT);               
  $sql->execute();                
                   
  $sql2 = $db->prepare("DELETE FROM user_name WHERE id = ?");
  $sql2->bindParam(1,$id, PDO::PARAM_INT);
  $sql2->execute();
} 
?>