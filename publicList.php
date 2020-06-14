<!DOCTYPE html>
<html lang="es">
  <head>
    <link href="assets/css/material-bootstrap-wizard.css" rel="stylesheet" />
    <style media="screen">
      table {
        border-collapse: collapse;
        width: 100%;
      }

      th, td {
        text-align: left;
        padding: 8px;
      }

      tr:nth-child(even){background-color: #f2f2f2}

      th {
        background-color: #4CAF50;
        color: white;
      }
    </style>
    <meta charset="utf-8">
    <title>Lista Pública - Palabras Por Sonrisas</title>
  </head>
  <body>
    <div style="width:700px;margin:auto;margin-top:25px;border:1px solid black;">
      <div class="wizard-header">
          <h3 class="wizard-title" style="margin:auto;text-align:center;">
            Palabras Por Sonrisas
          </h3>
      </div>
      <a href="index.php">Volver</a>
      <table>
        <th>Título</th>
        <th>Carta</th>
        <th>Perfil</th>
        <th>Fecha</th>

        <?php
        require 'system/connection.php';

        $sql = "SELECT writer, letter, profile, date FROM letters WHERE public=1;";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          $profiles = '<option selected disabled>Elegir Perfil</option>';
          while($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo '<td>'.$row['writer'].'</td>';
              echo '<td>'.$row['letter'].'</td>';
              echo '<td>'.$row['profile'].'</td>';
              echo '<td>'.$row['date'].'</td>';
              echo "</tr>";
          }
        }
        ?>

      </table>
    </div>
      <script src="assets/js/material-bootstrap-wizard.js"></script>
  </body>
</html>
