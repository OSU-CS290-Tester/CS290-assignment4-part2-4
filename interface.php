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

    <?php
    if (isset($_POST["filter_category"])){
      $filterValue = $_POST["filter_category"];
      // echo "filter value" $filterValue;
    }
    else{
      $filterValue = 'All Movies';
      // echo "filter value" $filterValue;
    }
    ?>

    <form method="POST" action='interface.php'>
      <legend>Filter by Category:</legend>
      <select name ="filter_category">
        <option value="All Movies">All Movies</option>

        <?php
        $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "willardm-db", $myPassword, "willardm-db");

        if (!$mysqli || $mysqli->connect_errno){
          echo "Connnection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
        }
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
      <input type="submit" value="Apply Filter">
    </form>
  </p>
  <!-- CREATE THE TABLE -->
<table border="1">
  <tr>
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

  // Check for filtering
  if ($filterValue != 'All Movies'){
    $queryStmt = "SELECT id, name, category, length, rented FROM movieDB WHERE category = '" . $filterValue . "'";
  }
  else{
    $queryStmt = "SELECT id, name, category, length, rented FROM movieDB";
  }
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
      "</td>";
      if ($row[4] === '0'){
        echo "<td bgcolor='green'>" . $out_text . 
        "<form action='tableRefresh.php' method='POST'>
        <input type='hidden' name='id' value='$idVal'>
        <input type='submit' name='checkOut' value='Check Out'>
        </form></td>";
      }
      elseif($row[4] === '1'){
        echo "<td bgcolor='red'>" . $out_text .
        "<form action='tableRefresh.php' method='POST'>
        <input type='hidden' name='id' value='$idVal'>
        <input type='submit' name='checkIn' value='Check In'>
        </form></td>";
      }
      echo "<form action='tableRefresh.php' method='POST'>
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
<p>
  <form action='tableRefresh.php' method='POST'>
    <input type="submit" name="kill_em_all" value="Clear the Database"/>
  </form>
</p>

</body>
</html>
