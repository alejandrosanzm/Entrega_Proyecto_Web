<?php
  // error_reporting(0);
  // session_start();
  if(isset($_SESSION["user"])) {
    require '../../system/connection.php';
    $hospital = $_SESSION["hospital"];

    // Top hospitals - by n letters
    $sql = "SELECT COUNT(hospitals.id) as n, hospitals.id, hospitals.name
      FROM validated
      INNER JOIN hospitals ON validated.hospital_id=hospitals.id
      WHERE validated.validated=1
      GROUP BY hospitals.id
      ORDER BY n DESC LIMIT 5";

      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        $i = 1;
        $intop = false;
        echo '<tr><td colspan="7" style="background-color:#f6f6f6;"><i class="fa fa-line-chart">&nbsp;&nbsp;</i>Top hospitales</td></tr>';
        while($row = $result->fetch_assoc()) {
          if($row["id"]=$hospital) {
            $intop = true;
          }

          $ticon = ($i<=3)?"<i class='fa fa-trophy'>&nbsp;</i>":"<i class='fa fa-hospital-o'>&nbsp;</i>";
          echo '<tr>
              <td class="action" colspan="2">'.$ticon.$i.'&nbsp;&nbsp;</td>
              <td class="action"colspan="2"><span style="font-size:10px">'.$row["name"].'</span></td>
              <td class="name" colspan="7"><span style="font-size:10px">'.$row["n"].' cartas</span></td>
            </tr>';
            $i++;
        }
      }

      // Total cartas en tu hospital
      $letterscount = 0;
      $sql = "SELECT COUNT(id) as n FROM validated WHERE hospital_id=? AND validated=1;";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param('i', $hospital);
      $stmt->execute();
      $stmt->bind_result($mletterscount);
      $stmt->store_result();
      if($stmt->num_rows == 1)  {
        if($stmt->fetch()) {
          $letterscount = $mletterscount;
        }
      }
      if(!$intop) {
        echo '<tr>
            <td class="action" style="max-width:7px;"><i class="fa fa-user-md"></i></td>
            <td class="action style="max-width:30px;"><span style="font-size:10px">Tu hospital</span></td>
            <td class="name" colspan="7"><span style="font-size:10px">'.$letterscount.' cartas</span></td>
          </tr>';
      }

    // Letters, depends
    // Last shared letters
    if(isset($_GET["all"])) {
      echo '<tr><td colspan="7" style="background-color:#f6f6f6;"><i class="fa fa-bullhorn">&nbsp;&nbsp;</i>Últimas cartas compartidas<p style="float:right;"><a href="shared.php">Top Cartas</a>&nbsp;|&nbsp;<a href="shared.php?all=true">Últimas Cartas</a></p></td></tr>';
      $sql = "SELECT letters.shared, letters.id as lid, letters.writer, letters.letter, letters.shared as shareddoctor, letters.shared_date as date, letters.image, letters.public, profiles.id, profiles.name as profile
        FROM letters INNER JOIN profiles ON letters.profile=profiles.id
        WHERE shared!=0 ORDER BY letters.shared_date DESC";

        $result = $conn->query($sql);
              if ($result->num_rows > 0) {
                $hospital = $_SESSION["hospital"];
                $doctor = $_SESSION["user"];
                while($row = $result->fetch_assoc()) {
                  $shareddoctor = $row["shareddoctor"];
                  $myBodyLetter = $row['public']==0?$desencriptar($row["letter"]):$row['letter'];

                  $totalLikes = 0;
                  $stmt = $conn->prepare("SELECT COUNT(id) FROM internal_likes WHERE letter=?");
                      $stmt->bind_param('i', $row["lid"]);
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

                          $phrase = '';
                          // Quién lo ha compartido
                          if($shareddoctor==$_SESSION["user"]) {
                            $phrase = '<p style="font-size:9px;"><i class="fa fa-retweet">&nbsp;</i>Por ti.</p>';
                          } else {
                            $sharedname = "";
                            $sharedhosp = "";
                            $sql = "SELECT doctors.name as sharedname, hospitals.name as sharedhosp FROM doctors INNER JOIN hospitals ON doctors.hospital=hospitals.id
                              WHERE doctors.id=?";
                              $stmt = $conn->prepare($sql);
                                  $stmt->bind_param('i', $shareddoctor);
                                  $stmt->execute();
                                  $stmt->bind_result($msharedname, $msharedhosp);
                                  $stmt->store_result();
                                  if($stmt->num_rows == 1)  {
                                    if($stmt->fetch()) {
                                      $sharedname = $msharedname;
                                      $sharedhosp = $msharedhosp;
                                    }
                                  }
                                  $phrase = '<p style="font-size:9px;"><i class="fa fa-retweet">&nbsp;</i>Por '.$sharedname.', en '.$sharedhosp.'.</p>';
                          }

                  if(empty($row["image"])) {
                    echo '<tr>
                        <td class="action"><input type="checkbox" name="cb_letter[]" value="'.$row["lid"].'"/></td>
                        <td class="action">'.$realheard.'<span style="font-size:9px">'.$totalLikes.'</span></td>
                        <td class="name">'.$row["writer"].'</td>
                        <td class="subject" style="max-width:350px;" colspan="2">'.$myBodyLetter.'</td>
                        <td>Perfil:<br>'.$row["profile"].'</td>
                        <td class="time">Compartido:<br>'.date("d/m/Y H:i", strtotime($row["date"])).'</td>
                      </tr> <tr><td colspan="7" style="line-height:1;border:0;">'.$phrase.'</td></tr>';
                  } else {
                    echo '<tr>
                        <td class="action"><input type="checkbox" name="cb_letter[]" value="'.$row["lid"].'"/></td>
                        <td class="action">'.$realheard.'<span style="font-size:9px">'.$totalLikes.'</span></td>
                        <td class="name">'.$row["writer"].'</td>
                        <td class="subject" style="max-width:350px;">'.$myBodyLetter.'</td>
                        <td>Perfil:<br>'.$row["profile"].'</td>
                        <td><img style="max-width:300px;max-height:100px;" src="../../res/img/'.$row["image"].'" alt="Dibujo de la carta"></td>
                        <td class="time">Compartido:<br>'.date("d/m/Y H:i", strtotime($row["date"])).'</td>
                      </tr> <tr><td colspan="7" style="line-height:1;border:0;">'.$phrase.'</td></tr>';
                  }
                }
              } else {
                echo "<tr><td colspan='6'><p>Aun no hay cartas compartidas.</p></td></tr>";
              }



    } else {
      // Top Letters
      echo '<tr><td colspan="7" style="background-color:#f6f6f6;"><i class="fa fa-thumbs-up">&nbsp;&nbsp;</i>Cartas más gustadas<p style="float:right;"><a href="shared.php">Top Cartas</a>&nbsp;|&nbsp;<a href="shared.php?all=true">Últimas Cartas</a></p></td></tr>';
      $sql = "SELECT COUNT(letters.id) as n, letters.id as lid, letters.writer, letters.letter, letters.shared as shareddoctor, letters.shared_date as date, letters.image, letters.public, profiles.id, profiles.name as profile
        FROM internal_likes
        INNER JOIN letters ON internal_likes.letter=letters.id
        INNER JOIN profiles ON letters.profile=profiles.id
        WHERE letters.shared!=0
        GROUP BY letters.id
        ORDER BY n DESC LIMIT 5";

        $result = $conn->query($sql);
              if ($result->num_rows > 0) {
                $hospital = $_SESSION["hospital"];
                $doctor = $_SESSION["user"];
                while($row = $result->fetch_assoc()) {
                  $shareddoctor = $row["shareddoctor"];
                  $myBodyLetter = $row['public']==0?$desencriptar($row["letter"]):$row['letter'];

                  // Total de likes entre todos los hospitales
                  $totalLikes = 0;
                  // SELECT count(id) FROM internal_likes WHERE letter=? AND doctor = ANY(SELECT id FROM doctors WHERE hospital=?);
                  $stmt = $conn->prepare("SELECT COUNT(id) FROM internal_likes WHERE letter=?");
                      $stmt->bind_param('i', $row["lid"]);
                      $stmt->execute();
                      $stmt->bind_result($mtotalLikes);
                      $stmt->store_result();
                      if($stmt->num_rows == 1)  {
                        if($stmt->fetch()) {
                          $totalLikes = $mtotalLikes;
                        }
                      }

                      // Si el médico usuario ha compartido
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

                        $phrase = '';
                        // Quién lo ha compartido
                        if($shareddoctor==$_SESSION["user"]) {
                          $phrase = '<p style="font-size:9px;"><i class="fa fa-retweet">&nbsp;</i>Por ti.</p>';
                        } else {
                          $sharedname = "";
                          $sharedhosp = "";
                          $sql = "SELECT doctors.name as sharedname, hospitals.name as sharedhosp FROM doctors INNER JOIN hospitals ON doctors.hospital=hospitals.id
                            WHERE doctors.id=?";
                            $stmt = $conn->prepare($sql);
                                $stmt->bind_param('i', $shareddoctor);
                                $stmt->execute();
                                $stmt->bind_result($msharedname, $msharedhosp);
                                $stmt->store_result();
                                if($stmt->num_rows == 1)  {
                                  if($stmt->fetch()) {
                                    $sharedname = $msharedname;
                                    $sharedhosp = $msharedhosp;
                                  }
                                }
                                $phrase = '<p style="font-size:9px;"><i class="fa fa-retweet">&nbsp;</i>Por '.$sharedname.', en '.$sharedhosp.'.</p>';
                        }


                  if(empty($row["image"])) {
                    echo '<tr>
                        <td class="action"><input type="checkbox" name="cb_letter[]" value="'.$row["lid"].'"/></td>
                        <td class="action">'.$realheard.'<span style="font-size:9px">'.$totalLikes.'</span></td>
                        <td class="name">'.$row["writer"].'</td>
                        <td class="subject" style="max-width:350px;" colspan="2">'.$myBodyLetter.'</td>
                        <td>Perfil:<br>'.$row["profile"].'</td>
                        <td class="time">Compartido:<br>'.date("d/m/Y H:i", strtotime($row["date"])).'</td>
                      </tr> <tr><td colspan="7" style="line-height:0;border:0;">'.$phrase.'</td></tr>';
                  } else {
                    echo '<tr>
                        <td class="action"><input type="checkbox" name="cb_letter[]" value="'.$row["lid"].'"/></td>
                        <td class="action">'.$realheard.'<span style="font-size:9px">'.$totalLikes.'</span></td>
                        <td class="name">'.$row["writer"].'</td>
                        <td class="subject" style="max-width:350px;">'.$myBodyLetter.'</td>
                        <td>Perfil:<br>'.$row["profile"].'</td>
                        <td><img style="max-width:300px;max-height:100px;" src="../../res/img/'.$row["image"].'" alt="Dibujo de la carta"></td>
                        <td class="time">Compartido:<br>'.date("d/m/Y H:i", strtotime($row["date"])).'</td>
                      </tr> <tr><td colspan="7" style="line-height:0;border:0;">'.$phrase.'</td></tr>';
                  }
                }
              } else {
                echo "<tr><td colspan='6'><p>Aun no hay cartas en el top.</p></td></tr>";
              }
    }
  }
?>
