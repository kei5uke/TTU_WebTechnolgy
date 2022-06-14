<?php
$servername_sql = "anysql.itcollege.ee";
$username_sql = "team14";
$password_sql = "e80c4041016a";

$connection = mysqli_connect($servername_sql, $username_sql, $password_sql);
mysqli_select_db ($connection, "WT_14");
if (!$connection) {
  die("Connection failed: " . mysqli_connect_error());
}
?>