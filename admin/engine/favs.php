<?php
  // error_reporting(0);
  if(isset($_SESSION["user"])) {
    require '../../system/connection.php';
    require '../../system/encrypt.php';

    $sql = "SELECT letters.id as lid, letters.writer, letters.letter, letters.date, letters.shared, letters.shared_date, letters.image, letters.public, profiles.id, profiles.name as profile
      FROM internal_likes INNER JOIN letters ON internal_likes.letter=letters.id INNER JOIN profiles ON letters.profile=profiles.id
      WHERE internal_likes.doctor=".$_SESSION["user"]." ORDER BY letters.date DESC";

      $result = $conn->query($sql);
            if ($result->num_rows > 0) {
              $hospital = $_SESSION["hospital"];
              $doctor = $_SESSION["user"];
              while($row = $result->fetch_assoc()) {
                $myBodyLetter = $row['public']==0?$desencriptar($row["letter"]):$row['letter'];
                $shareddoctor = $row["shared"];

                $sql = "";
                $totalLikes = 0;
                $sharedrow = "";
                if($row["shared"]!=0) {
                  $sql = "SELECT COUNT(id) FROM internal_likes WHERE letter=?";
                  $stmt = $conn->prepare($sql);
                      $stmt->bind_param('i', $row["lid"]);
                      $stmt->execute();
                      $stmt->bind_result($mtotalLikes);
                      $stmt->store_result();
                      if($stmt->num_rows == 1)  {
                        if($stmt->fetch()) {
                          $totalLikes = $mtotalLikes;
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
                      $sharedrow = '<tr><td colspan="4" style="line-height:1;border:0;">'.$phrase.'</td></tr>';

                } else {
                  $sql = "SELECT count(id) FROM internal_likes WHERE letter=? AND doctor = ANY(SELECT id FROM doctors WHERE hospital=?);";
                  $stmt = $conn->prepare($sql);
                      $stmt->bind_param('ii', $row["lid"], $hospital);
                      $stmt->execute();
                      $stmt->bind_result($mtotalLikes);
                      $stmt->store_result();
                      if($stmt->num_rows == 1)  {
                        if($stmt->fetch()) {
                          $totalLikes = $mtotalLikes;
                        }
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
                    </tr>'.$sharedrow;
                } else {
                  echo '<tr>
                      <td class="action"><input type="checkbox" name="cb_letter[]" value="'.$row["lid"].'"/></td>
                      <td class="action">'.$realheard.'<span style="font-size:9px">'.$totalLikes.'</span></td>
                      <td class="name">'.$row["writer"].'</td>
                      <td class="subject" style="max-width:350px;">'.$myBodyLetter.'</td>
                      <td>Perfil:<br>'.$row["profile"].'</td>
                      <td><img style="max-width:300px;max-height:100px;" src="../../res/img/'.$row["image"].'" alt="Dibujo de la carta"></td>
                      <td class="time">'.date("d/m/Y H:i", strtotime($row["date"])).'</td>
                    </tr>'.$sharedrow;
                }
              }
            } else {
              echo "<tr><td colspan='6'><p>Aun no tienes cartas favoritas.</p></td></tr>";
            }
        // $conn->close();
  }
?>
