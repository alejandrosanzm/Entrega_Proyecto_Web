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
								<li class="active"><a href="shared.php"><i class="fa fa-retweet"></i> Compartidas</a></li>
								<li><a href="profiles.php"><i class="fa fa-users"></i> Perfiles</a></li>
                <li><a href="letter-list.php"><i class="fa fa-check-square"></i> Por aceptar</a></li>
                <li><a href="account.php"><i class="fa fa-user-md"></i> Mi Cuenta</a></li>
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
							<table class="table" border="0px">
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
                    }
                  ?>
                  <?php require '../engine/shared.php'; ?>
                  </tbody></table>
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
