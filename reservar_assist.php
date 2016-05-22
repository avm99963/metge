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
<?php
require_once("core.php");

if (!loggedin()) {
  header("Location: index.php");
  exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Reservar hora – <?php echo $appname; ?></title>
  <meta charset="UTF-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Chrome for Android theme color -->
  <!--<meta name="theme-color" content="#2E3AA1">-->

  <link rel="stylesheet" href="bower_components/material-design-lite/material.min.css">
  <script src="bower_components/material-design-lite/material.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link href='https://fonts.googleapis.com/css?family=Roboto:400,500,700,300' rel='stylesheet' type='text/css'>

  <link rel="stylesheet" href="css/stylemd.css">
  <link rel="stylesheet" href="css/dashboard.css">

  <script>
  var	working = false, isadmin = <?=(isadmin() ? "true" : "false")?>, redirect = true, redirecturl = "reservar_assist.php?v=<?=$_GET["v"]+1?>";
  </script>
  <script src="js/reservar.js"></script>
</head>
<body class="mdl-color--green">
  <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <?php require("mdnav.php"); ?>
    <main class="mdl-layout__content">
      <div class="page-content">
        <div class="main withfab mdl-shadow--4dp">
          <?php
          if (isset($config['visits'])) {
            $query = mysqli_query($con, "SELECT * FROM reserva");
            if (mysqli_num_rows($query)) {
              $reserva = array();
              while ($row = mysqli_fetch_array($query)) {
                $reserva[$row["dia"]][$row["hora"]][$row["posicio"]]["usuari"] = $row["usuari"];
                $reserva[$row["dia"]][$row["hora"]][$row["posicio"]]["nombre"] = userdata("nombre", $row["usuari"]);
              }
            }
            $groups = array();
            foreach ($config['visits'] as $visit) {
              $groups[] = $visit["codename"];
            }
            $groups = array_values(array_unique($groups));
            $disabled = $groups;
            $visitname = "";
            if (isset($groups[$_GET["v"]])) {
              foreach ($config["visits"] as $visit) {
                if ($visit["codename"] == $groups[$_GET["v"]]) {
                  $visitname = $visit["name"];
                  $visitcodename = $visit["codename"];
                }
              }
            ?>
            <h2>Reservar hora para "<?=$visitname?>"</h2>
            <p>Escoja la hora que mejor le convenga para la visita "<?=$visitname?>":</p>
            <div class="holder">
              <?php
              $letscheck = mysqli_query($con, "SELECT * FROM reserva WHERE usuari = ".(INT)$_SESSION['id']."");
              if (mysqli_num_rows($letscheck)) {
                while($rowtheboat = mysqli_fetch_array($letscheck)) {
                  foreach ($config['visits'] as $visit) {
                    if ($visit["date"] == $rowtheboat["dia"]) {
                      $disabled[$visit["codename"]] = " disabled='true'";
                      if ($visit["codename"] == $visitcodename) {
                        header("Location: reservar_assist.php?v=".($_GET["v"] + 1).(($_GET["v"] == 0 || isset($_GET["nomsg"])) ? "&nomsg" : ""));
                        exit();
                      }
                    }
                  }
                }
              }
              $groups = array();
              foreach ($config['visits'] as $idvisit =>  $visit) {
                if (!isset($groups[$visit["codename"]])) {
                  $groups[$visit["codename"]] = array();
                }
                $visit["idvisit"] = $idvisit;
                $groups[$visit["codename"]][] = $visit;
              }

              if (isset($groups[$visitcodename])) {
                $visits = $groups[$visitcodename];

                $firsttime = $visits[0]["firsttime"];
                $interval = $visits[0]["interval"];
                $lasttime = $visits[0]["lasttime"];
                $oldmethod = false;
                foreach ($visits as $visit) {
                  if ($firsttime != $visit["firsttime"] || $interval != $visit["interval"] || $lasttime != $visit["lasttime"]) {
                    $oldmethod = true;
                  }
                }
                if ($oldmethod) {
                  foreach ($visits as $visit) {
                    echo "<div class='day-reservar'>";
                    if ($config['debugenabled'])
                      echo "Firsttime: ".$visit["firsttime"]."; Interval: ".$visit["interval"]."; Lasttime".$visit["lasttime"];
                    echo "<table><thead><tr><th></th>";
                    for($i=1; $i<=$visit["number"]; $i++) {
                      echo "<th>".date("d F", $visit["date"])."</th>";
                    }
                    echo "</thead><tbody>";
                    for($i=$visit['firsttime']; $i<=$visit['lasttime']; $i=$i+$visit['interval']) {
                      echo "<tr><td>".mintohourmin($i)."</td>";
                      for($i2=1; $i2<=$visit["number"]; $i2++) {
                        $placeholder_td = "";
                        $td_cancel = "";
                        if (isset($reserva[$visit["date"]][$i][$i2])) {
                          if ($reserva[$visit["date"]][$i][$i2]["usuari"] == $_SESSION["id"]) {
                            $placeholder_td = " class='user mdl-color--green-200'";
                            $td_cancel = "<a href='cancelreservar.php?dia=".$visit["date"]."&hora=".$i."&posicio=".$i2."'><i class='material-icons delete'>delete</i></a>";
                            $name = $reserva[$visit["date"]][$i][$i2]["nombre"];
                          } else {
                            $placeholder_td = " class='reserved mdl-color--red-200'";
                            if (isadmin()) {
                              $name = $reserva[$visit["date"]][$i][$i2]["nombre"];
                            } else {
                              $name = "-";
                            }
                          }
                          $placeholder = "<span style='vertical-align: middle;'>".$name."</span>";
                        } else {
                          $placeholder = "<button class=\"mdl-button mdl-button--primary mdl-js-ripple-effect\" onclick=\"reservar('".$visit["date"]."', '".$i."', '".$i2."', '".$visit["idvisit"]."', '".$visit["codename"]."')\"".(isset($disabled[$visit["codename"]]) ? $disabled[$visit["codename"]] : "")." data-codename='".$visit["codename"]."'>Reservar<span class=\"mdl-ripple\"></span></button>";
                        }
                        echo "<td".$placeholder_td." id='".$visit['date']."-".$i."-".$i2."'>".$placeholder.$td_cancel."</td>";
                      }
                      echo "</tr>";
                    }
                    echo "</tbody></table></div>";
                  }
                } else {
                  echo "<div class='day-reservar-centered'>";
                  echo "<table><thead><tr><th></th>";
                  foreach ($visits as $visit) {
                    for($i2=1; $i2<=$visit["number"]; $i2++) {
                      echo "<th>".date("d F", $visit["date"])."</th>";
                    }
                  }
                  echo "</thead><tbody>";
                  for($i=$visits[0]['firsttime']; $i<=$visits[0]['lasttime']; $i=$i+$visits[0]['interval']) {
                    echo "<tr><td>".mintohourmin($i)."</td>";
                    foreach ($visits as $visit) {
                      for($i2=1; $i2<=$visit["number"]; $i2++) {
                        $placeholder_td = "";
                        $td_cancel = "";
                        if (isset($reserva[$visit["date"]][$i][$i2])) {
                          if ($reserva[$visit["date"]][$i][$i2]["usuari"] == $_SESSION["id"]) {
                            $placeholder_td = " class='user mdl-color--green-200'";
                            $td_cancel = "<a href='cancelreservar.php?dia=".$visit["date"]."&hora=".$i."&posicio=".$i2."'><i class='material-icons delete'>delete</i></a>";
                            $name = $reserva[$visit["date"]][$i][$i2]["nombre"];
                          } else {
                            $placeholder_td = " class='reserved mdl-color--red-200'";
                            if (isadmin()) {
                              $name = $reserva[$visit["date"]][$i][$i2]["nombre"];
                            } else {
                              $name = "-";
                            }
                          }
                          $placeholder = "<span style='vertical-align: middle;'>".$name."</span>";
                        } else {
                          $placeholder = "<button class=\"mdl-button mdl-button--primary mdl-js-ripple-effect\" onclick=\"reservar('".$visit["date"]."', '".$i."', '".$i2."', '".$visit["idvisit"]."', '".$visit["codename"]."')\"".(isset($disabled[$visit["codename"]]) ? $disabled[$visit["codename"]] : "")." data-codename='".$visit["codename"]."'>Reservar<span class=\"mdl-ripple\"></span></button>";
                        }
                        echo "<td".$placeholder_td." id='".$visit['date']."-".$i."-".$i2."'>".$placeholder.$td_cancel."</td>";
                      }
                    }
                    echo "</tr>";
                  }
                  echo "</tbody></table></div>";
                }
              }
              ?>
            </div>
            <?php
            } else {
              ?>
              <h2>¡Ya estamos!</h2>
              <?php if (!isadmin() && isset($config["sendemails"]) && $config["sendemails"] === true && !isset($_GET["nomsg"])) { ?>
                <p>Usted recibirá en el futuro un correo electrónico confirmando cada una de sus solicitudes.</p>
              <?php } ?>
              <p>Que tenga un buen día.</p>
              <a class="next mdl-button md-js-button mdl-button--raised mdl-button--fab mdl-js-ripple-effect mdl-button--accent" href="index.php"><i class="material-icons">arrow_forward</i><span class="mdl-ripple"></span></a>
              <?php
            }
          }
          else
          {
            echo "<div class='alert alert-danger'>No hay fechas definidas</div>";
          }
          ?>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
