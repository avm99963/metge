<!--
The MIT License (MIT)

Copyright (c) 2016 Adrià Vilanova Martínez

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
-->
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <style>
  td, th
  {
  	padding:5px;
  }
  table
  {
  	border-collapse:collapse;
  	margin-bottom: 5px;
    page-break-inside:auto;
  }
  table, th, td
  {
  	border: 1px solid black;
  }
  tr    { page-break-inside:avoid; page-break-after:auto }
  thead { display:table-header-group }
  tfoot { display:table-footer-group }
  </style>
</head>
<body>
  <?php
  require("core.php");
  if (isadmin()) {
    if (isset($_GET["horario"]) && isset($config["visits"][$_GET["horario"]])) {
      $query = mysqli_query($con, "SELECT * FROM reserva");
      if (mysqli_num_rows($query)) {
        $reserva = array();
        while ($row = mysqli_fetch_array($query)) {
          $reserva[$row["dia"]][$row["hora"]][$row["posicio"]]["usuari"] = $row["usuari"];
          $reserva[$row["dia"]][$row["hora"]][$row["posicio"]]["nombre"] = userdata("nombre", $row["usuari"]);
        }
      }
      $visit = $config["visits"][$_GET["horario"]];
      echo "<table><thead><tr><th>".$visit["name"]."</th>";
      for($i=1; $i<=$visit["number"]; $i++) {
        $fecha = date("d F", $visit["date"]);
        echo "<th>".$fecha."</th>";
      }
      echo "</thead><tbody>";
      for($i=$visit['firsttime']; $i<=$visit['lasttime']; $i=$i+$visit['interval']) {
        echo "<tr><td>".mintohourmin($i)."</td>";
        for($i2=1; $i2<=$visit["number"]; $i2++) {
          $placeholder_td = "";
          $td_cancel = "";
          if (isset($reserva[$visit["date"]][$i][$i2])) {
            $placeholder_td = " class='reserved'";
            if (userdata("admin", $reserva[$visit["date"]][$i][$i2]["usuari"]) == 1) {
              $name = "Bloqueado";
            } else {
              $name = $reserva[$visit["date"]][$i][$i2]["nombre"];
            }
            $placeholder = "<span>".$name."</span>";
          } else {
            $placeholder = "-";
          }
          echo "<td".$placeholder_td." id='".$visit['date']."-".$i."-".$i2."'>".$placeholder.$td_cancel."</td>";
        }
        echo "</tr>";
      }
      echo "</tbody></table></div>";
    } elseif (isset($_GET["horario"]) && $_GET["horario"] == "missing") {
      $reservasperuser = array();

      $query = mysqli_query($con, "SELECT * FROM reserva");
      if (mysqli_num_rows($query)) {
        $reserva = array();
        while ($row = mysqli_fetch_array($query)) {
          if (!isset($reservasperuser[$row["usuari"]])) {
            $reservasperuser[$row["usuari"]] = 1;
          } else {
            $reservasperuser[$row["usuari"]] += 1;
          }
        }
      }

      $groups = array();
      foreach ($config['visits'] as $visit) {
        if (!in_array($visit["codename"], $groups)) {
          $groups[] = $visit["codename"];
        }
      }

      $ngroups = count($groups);

      echo "<h2>Lista de pacientes</h2><h3>Pacientes a los que les falta reservar alguna visita</h3><table><thead><tr><th>Nombre</th><th>Reservas</th></thead><tbody>";

      $everythingallright = "";

      $query2 = mysqli_query($con, "SELECT * FROM usuaris");
      while ($user = mysqli_fetch_assoc($query2)) {
        if ($user["admin"] == 0) {
          if (!isset($reservasperuser[$user["ID"]])) {
            $reservasperuser[$user["ID"]] = 0;
          }
          if ($reservasperuser[$user["ID"]] < $ngroups) {
            echo "<tr><td>".$user["nombre"]."</td><td>".$reservasperuser[$user["ID"]]."</td></tr>";
          } else {
            $everythingallright .= "<tr><td>".$user["nombre"]."</td><td>".$reservasperuser[$user["ID"]]."</td></tr>";
          }
        }
      }
      echo "</tbody></table><h3>Pacientes que ya han reservado todas las visitas</h3><table><thead><tr><th>Nombre</th><th>Reservas</th></thead><tbody>".$everythingallright."</tbody></table>";
    } else {
      echo "<div class='alert-danger'>No existe este horario.</div>";
    }
  } else {
    header('HTTP/1.0 404 Not Found');
  }
  ?>
</body>
</html>
