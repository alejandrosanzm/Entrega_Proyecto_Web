<?php
  /*
  // Ejemplo de fila
  <tr>
    <td class="action"><input type="checkbox" /></td>
    <td class="action"><i class="fa fa-heart-o"></i><span style="font-size:9px">10</span></td>
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
    require '../system/connection.php';
    require '../system/encrypt.php';

    $sql = "";
    if(isset($_POST["action"]) && $_POST["action"] == "filter") {
      $hospital = $_SESSION["hospital"];
      $doctor = $_SESSION["user"];

      echo '<tr><p>Filtrando por perfiles</p><tr>';
      foreach($_POST['cb_letter'] as $l) {
        $sql = "SELECT letters.id as lid, letters.writer, letters.letter, letters.date, letters.image, letters.public, profiles.id, profiles.name as profile
          FROM validated INNER JOIN letters ON validated.letter_id=letters.id INNER JOIN profiles ON letters.profile=profiles.id
          WHERE validated.hospital_id=".$hospital." AND validated.validated=1 AND letters.profile=".$l."
          ORDER BY letters.date DESC";

          $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                  while($row = $result->fetch_assoc()) {
                    $myBodyLetter = $row['public']==0?$desencriptar($row["letter"]):$row['letter'];

                    echo '<tr><td colspan="7"><p><i class="fa fa-users">&nbsp;</i>'.$row["profile"].'</p></td></tr>';
                    $totalLikes = 0;
                    $stmt = $conn->prepare("SELECT count(id) FROM internal_likes WHERE letter=? AND doctor = ANY(SELECT id FROM doctors WHERE hospital=?);");
                        $stmt->bind_param('ii', $row["lid"], $hospital);
                        $stmt->execute();
                        $stmt->bind_result($mtotalLikes);
                        $stmt->store_result();
                        if($stmt->num_rows == 1)  {
                          if($stmt->fetch()) {
                            $totalLikes = $mtotalLikes;
                          }
                        }

                        $realheard = '<i class="fa fa-heart-o"></i>';
                        $stmt = $conn->prepare("SELECT id FROM internal_likes WHERE letter=? AND doctor=?");
                            $stmt->bind_param('ii', $row["lid"], $doctor);
                            $stmt->execute();
                            $stmt->bind_result($mrealheard);
                            $stmt->store_result();
                            if($stmt->num_rows == 1)  {
                              if($stmt->fetch()) {
                                $realheard = '<i class="fa fa-heart" style="color:#880a0a;"></i>';
                              }
                            }

                    if(empty($row["image"])) {
                      echo '<tr>
                          <td class="action"><input type="checkbox" name="cb_letter[]" value="'.$row["lid"].'"/></td>
                          <td class="action">'.$realheard.'<span style="font-size:9px">'.$totalLikes.'</span></td>
                          <td class="name">'.$row["writer"].'</td>
                          <td class="subject" style="max-width:350px;" colspan="2">'.$myBodyLetter.'</td>
                          <!-- <td>Perfil:<br>'.$row["profile"].'</td> -->
                          <td class="time">'.date("d/m/Y H:i", strtotime($row["date"])).'</td>
                        </tr>';
                    } else {
                      echo '<tr>
                          <td class="action"><input type="checkbox" name="cb_letter[]" value="'.$row["lid"].'"/></td>
                          <td class="action">'.$realheard.'<span style="font-size:9px">'.$totalLikes.'</span></td>
                          <td class="name">'.$row["writer"].'</td>
                          <td class="subject" style="max-width:350px;">'.$myBodyLetter.'</td>
                          <!-- <td>Perfil:<br>'.$row["profile"].'</td> -->
                          <td><img style="max-width:300px;max-height:100px;" src="../res/img/'.$row["image"].'" alt="Dibujo de la carta"></td>
                          <td class="time">'.date("d/m/Y H:i", strtotime($row["date"])).'</td>
                        </tr>';
                    }
                  }
                }
      }
    } elseif (isset($_POST["action"]) && $_POST["action"] == "filterwo") {
      $hospital = $_SESSION["hospital"];
      $doctor = $_SESSION["user"];
      $skip = "";
      foreach($_POST['cb_letter'] as $l) {
        $skip = $skip." AND letters.profile NOT LIKE ".$l;
      }
      echo '<tr><p>Descartando por perfiles</p><tr>';

      $sql = "SELECT letters.id as lid, letters.writer, letters.letter, letters.date, letters.image, letters.public, profiles.id, profiles.name as profile
        FROM validated INNER JOIN letters ON validated.letter_id=letters.id INNER JOIN profiles ON letters.profile=profiles.id
        WHERE validated.hospital_id=".$hospital." AND validated.validated=1".$skip."
        ORDER BY letters.date DESC";

        $result = $conn->query($sql);
              if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                  $myBodyLetter = $row['public']==0?$desencriptar($row["letter"]):$row['letter'];

                  $totalLikes = 0;
                  $stmt = $conn->prepare("SELECT count(id) FROM internal_likes WHERE letter=? AND doctor = ANY(SELECT id FROM doctors WHERE hospital=?);");
                      $stmt->bind_param('ii', $row["lid"], $hospital);
                      $stmt->execute();
                      $stmt->bind_result($mtotalLikes);
                      $stmt->store_result();
                      if($stmt->num_rows == 1)  {
                        if($stmt->fetch()) {
                          $totalLikes = $mtotalLikes;
                        }
                      }

                      $realheard = '<i class="fa fa-heart-o"></i>';
                      $stmt = $conn->prepare("SELECT id FROM internal_likes WHERE letter=? AND doctor=?");
                          $stmt->bind_param('ii', $row["lid"], $doctor);
                          $stmt->execute();
                          $stmt->bind_result($mrealheard);
                          $stmt->store_result();
                          if($stmt->num_rows == 1)  {
                            if($stmt->fetch()) {
                              $realheard = '<i class="fa fa-heart" style="color:#880a0a;"></i>';
                            }
                          }

                  if(empty($row["image"])) {
                    echo '<tr>
                        <td class="action"><input type="checkbox" name="cb_letter[]" value="'.$row["lid"].'"/></td>
                        <td class="action">'.$realheard.'<span style="font-size:9px">'.$totalLikes.'</span></td>
                        <td class="name">'.$row["writer"].'</td>
                        <td class="subject" style="max-width:350px;" colspan="2">'.$myBodyLetter.'</td>
                        <td>Perfil:<br>'.$row["profile"].'</td>
                        <td class="time">'.date("d/m/Y H:i", strtotime($row["date"])).'</td>
                      </tr>';
                  } else {
                    echo '<tr>
                        <td class="action"><input type="checkbox" name="cb_letter[]" value="'.$row["lid"].'"/></td>
                        <td class="action">'.$realheard.'<span style="font-size:9px">'.$totalLikes.'</span></td>
                        <td class="name">'.$row["writer"].'</td>
                        <td class="subject" style="max-width:350px;">'.$myBodyLetter.'</td>
                        <td>Perfil:<br>'.$row["profile"].'</td>
                        <td><img style="max-width:300px;max-height:100px;" src="../res/img/'.$row["image"].'" alt="Dibujo de la carta"></td>
                        <td class="time">'.date("d/m/Y H:i", strtotime($row["date"])).'</td>
                      </tr>';
                  }
                }
              }
    } else {
      echo '<tr><td colspan="7" style="background-color:#f6f6f6;"><i class="fa fa-inbox">&nbsp;&nbsp;</i>Cartas validadas</td></tr>';
      $sql = "SELECT letters.id as lid, letters.writer, letters.letter, letters.date, letters.image, letters.public, profiles.id, profiles.name as profile
        FROM validated INNER JOIN letters ON validated.letter_id=letters.id INNER JOIN profiles ON letters.profile=profiles.id
        WHERE validated.hospital_id=".$_SESSION["hospital"]." AND validated.validated=1
        ORDER BY letters.date DESC";

        $result = $conn->query($sql);
              if ($result->num_rows > 0) {
                $hospital = $_SESSION["hospital"];
                $doctor = $_SESSION["user"];
                while($row = $result->fetch_assoc()) {
                  $myBodyLetter = $row['public']==0?$desencriptar($row["letter"]):$row['letter'];

                  $totalLikes = 0;
                  $stmt = $conn->prepare("SELECT count(id) FROM internal_likes WHERE letter=? AND doctor = ANY(SELECT id FROM doctors WHERE hospital=?);");
                      $stmt->bind_param('ii', $row["lid"], $hospital);
                      $stmt->execute();
                      $stmt->bind_result($mtotalLikes);
                      $stmt->store_result();
                      if($stmt->num_rows == 1)  {
                        if($stmt->fetch()) {
                          $totalLikes = $mtotalLikes;
                        }
                      }

                      $realheard = '<i class="fa fa-heart-o"></i>';
                      $stmt = $conn->prepare("SELECT id FROM internal_likes WHERE letter=? AND doctor=?");
                          $stmt->bind_param('ii', $row["lid"], $doctor);
                          $stmt->execute();
                          $stmt->bind_result($mrealheard);
                          $stmt->store_result();
                          if($stmt->num_rows == 1)  {
                            if($stmt->fetch()) {
                              $realheard = '<i class="fa fa-heart" style="color:#880a0a;"></i>';
                            }
                          }

                  if(empty($row["image"])) {
                    echo '<tr>
                        <td class="action"><input type="checkbox" name="cb_letter[]" value="'.$row["lid"].'"/></td>
                        <td class="action">'.$realheard.'<span style="font-size:9px">'.$totalLikes.'</span></td>
                        <td class="name">'.$row["writer"].'</td>
                        <td class="subject" style="max-width:350px;" colspan="2">'.$myBodyLetter.'</td>
                        <td>Perfil:<br>'.$row["profile"].'</td>
                        <td class="time">'.date("d/m/Y H:i", strtotime($row["date"])).'</td>
                      </tr>';
                  } else {
                    echo '<tr>
                        <td class="action"><input type="checkbox" name="cb_letter[]" value="'.$row["lid"].'"/></td>
                        <td class="action">'.$realheard.'<span style="font-size:9px">'.$totalLikes.'</span></td>
                        <td class="name">'.$row["writer"].'</td>
                        <td class="subject" style="max-width:350px;">'.$myBodyLetter.'</td>
                        <td>Perfil:<br>'.$row["profile"].'</td>
                        <td><img style="max-width:300px;max-height:100px;" src="../res/img/'.$row["image"].'" alt="Dibujo de la carta"></td>
                        <td class="time">'.date("d/m/Y H:i", strtotime($row["date"])).'</td>
                      </tr>';
                  }
                }
              } else {
                echo "<tr><td colspan='6'><p>No se encuentran más cartas validadas en este hospital.</p></td></tr>";
              }
    }
        // $conn->close();
  }
?>
