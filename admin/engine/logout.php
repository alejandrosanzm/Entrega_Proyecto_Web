<?php
  // error_reporting(0);
  session_start();
  unset($_SESSION["user"]);
  unset($_SESSION["level"]);
  unset($_SESSION["hospital"]);
  session_destroy();
  header("Location: ../../");
?>
