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

$filePath = explode('/', $_SERVER['PHP_SELF'], -1);
$filePath = implode('/', $filePath);
$redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
$redirect1 = $redirect . '/interface.php';
$goBackText = "<p>Click <a href=\"$redirect1\">here</a> to go back.";

// Parse the POST request to check which function was requested
if(isset($_POST["add_item"])){
  // echo "Adding Item";
  addItem($_POST["in_name"], $_POST["in_category"], $_POST["in_length"]);
}
if(isset($_POST["change_status"])){
  changeStatus($_POST["id"]);
}
if(isset($_POST["delete_entry"])){
  deleteEntry($_POST["id"]);
}

//*****  FUNCTION DEFINITIONS *****

function addItem($varName, $varCat, $varLen){
  $filePath = explode('/', $_SERVER['PHP_SELF'], -1);
  $filePath = implode('/', $filePath);
  $redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
  $redirect1 = $redirect . '/interface.php';
  $goBackText = "<p>Click <a href=\"$redirect1\">here</a> to go back.";
  // Check for Required Fields
  if($varName === NULL){
    echo "The <b>Name</b> field was left blank.<br>";
    echo $goBackText;
  }
  elseif($varCat === NULL){
    echo "The <b>Category</b> field was left blank.<br>";
    echo $goBackText;
  }
  elseif($varLen === NULL){
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
    echo "This Film was added to the database.";
    echo "<p>Click <a href=\"$redirect1\">here</a> to go back.";
  }
}

function changeStatus($varId){

}

function deleteEntry($varId){

}

?>
