<?php
session_start();

require_once('../inc/config.php');
require(ROOT_PATH . 'inc/database.php');

// Creates a new username.
if (isset($_POST['newname'])) {
  $newname = trim($_POST['newname']);
  $password = trim($_POST['new-password']);
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

// Signs In Existing User
if (isset($_POST['username']) && isset($_POST['password'])) {
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	$password = hash("sha256", $password);
	
	if (empty($username) || empty($password)) {
		echo "Please Complete All Fields";
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
			echo "Invalid Username";
		}
	}
}
require_once(ROOT_PATH . 'inc/header.php');
?>
<body>
	<div class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<h3 class="navbar-text pull-right">Easy List Maker</h3>
		</div>
	</div>
	<div class="jumbotron">
		<div class="container">
			<div class="row">
				<div class="col-sm-6">
					<h1>Easy List Maker</h1>
    			<p class="lead">
    				The quickest and easiest list making app you will ever use. Don't struggle with pen and paper anymore. Make 
    				your list here and then it's with you anywhere you go. As long as you have your phone or tablet you will never 
    				lose your list again.
    			</p>
    			<p class="lead">
    				<a class="btn btn-default btn-md" href="#signIn" data-toggle="modal">Sign In</a>
    				<a class="btn btn-default btn-md"	 href="#signUp" data-toggle="modal">Get Started</a>
    			</p>
				</div>
				<div class="col-sm-6 hidden-xs">
					<div class="device">
					</div>
				</div>
			</div>
		</div>
	</div>
  <div class="modal fade" id="signIn">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Sign In with your Username and Password</h4>
				</div>
				<div class="modal-body">
					<form class="signin-form" method="POST">
					  <div class="form-group">
					  	<input type="text" class="form-control-sm" name="username" id="username" placeholder="Username">
					  </div>
					  <div class="form-group">
					    <input type="password" class="form-control-sm" name="password" id="password" placeholder="Password">
					  </div>
					  <input type="submit" name="signin" class="btn btn-primary" value="Sign In">
					</form>
				</div>
			</div>
		</div>
	</div>
  <div class="modal fade" id="signUp">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Create a Username and Password</h4>
				</div>
				<div class="modal-body">
					<form class="signup-form" method="POST">
					  <div class="form-group">
					  	<input type="text" class="form-control-sm" name="newname" id="name" placeholder="Username">
					  </div>
					  <div class="form-group">
					    <input type="password" class="form-control-sm" name="new-password" id="new-password" placeholder="Password">
					  </div>
            <div class="form-group">
					  	<input type="password" class="form-control-sm" name="password-confirm" id="password-confirm" placeholder="Pass Confirm">
					  </div>
					  <input type="submit" name="addname" class="btn btn-primary" value="Get Started">
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
	 	<div class="row">
	 		<div class="col-sm-4">
	 			<b class="glyphicon glyphicon-user"></b>
	 			<h2>Get Started</h2>
			 	<p>
			 		Create an account with a Username and Password. Then make as many lists as you want. It's quick, easy, and FREE!! Takes 
			 		less than a minute to get started and have your first list on it's way.
			 	</p>
	    </div>
	    <div class="col-sm-4">
	    	<b class="glyphicon glyphicon-th-list"></b>
	    	<h2>Make Multiple Lists</h2>
			 	<p>
			 		Once you get started you can make as many lists as you want. Shopping, To Do, Goals, Christmas, Packing, Grocery, or
			 		any other list that you can think of.
			 	</p>
	 		</div>
	 		<div class="col-sm-4">
	 			<b class="glyphicon glyphicon-edit"></b>
	 			<h2>Edit With Ease</h2>
			 	<p>
			 		Editing your list is quick and easy. Remove single items with 1 click or touch, and clear your entire list and 
			 		start over with only 2 clicks.
			 	</p>
	 		</div>
	 	</div>
	</div>
<?php
include(ROOT_PATH . 'inc/footer.php');
?>