<?php
  // error_reporting(0);
  $server = "127.0.0.1";
  $user = "root";
  $password = "";
  $database = "letters";

  $conn = mysqli_connect($server, $user, $password, $database) or die("Connection failed: " . mysqli_connect_error());
  $conn->set_charset("utf8")
?>
