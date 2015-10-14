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
	<a href="<?php echo BASE_URL; ?>" class="logout">Logout & Save</a>
  <div class="top">
   <?php
    echo  '<div><span data-id=' . $userid . '>' . 
    '<h1>' . htmlspecialchars(user_name($userid)) . "'s List" . '</span>' . ' ' . '</h1>' . '</div>';?>
   <p>First select or create a list, then start adding items.</p>
  </div>
  <img id="shoppingcart2" src="../img/1440454195_Shopping cart.png" width="128" height="109" alt="Shopping Cart">
	<img id="notepad2" src="../img/1441743044_kwrite.png" width="128" height="128" alt="Notepad">
	<div>
		<a href="" id="newlist">Create List</a>
	</div>
	<div class="list">
	<form method="POST">
		<label for="newlist">Create List</label>
		<input type="hidden" name="action" value="newlist">
		<input type="text" name="newlist" id="newlist">
		<input type="submit" name="submit" id="submit" value="Add List">
	</form>
	</div>
  <form method="POST">
  <div>
    <a href="" id="changename">Edit Username</a>
  </div>
  <div class="tog">
    <label for="rename">Rename</label>
    <input type="hidden" name="action" value="editname">
    <input type="text" name="rename" id="rename">
    <input type="submit" name="submit" id="submit" value="Rename">
	</div>
  </form>
	<div>
		<a href="" class="deletelist">Delete List</a>
	</div>
	<div id="info">
  <form method="POST" action="" class="item-form">
		<label for="list">Select List</label>
    <?php
    $cols = $db->prepare('SELECT DISTINCT(list) FROM grocery_item WHERE name_id = ? ORDER BY list ASC'); 
    $cols->bindParam(1,$userid);
    $cols->execute();?>
    <select id="return" name="list" >Select</option>
      <option selected disabled>Select</option>
      <?php foreach ($cols as $col) { ?>
              <option value="<?php echo htmlspecialchars($col['list']); ?>" 
              <?php if ( $col['list'] == "$prev") echo ' selected="selected"'; ?>>
                <?php echo htmlspecialchars($col['list']); ?></option>
      <?php } ?>
    </select>
    <input type="hidden" name="action" value="list">
    <input type="submit" name="submit" id="submit" value="Select">
	</div>
	<div>
    <label for="grocery">Add Items</label>
    <input type="hidden" name="action" value="additem"> 
    <input type="text" autofocus="autofocus" name="grocery" id="grocery">
    <input type="submit" name="submit" class="submit" id="submit" value="Add">
  </form>
  </div> 
  <div>
    <a href="" class="clear">Clear List</a>
  </div>
  <?php
   echo  '<div><span data-lis=' . json_encode($listname) . '>' . 
   '<h2>' . htmlspecialchars($listname) . '</span>' . ' ' . '</h2>' . '</div>';?>
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
	<a href="#" class="deletename">Delete My Account</a>
<?php
include(ROOT_PATH . 'inc/footer.php');
?>