<?php 
session_start();

$userid = ($_SESSION['userid']);

require_once('../inc/config.php');
require_once(ROOT_PATH . 'inc/header.php');
require_once(ROOT_PATH . 'inc/database.php');

// Grabs NAME from grocery_name Table.
function user_name($userid) {
  require(ROOT_PATH . 'inc/database.php');
  
  try {
    $name = $db->prepare('SELECT name FROM user_name WHERE id = ?');
    $name->bindValue(1,$userid);
    $name->execute();
    foreach ($name as $nme) {
      $username = $nme['name'];
      return $username;
    }
  } catch (Exception $e) {
    echo "Data was not retrieved from the database successfully.";
    exit;
  }
}

if (isset($_POST['list'])) { 
	$listname = $_POST['list'];
}

if (isset($_POST['grocery'])) {
	$item = trim($_POST['grocery']);
	
	// Sends ITEM with matching NAME ID to ITEM Table.
	try {
		$sql = $db->prepare('INSERT INTO grocery_item (item,name_id,list) VALUES(?,?,?)');
		$sql->bindParam(1,$item);
		$sql->bindParam(2,$userid);
		$sql->bindParam(3,$listname);
		$sql->execute();
	} catch (Exception $e) {
		echo "Data was not submitted to the database successfully.";
		exit;
	}
}

// Adds a New List.
if (isset($_POST['newlist'])) {
  $newlist = trim($_POST['newlist']);
  
  if (!empty($newlist)) { 
    try {
      $sql = $db->prepare('INSERT INTO grocery_item (name_id,list) VALUES(?,?)');
      $sql->bindParam(1,$userid);
      $sql->bindParam(2,$newlist);
      $sql->execute();
    } catch (Exception $e) {
      echo 'Data was not submitted to the database successfully.';
      exit;
    }
  } 
}

// Renames a shopper.
if (isset($_POST['rename'])) {
  $rename = trim($_POST['rename']);
  
  if (!empty($rename)) { 
		try {
			$sql = $db->prepare('UPDATE user_name SET name = ? WHERE id = ?');
			$sql->bindParam(1,$rename);
			$sql->bindParam(2,$userid);
			$sql->execute();
		} catch (Exception $e) {
			echo 'Data was not submitted to the database successfully.';
			exit;
		}
  } 
}

if (isset($_REQUEST['action'])) {
	$action = $_REQUEST['action'];
	
	if ($action == 'newlist') {
		$prev = $_REQUEST['newlist'];
	
	} elseif (isset($_REQUEST['list'])) {
		$prev = $_REQUEST['list'];
	}
	
	} else {
	$prev = "Select";	
}
?>
<body>
	<div class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<h3 class="navbar-text pull-left">Easy List Maker</h3>
			<a href="<?php echo BASE_URL; ?>" class="navbar-text pull-right">Logout & Save</a>
		</div>
	</div>
	<div class="list-page">
	  <div class="container">
	  	<?php
	    	echo  '<div><span data-id=' . $userid . '>' . 
	    	'<h1>' . htmlspecialchars(user_name($userid)) . "'s List" . '</span>' . ' ' . '</h1>' . '</div>';
	    ?>
	   	<p class="lead">First select or create a list. Then start adding items.</p>
	   	<p class="lead">
    		<a class="btn btn-default btn-sm" href="#createList" data-toggle="modal">New List</a>
    		<a class="btn btn-default btn-sm" id="clear" href="">Clear List</a>
    		<a class="btn btn-default btn-sm"	id="deletelist" href="">Delete List</a>
    	</p>
	  </div>
	  <div class="modal fade modal" id="createList">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title">Enter the name of your new List!</h4>
					</div>
					<div class="modal-body">
						<form class="signin-form" method="POST">
						  <div class="form-group">
	              <input type="hidden" name="action" value="newlist">
						  	<input type="text" class="form-control-sm" name="newlist" id="newlist" autofocus="autofocus" placeholder="List Name">
						  </div>
						  <input type="submit" name="signin" class="btn btn-primary" value="Start">
						</form>
					</div>
				</div>
			</div>
		</div>
	  <div class="modal fade modal" id="editName">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title">Enter your new Username.</h4>
					</div>
					<div class="modal-body">
						<form class="signin-form" method="POST">
						  <div class="form-group">
						  	<input type="text" class="form-control-sm" name="rename" id="rename" autofocus="autofocus" placeholder="New Username">
						  </div>
						  <input type="submit" name="signin" class="btn btn-primary" value="Rename">
						</form>
					</div>
				</div>
			</div>
		</div>
		<div id="info">
	  <form method="POST" action="" class="item-form">
			<label for="list" class="my-label">Select List</label>
	    <?php
	    $cols = $db->prepare('SELECT DISTINCT(list) FROM grocery_item WHERE name_id = ? ORDER BY list ASC'); 
	    $cols->bindParam(1,$userid);
	    $cols->execute();?>
	    <select id="return" name="list">Select</option>
	      <option selected disabled>Select</option>
	      <?php foreach ($cols as $col) { ?>
	              <option value="<?php echo htmlspecialchars($col['list']); ?>" 
	              <?php if ( $col['list'] == "$prev") echo ' selected="selected"'; ?>>
	                <?php echo htmlspecialchars($col['list']); ?></option>
	      <?php } ?>
	    </select>
	    <input type="hidden" name="action" value="list">
	    <input type="submit" class="btn btn-primary btn-sm" name="submit" id="submit" value="Select">
		</div>
		<div>
	    <label for="grocery" class="my-label">Add Items</label>
	    <input type="hidden" name="action" value="additem"> 
	    <input type="text" autofocus="autofocus" name="grocery" id="grocery">
	    <input type="submit" class="btn btn-primary btn-sm" name="submit" id="submit" value="Add">
	  </form>
	  </div> 
	  <?php
	   echo  '<div><span data-lis=' . json_encode($prev) . '>' . 
	   '<h2 class="list-name">' . htmlspecialchars($prev) . '</span>' . ' ' . '</h2>' . '</div>';
	  ?>
	  <div class="paper">
			<ol>
				<?php 

				// Selects items from a user's specific list, and displays them.
				try {
				  $rows = $db->prepare('
						SELECT * FROM grocery_item 
						WHERE name_id = ? 
						AND list = ?
						ORDER BY id ASC
				    ');
				  $rows->bindParam(1,$userid);
				  $rows->bindParam(2,$listname);
				  $rows->execute();
				  foreach ($rows as $row) {
				    if (!empty($row['item'])) {
				      echo '<div class="fulllist"><span data-id=' . $row['id'] . '>' . 
				      '<li>' . htmlspecialchars($row['item']) . "</span> " . ' ' . 
				      '<a href="" class="byebye">Remove Item</a>' . '</li>' . '</div>' ;
				    }
				  }
				} catch (Exception $e) {
				    echo "Data was not retrieved from the database successfully.";
				    exit;
				}
				?>
	  	</ol>
		</div>
		<a class="btn btn-default btn-sm"	href="#editName" data-toggle="modal">Edit Username</a>
		<a href="#" class="btn btn-default btn-md" id="deletename">Delete My Account</a>
	</div>
<?php
include(ROOT_PATH . 'inc/footer.php');
?>