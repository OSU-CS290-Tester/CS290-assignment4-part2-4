<?php
error_reporting(E_ALL);
ini_set('display_errors','On');
include 'storedInfo.php';

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "willardm-db", $myPassword, "willardm-db");

if (!$mysqli || $mysqli->connect_errno){
  echo "Connnection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
?>
<!DOCTYPE html>
<!--
Micheal Willard
Oregon State University
CS 290-400
Winter 2015
Assignment 4 Part 2
-->
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Assignment 4 Part 2</title>
</head>
<body>
  <!-- ************** -->
  <form method="POST" id="in_video" action="tableRefresh.php">
    <!-- *********** -->
    <fieldset>
      <legend>Add Video</legend>
      <p>
        <!-- <input type="hidden" name="action" value="add"/> -->
        <label>Name (required):</label>
        <input type="text" name="in_name" maxlength="255"/>
        <label>Category:</label>
        <input type="text" name="in_category" maxlength="255"/>
        <label>Length (in minutes):</label>
        <input type="number" name="in_length" min="1" max="400"/>
      </p>
      <input type="submit" name="add_item" value="Add to Database"/>
    </fieldset>
  </form>
  <p>
    <h2>Video Database</h2>
    <form method="POST" action='interface.php' form='category'>
      <legend>Filter by Category:</legend>
      <select name ="filter_category">
        <option>All Movies</option>
        <?php
        // Fetch the categories from the DB
        $get_categories = "SELECT DISTINCT category FROM movieDB";
        if($result = $mysqli->query($get_categories)){
          while($row = $result->fetch_row()){
            echo '<option name="filter_category" value="' . $row[0] . '">' . $row[0] . '</option>';
          }
        }
        $result->close();
        ?>
      </select>
      <input type="submit" id="category" name="filter_category" value="Apply Filter">
    </form>
  </p>
  <!-- CREATE THE TABLE -->
<table border="1">
  <tr>
    <!--<td>ID</td>-->
    <td>Title</td>
    <td>Category</td>
    <td>Length</td>
    <td>Availability</td>
    <td>Delete</td>
  </tr>

  <?php
  $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "willardm-db", $myPassword, "willardm-db");

  if (!$mysqli || $mysqli->connect_errno){
    echo "Connnection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
  }

  // if (!($tableOut = $mysqli->prepare("SELECT id, name, category, length, rented FROM movieDB"))) {
  //     echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
  // }
  //
  // if (!$tableOut->execute()) {
  //     echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
  // }
  //
  // $out_id    = NULL;
  // $out_name = NULL;
  // $out_category = NULL;
  // $out_length = NULL;
  // $out_avail = NULL; //0 for available, 1 for checked out
  //
  // if (!$tableOut->bind_result($out_id, $out_name, $out_category, $out_length, $out_avail)) {
  //     echo "Binding output parameters failed: (" . $stmt->errno . ") " . $tableOut->error;
  // }
  $queryStmt = "SELECT id, name, category, length, rented FROM movieDB";
  $tableOut = $mysqli->query($queryStmt);

  if ($tableOut->num_rows > 0){
    while ($row = $tableOut->fetch_row()) {
      if ($row[4] === '0'){
        $out_text = 'Available';
      }
      elseif ($row[4] === '1'){
        $out_text = 'Checked Out';
      }
      $idVal = $row[0];
      echo "<tr><td>" . $row[1] .
      "</td><td>". $row[2] .
      "</td><td>". $row[3] .
      "</td><td>". $out_text . $row[4];
      echo "<form action='tableRefresh.php method='POST'>
      <input type='hidden' name='id' value='$idVal'>
      <input type='submit' name='change_status' value='Change Status'>
      </form></td>";
      echo "<form action='tableRefresh.php method='POST'>
      <input type='hidden' name='id' value='$idVal'>
      <td><input type='submit' name='delete_entry' value='Delete Entry'>
      </form></td>";
    }
  }
  else{
    echo "The database is empty";
  }
  ?>
</table>
</body>
</html>
