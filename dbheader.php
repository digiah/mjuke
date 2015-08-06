<?php
$dberror = 0;
$mysqli = mysqli_connect("host", "login", "pass", "dbname");
if (mysqli_connect_errno($mysqli)) {
  $dberror = 1;
}
mysqli_set_charset($mysqli,"utf8");

?>