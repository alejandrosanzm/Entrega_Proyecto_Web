<?php

include_once("./system/connection.php");
$sql = "SELECT id, icon ,name, age, stats FROM profiles";
$resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));

$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $value = $row["id"] ;
    echo '<div class="col-sm-4">
          <div rel="tooltip" title="Selecciona este perfil si tu carta es para '.$row["name"].'.">
              <input type="radio" id="'.$value.'" name="profile" value="'.$value.'">
              <label for="'.$value.'">
                <i class="material-icons" style="font-size:85px;">'.$row["icon"].'</i>
              </label>
              <h6>'.$row["name"].'</h6>
              <hr>
              <span style="font-size:16px;">'.$row["age"].'</span>
              <br>
              <span style="font-size:14px;">  '.$row["stats"].'</span>
          </div>
      </div>

    ';
  }
} else {
  echo "<p>No profiles!</p>";
}
?>
