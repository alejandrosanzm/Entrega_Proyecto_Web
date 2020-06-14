<?php
  // error_reporting(0);
  // session_start();
  if(isset($_SESSION["user"])) {
    require '../../system/connection.php';

      $sql = "SELECT id, name, icon, age FROM profiles;";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        echo '<tr>
            <td class="action"><input type="checkbox" name="cb_letter[]" value="'.$row["id"].'"/></td>
            <td class="name" style="max-width:70px;"><i class="fa fa-users">&nbsp;</i>'.$row["name"].'</td>
            <td class="subject">'.$row["age"].'</td></tr>';
          }
      }
    }
?>
