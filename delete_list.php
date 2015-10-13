<?php
include('inc/config.php');
require_once(ROOT_PATH . 'inc/database.php');

if(isset($_POST['id']) && ($_POST['lis'])) {
  $id = $_POST['id']; 
  $lis = trim($_POST['lis']); 
  
  $sql = $db->prepare("DELETE FROM grocery_item WHERE name_id = ? AND list = ?");
  $sql->bindParam(1,$id);    
  $sql->bindParam(2,$lis);           
  $sql->execute();                
} 
?>