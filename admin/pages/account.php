<?php
  // error_reporting(0);
  session_start();
  if(!isset($_SESSION["user"])) {
    header("Location: ../login.php");
  }

  // Obtener número de cartas recibidas
  if(isset($_SESSION["user"])) {
      require '../../system/connection.php';
      $numInbox = 0;
      $stmt = $conn->prepare("SELECT COUNT(id) FROM validated WHERE hospital_id=? AND validated=1");
          $stmt->bind_param('i', $_SESSION["hospital"]);
          $stmt->execute();
          $stmt->bind_result($mnumInbox);
          $stmt->store_result();
          if($stmt->num_rows == 1)  {
            if($stmt->fetch()) {
              $numInbox = $mnumInbox;
            }
          }
  }

  // Obtener número de cartas favoritas
  if(isset($_SESSION["user"])) {
      require '../../system/connection.php';
      $numLikes = 0;
      $stmt = $conn->prepare("SELECT COUNT(id) FROM internal_likes WHERE doctor=?");
          $stmt->bind_param('i', $_SESSION["user"]);
          $stmt->execute();
          $stmt->bind_result($mnumLikes);
          $stmt->store_result();
          if($stmt->num_rows == 1)  {
            if($stmt->fetch()) {
              $numLikes = $mnumLikes;
            }
          }
  }

  // Obtener número de cartas compartidas
  if(isset($_SESSION["user"])) {
      require '../../system/connection.php';
      $numShared = 0;
      $stmt = $conn->prepare("SELECT COUNT(id) FROM letters WHERE shared=?");
          $stmt->bind_param('i', $_SESSION["user"]);
          $stmt->execute();
          $stmt->bind_result($mnumShared);
          $stmt->store_result();
          if($stmt->num_rows == 1)  {
            if($stmt->fetch()) {
              $numShared = $mnumShared;
            }
          }
  }

  // Obtener los datos del médico
  if(isset($_SESSION["user"])) {
      require '../../system/connection.php';
      $doctorId = 0;
      $doctorName = "";
      $doctorUser = "";
      $hospitalId = 0;
      $hospitalName = "";
      $myQuery = "SELECT doctors.id as doctorId, doctors.name as doctorName, doctors.user as doctorUser, hospitals.id as hospitalId, hospitals.name as hospitalName FROM doctors INNER JOIN hospitals ON doctors.hospital = hospitals.id WHERE doctors.id=?";
      $stmt = $conn->prepare($myQuery);
          $stmt->bind_param('i', $_SESSION["user"]);
          $stmt->execute();
          $stmt->bind_result($myDoctorId, $myDoctorName, $myDoctorUser, $myHospitalId, $myHospitalName);
          $stmt->store_result();
          if($stmt->num_rows == 1)  {
            if($stmt->fetch()) {
              $doctorId = $myDoctorId;
              $doctorName = $myDoctorName;
              $doctorUser = $myDoctorUser;
              $hospitalId = $myHospitalId;
              $hospitalName = $myHospitalName;
            }
          }
  }

  // Cambiar la información de usuario
  if(isset($_SESSION["user"]) && isset($_POST['submit']) && isset($_POST['user']) && isset($_POST['actualPass'])) {

    $actualHash = "";
    $myQuery = "SELECT password FROM doctors WHERE id=?";
    $stmt = $conn->prepare($myQuery);
        $stmt->bind_param('i', $_SESSION["user"]);
        $stmt->execute();
        $stmt->bind_result($myActualHash);
        $stmt->store_result();
        if($stmt->num_rows == 1)  {
          if($stmt->fetch()) {
            $actualHash = $myActualHash;
          }
        }

    if(isset($_POST['actualPass']) && hash('sha256', $_POST['actualPass']) == $actualHash) {

      if((isset($_POST['pass1']) && isset($_POST['pass2'])) && ($_POST['pass1'] != "" || $_POST['pass2'] != "")) {
        // Usuario y pass
        $newUser = $_POST['user'];
        $newPass1 = $_POST['pass1'];
        $newPass2 = $_POST['pass2'];
        if($newPass1 == $newPass2 && ($newPass1 != "" || $newUser != "")) {
          $sql = "UPDATE doctors SET user=?, password=? WHERE id=?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param('ssi', $newUser, hash('sha256', $newPass1), $_SESSION["user"]);
          $stmt->execute();
          header("Location: account.php");
        } else {
          // Las claves no coinciden
          header("Location: account.php?edit=true&error=Las claves no coinciden");
        }
      } else {
        // Solo usuario
        $newUser = $_POST['user'];
        $sql = "UPDATE doctors SET user=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $newUser, $_SESSION["user"]);
        $stmt->execute();
        header("Location: account.php");
      }

    } else {
      // Clave no es correcta
      header("Location: account.php?edit=true&error=Autenticación incorrecta, error de permisos");
    }
  }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Palabras Por Sonrisas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="../../assets/admin-assets/jquery.min.js"></script>
    <link href="../../assets/admin-assets/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <!-- https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
  <form action="../index.php" method="post">
