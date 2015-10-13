<?php
session_start();

require_once('../inc/config.php');
require(ROOT_PATH . 'inc/database.php');

if (isset($_POST['username']) && isset($_POST['password'])) {
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	$password = hash("sha256", $password);
	
	if (empty($username) || empty($password)) {
	} else {

	  function username($username,$password) {
	    require(ROOT_PATH . 'inc/database.php');
	
			try {
				$user = $db->prepare('SELECT name FROM user_name WHERE name = ? AND password = ?');
			  $user->bindParam(1,$username);
			  $user->bindParam(2,$password);
			  $user->execute();
			  foreach ($user as $usr) {
			    $users = $usr['name'];
			    return $users;
			  }
			} catch (Exception $e) {
				echo 'Data could not be retrieved from the database.';
			  exit;
			}
		}

		// Grabs ID from NAME Table.
	  function user_id($username) {
	    require(ROOT_PATH . 'inc/database.php');
    
	    try {
	      $ids = $db->prepare('SELECT id FROM user_name WHERE name = ?');
	      $ids->bindValue(1,$username);
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

		$userid = user_id($username);
	
		if(username($username,$password) == $username) {
			$_SESSION['login'] = "1";
			$_SESSION['userid'] = $userid;
		  header ("Location: " . BASE_URL . "list/");
		  exit();
		} else { 
			echo "Invalid login!!";
		}
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
  <div id="info">
  <form method="POST" action="" class="signin-form">
		<input type="text" name="username" id="username" placeholder="Username">
  <div>
    <input type="password" name="password" id="password" placeholder="Password">
    <input type="submit" name="signin" class="signin" value="Log In">
  </form>
  </div>
	</div>
	<img id="shoppingcart" src="../img/1440454195_Shopping cart.png" width="128" height="109" alt="1440454195 Shopping Cart">
	<img id="suitcase" src="../img/1441743852_suitcase-br.png" width="128" height="128" alt="Suitcase">
  <div class="newcomer">
    <a href="../signup" id="newname">New User Click Here</a>
  </div>
<?php
include(ROOT_PATH . 'inc/footer.php');
?>