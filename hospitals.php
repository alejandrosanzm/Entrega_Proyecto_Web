<?php

include_once("./system/connection.php");
$sql = "SELECT id ,name FROM hospitals";
$resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));

$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {

    echo '		<input type="checkbox" name="hospitals[]" id="box-'.$row["id"].'" value="'.$row["id"].'" checked>
        <label for="box-'.$row["id"].'">'.$row["name"].'</label>









    ';
  }
} else {
  echo "<p>No hospitals!</p>";
}
?>