<div class="container">
<div class="row">
	<!-- BEGIN INBOX -->
	<div class="col-md-12">
		<div class="grid email">
			<div class="grid-body">
				<div class="row">
					<!-- BEGIN INBOX MENU -->
					<div class="col-md-3">
						<h2 class="grid-title"><i class="fa fa-inbox"></i> <?php echo "Dashboard"; ?></h2>
						<a class="btn btn-block btn-primary" data-toggle="modal" data-target="#compose-modal"><i class="fa fa-pencil"></i>&nbsp;&nbsp;COMPARTIR CARTAS</a>

						<hr>

						<div>
							<ul class="nav nav-pills nav-stacked">
								<li class="header">Cajones</li>
								<li><a href="../"><i class="fa fa-inbox"></i> Aceptadas (<?php echo $numInbox; ?>)</a></li>
                <li><a href="favs.php"><i class="fa fa-thumbs-up"></i> Favoritas</a></li>
								<li><a href="shared.php"><i class="fa fa-retweet"></i> Compartidas</a></li>
								<li><a href="profiles.php"><i class="fa fa-users"></i> Perfiles</a></li>
                <li><a href="letter-list.php"><i class="fa fa-check-square"></i> Por aceptar</a></li>
                <li class="active"><a href="account.php"><i class="fa fa-user-md"></i> Mi Cuenta</a></li>
                <li><a href="../engine/logout.php"><i class="fa fa-sign-out"></i> Cerrar Sesión</a></li>
							</ul>
						</div>
					</div>
					<!-- END INBOX MENU -->

					<!-- BEGIN INBOX CONTENT -->
					<div class="col-md-9">
						<div class="row">
							<div class="col-sm-6">
                <select class="btn btn-default dropdown-toggle" name="action" onchange="this.form.submit()">
                  <option disabled="print" selected>Acción</option>
                  <option>Imprimir</option>
                  <option value="like">Cambiar como 'Me Gusta'</option>
                  <option value="share">Compartir</option>
                </select>
							</div>

							<div class="col-md-6 search-form">
								<form action="#" class="text-right">
									<div class="input-group">
										<input type="text" class="form-control input-sm" placeholder="Search">
										<span class="input-group-btn">
                                            <button type="submit" name="search" class="btn_ btn-primary btn-sm search"><i class="fa fa-search"></i></button></span>
									</div>
								</form>
							</div>
						</div>

						<div class="padding"><br></div>

						<div class="table-responsive">
							<table class="table">
                <!-- class="read" -->
                <!-- Si no hay foto, td de imagen vacío y td anterior (de subject) con colspan a 2 -->
								<tbody>
                  <?php
                    if(isset($_GET["error"])) {
                      echo "<tr><p style='color:#c3443f;'><i class='fa fa-exclamation-triangle' style='color:#ff9800;'>&nbsp;</i>Ha ocurrido un error al intentar realizar la acción.</p></tr>";
                    } elseif (isset($_GET["errdeleted"])) {
                      echo "<tr><p style='color:#4caf50;'><i class='fa fa-check-circle'>&nbsp;</i>Las cartas se han eliminado correctamente.</p></tr>";
                    } elseif (isset($_GET["errchanged"])) {
                      echo "<tr><p style='color:#4caf50;'><i class='fa fa-check-circle'>&nbsp;</i>Los perfiles se han modificado correctamente.</p></tr>";
                    } elseif (isset($_GET["shared"])) {
                      echo "<tr><p style='color:#4caf50;'><i class='fa fa-check-circle'>&nbsp;</i>Las cartas se han compartido correctamente.</p></tr>";
                    } elseif (isset($_GET["errshared"])) {
                      echo "<tr><p style='color:#4caf50;'><i class='fa fa-check-circle'>&nbsp;</i>Algunas cartas ya estaban compartidas.</p></tr>";
                    }
                  ?>
                  <tr><td colspan="7" style="background-color:#f6f6f6;"><i class="fa fa-user-md">&nbsp;&nbsp;</i>Tu cuenta</td></tr>
                  <?php // require '../engine/favs.php'; ?>
							</tbody></table>

                  <style media="screen">
                    @import url(http://fonts.googleapis.com/css?family=Lato:400,700);
                    body
                    {
                      font-family: 'Lato', 'sans-serif';
                      }
                    .profile
                    {
                      min-height: 355px;
                      display: inline-block;
                      }
                    figcaption.ratings
                    {
                      margin-top:20px;
                      }
                    figcaption.ratings a
                    {
                      color:#f1c40f;
                      font-size:11px;
                      }
                    figcaption.ratings a:hover
                    {
                      color:#f39c12;
                      text-decoration:none;
                      }
                    .divider
                    {
                      border-top:1px solid rgba(0,0,0,0.1);
                      }
                    .emphasis
                    {
                      border-top: 4px solid transparent;
                      }
                    .emphasis:hover
                    {
                      /* border-top: 4px solid #1abc9c; */
                      }
                    .emphasis h2
                    {
                      margin-bottom:0;
                      }
                    span.tags
                    {
                      background: #1abc9c;
                      border-radius: 2px;
                      color: #f5f5f5;
                      font-weight: bold;
                      padding: 2px 4px;
                      }
                    .dropdown-menu
                    {
                      background-color: #34495e;
                      box-shadow: none;
                      -webkit-box-shadow: none;
                      width: 250px;
                      margin-left: -125px;
                      left: 50%;
                      }
                    .dropdown-menu .divider
                    {
                      background:none;
                      }
                    .dropdown-menu>li>a
                    {
                      color:#f5f5f5;
                      }
                    .dropup .dropdown-menu
                    {
                      margin-bottom:10px;
                      }
                    .dropup .dropdown-menu:before
                    {
                      content: "";
                      border-top: 10px solid #34495e;
                      border-right: 10px solid transparent;
                      border-left: 10px solid transparent;
                      position: absolute;
                      bottom: -10px;
                      left: 50%;
                      margin-left: -10px;
                      z-index: 10;
                      }
                  </style>
                  <form action="account.php" method="post">
                 <div class="well profile">
                      <div class="col-sm-12">
                          <div class="col-xs-12 col-sm-8">
                              <h2><?php echo $doctorName; ?></h2>
                              <p><strong>Nombre de usuario: </strong><?php echo isset($_GET['edit'])?"<input type='text' name='user' value='".$doctorUser."'>":$doctorUser; ?>, <span class="tags">id_<?php echo $doctorId; ?></span></p>
                              <p><strong>Hospital: </strong><?php echo $hospitalName; ?>, <span class="tags">id_<?php echo $hospitalId; ?></span></p>
                              <p><strong>Password: </strong><?php
                                if(isset($_GET['edit'])) {
                                  echo '<input type="password" name="actualPass" required><br><strong>New Password: </strong><input type="password" name="pass1"><br><strong>Confirm Password: </strong><input type="password" name="pass2">';
                                } else { echo "********"; } ?>
                              </p>
                          </div>
                      </div>
                      <div class="col-xs-12 divider text-center">
                          <div class="col-xs-12 col-sm-4 emphasis">
                              <h2><strong><?php echo $numLikes; ?></strong></h2>
                              <p><small>Cartas Favoritas</small></p>
                              <!-- <button class="btn btn-success btn-block"><span class="fa fa-plus-circle"></span> Follow </button> -->
                          </div>
                          <div class="col-xs-12 col-sm-4 emphasis">
                              <h2><strong><?php echo $numShared; ?></strong></h2>
                              <p><small>Cartas Compartidas</small></p>
                          </div>
                          <div class="col-xs-12 col-sm-4 emphasis">
                              <h2><strong><?php echo $numInbox; ?></strong></h2>
                              <p><small>Cartas en tu hospital</small></p>
                          </div>
                      </div>
                      <?php
                        if(isset($_GET['edit'])) {
                          echo '<input type="hidden" name="submit" value="true">';
                          echo '<input class="btn btn-info btn-block" style="margin:auto;margin-top:270px;" type="submit" value="Guardar">';
                        } else {
                          echo '<a href="account.php?edit=true" class="btn btn-info btn-block" style="margin:auto;margin-top:270px;;"><span class="fa fa-user"></span>Editar Perfil</a>';
                        }
                      ?>
                      <?php
                        if(isset($_GET['error'])) {
                          echo '<p style="color:red;">'.$_GET['error'].'</p>';
                        }
                      ?>
                 </div>
               </form>






						</div>
					</div>
					<!-- END INBOX CONTENT -->
          </form>

					<!-- BEGIN COMPOSE MESSAGE -->
					<div class="modal fade" id="compose-modal" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-wrapper">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header bg-blue">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
										<h4 class="modal-title"><i class="fa fa-envelope"></i> Enviar cartas</h4>
									</div>
									<form action="#" method="post">
										<div class="modal-body">
											<div class="form-group">
                        <!-- Selector de hospitales -->
                        <label for="hospitals">Elige los hospitales de destino:</label><br>
                        <div class="boxes">
                          <!-- Para seleccionar atributo checked -->
                          <input type="checkbox" id="box-1">
                          <label for="box-1">Sustainable typewriter cronut</label>

                          <input type="checkbox" id="box-2">
                          <label for="box-2">Gentrify pickled kale chips </label>

                          <input type="checkbox" id="box-3">
                          <label for="box-3">Gastropub butcher</label>
                        </div>
                        <p style="font-size:9px;">*Se enviarán todas las cartas seleccionadas</p>



											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Descartar</button>
											<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-envelope"></i> Enviar Cartas</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					<!-- END COMPOSE MESSAGE -->
				</div>
			</div>
		</div>
	</div>
	<!-- END INBOX -->
</div>
</div>

<script src="../../assets/admin-assets/bootstrap.min.js"></script>
<script type="text/javascript">

</script>
</body>
</html>
