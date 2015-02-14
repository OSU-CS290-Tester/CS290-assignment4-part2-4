msqli::__construct() ([ string $host =
ini_get("mysqli.default_host") {, string $username =
}])

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "willardm-db", "d5F4Pqrfv3YF9nBt", "willardm-db");

if(!$mysqli || $mysqli->connect_errno){
  echo "Connnection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

if($mysqli->query("CREATE TABLE foo(bar INT PRIMARY KEY, baz VARCHAR(10))") === TRUE){
  printf("Table foo successfully created.\n");
}

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
  
</body>
</html>
