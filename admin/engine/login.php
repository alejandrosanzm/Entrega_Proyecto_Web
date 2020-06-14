<?php
  // error_reporting(0);
  if(isset($_POST["submit"])) {
    $f_user = $_POST["user"];
    $f_password = hash('sha256', $_POST["password"]);
    require '../../system/connection.php';

    $stmt = $conn->prepare("SELECT id, level, hospital FROM doctors WHERE BINARY user=? AND password=? LIMIT 1");
    $stmt->bind_param('ss', $f_user, $f_password);
    $stmt->execute();
    $stmt->bind_result($id, $level, $hospital);
    $stmt->store_result();
    if($stmt->num_rows == 1)  {
      if($stmt->fetch()) {
        session_start();
        $_SESSION["user"] = $id;
        $_SESSION["level"] = $level;
        $_SESSION["hospital"] = $hospital;
        header("Location: ../");
      }
    } else {
      header("Location: ../login.php?error=true");
    }
  }
?>
