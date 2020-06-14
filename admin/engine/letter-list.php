<?php
  /*
  // Ejemplo de fila
  <tr>
    <td class="action"><input type="checkbox" /></td>
    <td class="action"><i class="fa fa-heart-o"></i><span style="font-size:9px">7</span></td>
    <!-- <td class="action"><i class="fa fa-bookmark-o"></i></td> -->
    <td class="name">Larry Gardner</td>
    <td class="subject" style="max-width:350px;">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sedLorem ipsum dolor sit amet, consectetur adipisicing elit, sedLorem ipsum dolor sit amet, consectetur adipisicing elit, sed</td>
    <td><img style="max-width:300px;max-height:100px;" src="img.jpg" alt=""><td>
    <td class="time">08:30 PM</td>
  </tr>
  */
?>

<?php
  // error_reporting(0);
  if(isset($_SESSION["user"])) {
    require '../../system/connection.php';
    require '../../system/encrypt.php';

    $sql = "SELECT letters.id, letters.writer, letters.letter, letters.date, letters.public, letters.image, profiles.name as profile, profiles.id as profileId
      FROM validated INNER JOIN letters ON validated.letter_id=letters.id INNER JOIN profiles ON letters.profile=profiles.id
      WHERE validated.validated=0 AND validated.hospital_id=".$_SESSION["hospital"]." ORDER BY letters.date DESC";

      $result = $conn->query($sql);
            if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                $myBodyLetter = $row['public']==0?$desencriptar($row["letter"]):$row['letter'];

                if(empty($row["image"])) {
                  echo '<tr>
                      <td class="action"><input type="checkbox" name="cb_letter[]" value="'.$row["id"].'"/></td>
                      <td class="name">'.$row["writer"].'</td>
                      <td class="subject" style="max-width:350px;" colspan="2">'.$myBodyLetter.'</td>
                      <td>Perfil:<br>'.$row["profile"].'</td>
                      <td class="time">'.date("d/m/Y H:i", strtotime($row["date"])).'</td>
                    </tr>';
                } else {
                  echo '<tr>
    									<td class="action"><input type="checkbox" name="cb_letter[]" value="'.$row["id"].'"/></td>
    									<td class="name">'.$row["writer"].'</td>
    									<td class="subject" style="max-width:350px;">'.$myBodyLetter.'</td>
                      <td>Perfil:<br>'.$row["profile"].'</td>
                      <td><img style="max-width:300px;max-height:100px;" src="../../res/img/'.$row["image"].'" alt="Imagen de la carta"></td>
                      <td class="time">'.date("d/m/Y H:i", strtotime($row["date"])).'</td>
    								</tr>';
                }
              }
            } else {
              echo "<tr><td colspan='6'><p>No quedan cartas por validar.</p></td></tr>";
            }
        $conn->close();
  }
?>
