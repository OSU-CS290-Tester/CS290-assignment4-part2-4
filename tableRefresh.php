<?php
error_reporting(E_ALL);
ini_set('display_errors','On');
// storedInfo Password only works on one page?
// include 'storedInfo.php';
// Micheal Willard
// Oregon State University
// CS 290-400
// Winter 2015
// Assignment 4 Part 2

// Parse the POST request to check which function was requested
if(isset($_POST["add_item"])){
  addItem($_POST["in_name"], $_POST["in_category"], $_POST["in_length"]);
}
if(isset($_POST["checkIn"])){
  checkIn($_POST["id"]);
}
if(isset($_POST["checkOut"])){
  checkOut($_POST["id"]);
}
if(isset($_POST["delete_entry"])){
  echo $_POST["id"];
  deleteEntry($_POST["id"]);
}
if(isset($_POST["kill_em_all"])){
  killEmAll();
}

//************  FUNCTION DEFINITIONS ************

// Function to add the new item
// Triggered by form action on interface.php.
// Receives the required data from interface.php.
function addItem($varName, $varCat, $varLen){
  $filePath = explode('/', $_SERVER['PHP_SELF'], -1);
  $filePath = implode('/', $filePath);
  $redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
  $redirect1 = $redirect . '/interface.php';
  $goBackText = "<p>Click <a href=\"$redirect1\">here</a> to go back.";
  // Check for Required Fields
  if($varName == NULL){
    echo "The <b>Name</b> field was left blank.<br>";
    echo $goBackText;
  }
  elseif($varCat == NULL){
    echo "The <b>Category</b> field was left blank.<br>";
    echo $goBackText;
  }
  elseif($varLen == NULL){
    echo "The <b>Length</b> field was left blank.<br>";
    echo $goBackText;
  }
  else{
    // Connect to the DB
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "willardm-db", "d5F4Pqrfv3YF9nBt", "willardm-db");
    if(!$mysqli || $mysqli->connect_errno){
      echo "Connnection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
    }
    // Connection Passed

    if(!$stmtAdd = $mysqli->prepare("INSERT INTO movieDB(name, category, length) VALUES (?, ?, ?)")){
      echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    /* Prepared statement, stage 2: bind and execute */
    if (!$stmtAdd->bind_param("ssi", $varName, $varCat, $varLen)) {
      echo "Binding parameters failed: (" . $stmtAdd->errno . ") " . $stmtAdd->error;
    }

    if (!$stmtAdd->execute()) {
      echo "Execute failed: (" . $stmtAdd->errno . ") " . $stmtAdd->error;
    }
    echo "This item was added to the database.";
    echo "<p>Click <a href=\"$redirect1\">here</a> to go back.";
  }
}

//**********************************************
// Function to chnge the status of an entry.
// Triggered by button action on interface.php.
// Get's passed the unique id of the item and the current status.
function checkIn($varId){
  $filePath = explode('/', $_SERVER['PHP_SELF'], -1);
  $filePath = implode('/', $filePath);
  $redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
  $redirect1 = $redirect . '/interface.php';
  $goBackText = "<p>Click <a href=\"$redirect1\">here</a> to go back.";

  // Connect to the DB
  $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "willardm-db", "d5F4Pqrfv3YF9nBt", "willardm-db");

  if(!$mysqli || $mysqli->connect_errno){
    echo "Connnection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
  }
  // Connection Passed

  if(!$stmtStatus = $mysqli->prepare("UPDATE movieDB SET rented = 0 WHERE id = ?")){
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }
  /* Prepared statement, stage 2: bind and execute */
  if (!$stmtStatus->bind_param("i", $varId)) {
    echo "Binding parameters failed: (" . $stmtStatus->errno . ") " . $stmtStatus->error;
  }

  if (!$stmtStatus->execute()) {
    echo "Execute failed: (" . $stmtStatus->errno . ") " . $stmtStatus->error;
  }
  echo "This item was updated as Checked In.";
  echo "<p>Click <a href=\"$redirect1\">here</a> to go back.";
}

//**********************************************
// Function to chnge the status of an entry.
// Triggered by button action on interface.php.
// Get's passed the unique id of the item and the current status.
function checkOut($varId){
  $filePath = explode('/', $_SERVER['PHP_SELF'], -1);
  $filePath = implode('/', $filePath);
  $redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
  $redirect1 = $redirect . '/interface.php';
  $goBackText = "<p>Click <a href=\"$redirect1\">here</a> to go back.";

  // Connect to the DB
  $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "willardm-db", "d5F4Pqrfv3YF9nBt", "willardm-db");

  if(!$mysqli || $mysqli->connect_errno){
    echo "Connnection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
  }
  // Connection Passed

  if(!$stmtStatus = $mysqli->prepare("UPDATE movieDB SET rented = 1 WHERE id = ?")){
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }
  /* Prepared statement, stage 2: bind and execute */
  if (!$stmtStatus->bind_param("i", $varId)) {
    echo "Binding parameters failed: (" . $stmtStatus->errno . ") " . $stmtStatus->error;
  }

  if (!$stmtStatus->execute()) {
    echo "Execute failed: (" . $stmtStatus->errno . ") " . $stmtStatus->error;
  }
  echo "This item was updated as Checked Out.";
  echo "<p>Click <a href=\"$redirect1\">here</a> to go back.";
}

//**********************************************
// Function to delete an entry.
// Triggered by button action on interface.php.
// Get's passed the unique id of the item.
function deleteEntry($varId){
  $filePath = explode('/', $_SERVER['PHP_SELF'], -1);
  $filePath = implode('/', $filePath);
  $redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
  $redirect1 = $redirect . '/interface.php';
  $goBackText = "<p>Click <a href=\"$redirect1\">here</a> to go back.";

  // Connect to the DB
  $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "willardm-db", "d5F4Pqrfv3YF9nBt", "willardm-db");

  if(!$mysqli || $mysqli->connect_errno){
    echo "Connnection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
  }
  // Connection Passed

  if(!$stmtDel = $mysqli->prepare("DELETE FROM movieDB WHERE id = ?")){
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }
  /* Prepared statement, stage 2: bind and execute */
  if (!$stmtDel->bind_param("i", $varId)) {
    echo "Binding parameters failed: (" . $stmtDel->errno . ") " . $stmtDel->error;
  }

  if (!$stmtDel->execute()) {
    echo "Execute failed: (" . $stmtDel->errno . ") " . $stmtDel->error;
  }
  echo $varId;
  echo "This item was deleted from the database.";
  echo "<p>Click <a href=\"$redirect1\">here</a> to go back.";
}


//**********************************************
// Function to delete all entried in the database.
// Triggered by button action on interface.php.
// No parameters to pass.
function killEmAll(){
  $filePath = explode('/', $_SERVER['PHP_SELF'], -1);
  $filePath = implode('/', $filePath);
  $redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
  $redirect1 = $redirect . '/interface.php';
  $goBackText = "<p>Click <a href=\"$redirect1\">here</a> to go back.";

  // Connect to the DB
  $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "willardm-db", "d5F4Pqrfv3YF9nBt", "willardm-db");

  if(!$mysqli || $mysqli->connect_errno){
    echo "Connnection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
  }
  // Connection Passed
  if(!$stmtDel = $mysqli->prepare("TRUNCATE movieDB")){
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }
  if (!$stmtDel->execute()) {
    echo "Execute failed: (" . $stmtDel->errno . ") " . $stmtDel->error;
  }
  echo "The database was emptied.";
  echo "<p>Click <a href=\"$redirect1\">here</a> to go back.";
}
?>
