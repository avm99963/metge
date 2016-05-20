<?php
require("core.php");
if (isadmin())
{
  if (isset($config["visits"][$_POST["horario"]])) {
    $visit = $config["visits"][$_POST["horario"]];
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
          if ($reserva[$visit["date"]][$i][$i2]["usuari"] == $_SESSION["id"]) {
            $placeholder_td = " class='user'";
            $td_cancel = " <a href='cancelreservar.php?dia=".$visit["date"]."'><img style='height:16px;' src='img/cancel.png'></a>";
            $name = $reserva[$visit["date"]][$i][$i2]["nombre"];
          } else {
            $placeholder_td = " class='reserved'";
            if (isadmin()) {
              $name = $reserva[$visit["date"]][$i][$i2]["nombre"];
            } else {
              $name = "-";
            }
          }
          $placeholder = "<span>".$name."</span>";
        } else {
          $placeholder = "<button onclick=\"reservar('".$visit["date"]."', '".$i."', '".$i2."', '".$idvisit."', '".$visit["codename"]."')\"".$disabled[$visit["codename"]]." data-codename='".$visit["codename"]."'>Reservar</button>";
        }
        echo "<td".$placeholder_td." id='".$visit['date']."-".$i."-".$i2."'>".$placeholder.$td_cancel."</td>";
      }
      echo "</tr>";
    }
    echo "</tbody></table></div>";
  } else {
    echo "<div class='alert-danger'>No existe este horario.</div>";
  }
}
else
{
  header('HTTP/1.0 404 Not Found');
}
?>
