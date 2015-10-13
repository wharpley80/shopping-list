<?php
session_start();

require_once('../inc/config.php');
require(ROOT_PATH . 'inc/database.php');

// Creates a new username.
if (isset($_POST['newname'])) {
  $newname = trim($_POST['newname']);
  $password = trim($_POST['password']);
  $password_confirm = trim($_POST['password-confirm']);
  
	if (empty($newname) || empty($password) || empty($password_confirm)) {
		echo "Complete All Fields!";
	} elseif ($password !== $password_confirm) {
		echo "Passwords Don't Match!";
	} else {
		
		$password = hash("sha256", $password);
				
		try {
		  $sql = $db->prepare('INSERT IGNORE INTO user_name (name,password) VALUES (?,?)');
		  $sql->bindParam(1,$newname);
			$sql->bindParam(2,$password);
		  $sql->execute();
		} catch (Exception $e) {
		  echo 'Data could not be retrieved from the database.';
		  exit;
		}

		// Grabs ID from NAME Table.
	  function newuser_id($newname) {
	    require(ROOT_PATH . 'inc/database.php');
    
	    try {
	      $ids = $db->prepare('SELECT id FROM user_name WHERE name = ?');
	      $ids->bindValue(1,$newname);
	      $ids->execute();
	      foreach ($ids as $id) {
	        $userID = $id['id'];
	        return $userID;
	      }
	    } catch (Exception $e) {
	      echo "Data was not retrieved from the database successfully.";
	      exit;
	    }
	  } 
		$newuserid = newuser_id($newname);
	
		$_SESSION['login'] = "1";
		$_SESSION['userid'] = $newuserid;
	  header ("Location: " . BASE_URL . "list/");
	  exit();
  } 
}
require_once(ROOT_PATH . 'inc/header.php');

?>
<body>
  <header>
    <h1>Easy List Maker</h1>
    <p>SignIn or SignUp and Start Making Lists. <br>
    	Create as many Lists as you desire. <br>
    	Shopping List, To Do List, Christmas List, Packing List, etc...</p>
    <img id="notepad" src="../img/1441743044_kwrite.png" width="128" height="128" alt="Notepad">
  </header>
    <form method="POST" action="" class="signup-form">
  	<div>
	    <input type="text" name="newname" id="name" placeholder="Username">
    </div>
    <div>
			<input type="password" name="password" id="password" placeholder="Password">
    </div>
    <div>
	    <input type="password" name="password-confirm" id="password-confirm" placeholder="Password Confirm">
	    <input type="submit" name="addname" class="addname" value="Sign Up">
    </div>
  </form>
  <img id="shoppingcart" src="../img/1440454195_Shopping cart.png" width="128" height="109" alt="1440454195 Shopping Cart">
	<img id="suitcase" src="../img/1441743852_suitcase-br.png" width="128" height="128" alt="Suitcase">
  <div class="newcomer">
    <a href="../signin" id="newname">Existing Users Click Here</a>
  </div>
<?php
include(ROOT_PATH . 'inc/footer.php');
?>