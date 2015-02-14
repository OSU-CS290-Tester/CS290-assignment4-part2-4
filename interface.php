<?php
error_reporting(E_ALL);
ini_set('display_errors','On');
include 'storedInfo.php';

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "willardm-db", $myPassword, "willardm-db");

if(!$mysqli || $mysqli->connect_errno){
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
  <form method="POST" id="in_video" action="tableRefresh.php">
    <fieldset>
      <legend>Add Video</legend>
      <p>
        <label>Name (required):</label>
        <input type="text" name="in_name"/>
        <label>Category:</label>
        <input type="text" name="in_category"/>
        <label>Length (in minutes):</label>
        <input type="text" name="in_length"/>
      </p>
      <input type="submit" name="in_submit" value="Add to Database"/>
    </fieldset>
  </form>
  <p>
    <h2>Video Database</h2>
    <form method="POST">
      <legend>Filter by Category:</legend>
      <select>
        <option name="filter_category" value="All Movies">All Movies</option>
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
    </form>
  </p>
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
  $out_id    = NULL;
  $out_name = NULL;
  $out_category = NULL;
  $out_length = NULL;
  $out_avail = NULL; //0 for available, 1 for checked out

  if (!$stmt->bind_result($out_id, $out_name, $out_category, $out_length, $out_avail)) {
      echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  while ($stmt->fetch()) {
    if($out_avail === 0){
      $out_text = 'Available';
    }
    elseif($out_avail === 1){
      $out_text = 'Checked Out';
    }
      printf("<tr>
      <td>%s</td>
      <td>%s</td>
      <td>%i</td>
      <td>%s
      <button type=\"submit\" name=\"change_status\" value=\"{$out_id}\">
      Check In/Out</button></td>
      <td><button type=\"submit\" name=\"delete_entry\" value=\"{$out_id}\">
      Delete</button></td>
      ", $out_name, $out_category, $out_length, $out_text);
  }
  $stmt->close();
  ?>
</table>
</body>
</html>
