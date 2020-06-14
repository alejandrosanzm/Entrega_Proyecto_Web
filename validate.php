<?php
require './system/connection.php';

if(!empty($_POST['hospitals'])) {

  $myHospitals = is_iterable($_POST['hospitals']) ? $_POST['hospitals'] : explode(",", $_POST['hospitals']);

  echo "\n\n\n\nHOS: ".$_POST['hospitals']."\n\n\n\n";

  $checked_count = count($myHospitals);

  $sql = "SELECT id FROM letters WHERE id=(SELECT max(id) FROM letters)";
  $resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));

  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $letter_id =(int) $row["id"];
      echo '<br>la id del post es: ' .$letter_id. '<br>';
      foreach($myHospitals as $selected) {
        $hospital_id = (int)$selected;
          echo '<br>la id del hospital es: ' .$selected. '<br>';
        $stmt = $conn->prepare("INSERT INTO `validated` (`id`, `letter_id`, `hospital_id`, `validated`)
          VALUES (NULL, ?, ? , 0 );");
        $stmt->bind_param('ii', $letter_id, $hospital_id);
        $stmt->execute();
        $post_id = $conn->insert_id;

      }
    }
  }
}

?>
