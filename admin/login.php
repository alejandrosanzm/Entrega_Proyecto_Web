<?php
  // error_reporting(0);
  session_start();
  if(isset($_SESSION["user"])) {
    header("Location: ./");
  }
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <link href="../assets/login-assets/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="../assets/login-assets/bootstrap.min.js"></script>
    <script src="../assets/login-assets/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" href="login-style.css">
    <title>Palabras Por Sonrisas</title>
  </head>
  <body>
    <div class="sidenav">
             <div class="login-main-text">
                <h2>Palabras Por Sonrisas<br> Login</h2>
                <p>Necesitas identificarte para poder acceder a nuestro dashboard.</p>
             </div>
          </div>
          <div class="main">
             <div class="col-md-6 col-sm-12">
                <div class="login-form">
                  <?php if(isset($_GET["error"])) { echo '<p style="color:#881109;">Usuario o contraseña incorrectos.</p>'; } ?>
                   <form action="engine/login.php" method="post">
                      <input type="hidden" name="submit" value="true">
                      <div class="form-group">
                         <label>Nombre de Usuario</label>
                         <input type="text" name="user" class="form-control" placeholder="Usuario">
                      </div>
                      <div class="form-group">
                         <label>Contraseña</label>
                         <input type="password" name="password" class="form-control" placeholder="Contraseña">
                      </div>
                      <button type="submit" class="btn btn-black">Entrar</button>
                      <button class="btn btn-secondary">Volver</button>
                   </form>
                </div>
             </div>
          </div>
  </body>
</html>
