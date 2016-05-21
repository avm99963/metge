<?php
require_once("core.php");
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

  <link rel="stylesheet" href="css/welcome.css">

  <script>
  var working = false;

  function reservar(dia, hora, posicio, idvisit, codename)
  {
    if (!working) {
      working = true;
      var id = dia+"-"+hora+"-"+posicio;
      var button_elements = document.getElementsByTagName("button");
      for (i = 0; i < button_elements.length; i++) {
        if (button_elements[i].disabled) {
          button_elements[i].setAttribute("data-disabled", "yes");
        }
        button_elements[i].disabled = true;
      }
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          console.log(xmlhttp.responseText);
          obj = JSON.parse(xmlhttp.responseText);
          if (obj.status == "ok") {
            document.getElementById(id).innerHTML = "<span>"+obj.text+" <a href='cancelreservar.php?dia="+obj.dia+"'><img style='height:16px;' src='img/cancel.png'></a></span>";
            document.getElementById(id).className = "user";
            for (i = 0; i < button_elements.length; i++) {
              if (button_elements[i].getAttribute("data-codename") != codename && button_elements[i].getAttribute("data-disabled") != "yes") {
                button_elements[i].disabled = false;
              }
            }
          } else {
            console.error("Error '"+obj.status+"': \""+obj.statustxt+"\"");
            alert("Error '"+obj.status+"': \""+obj.statustxt+"\"");
          }
          window.location = "reservar_assist.php?v=<?=$_GET["v"]+1?>";
        }
        working = false;
      }
      xmlhttp.open("POST","ajax/reservar.php",true);
      xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
      xmlhttp.send("dia="+dia+"&hora="+hora+"&posicio="+posicio+"&idvisit="+idvisit);
    } else {
      console.warn("Hey, I'm working. Let me finish before sending another request :-P");
    }
  }
  </script>
</head>
<body class="mdl-color--green">
  <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <?php require("mdnav.php"); ?>
    <main class="mdl-layout__content">
      <div class="page-content">
        <div class="main mdl-shadow--4dp">
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
                    }
                  }
                }
              }
              foreach($config['visits'] as $idvisit => $visit) {
                if ($visit["name"] == $visitname) {
                  echo "<div class='day-reservar'>";
                  if ($config['debugenabled'])
                    echo "Firsttime: ".$visit["firsttime"]."; Interval: ".$visit["interval"]."; Lasttime".$visit["lasttime"];
                  echo "<table><thead><tr><th>Hora</th>";
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
                          $placeholder_td = " class='user mdl-color--green-200'";
                          $td_cancel = " <a href='cancelreservar.php?dia=".$visit["date"]."'><img style='height:16px;' src='img/cancel.png'></a>";
                          $name = $reserva[$visit["date"]][$i][$i2]["nombre"];
                        } else {
                          $placeholder_td = " class='reserved mdl-color--red-200'";
                          if (isadmin()) {
                            $name = $reserva[$visit["date"]][$i][$i2]["nombre"];
                          } else {
                            $name = "-";
                          }
                        }
                        $placeholder = "<span>".$name."</span>";
                      } else {
                        $placeholder = "<button class=\"mdl-button mdl-button--primary mdl-js-ripple-effect\" onclick=\"reservar('".$visit["date"]."', '".$i."', '".$i2."', '".$idvisit."', '".$visit["codename"]."')\"".(isset($disabled[$visit["codename"]]) ? $disabled[$visit["codename"]] : "")." data-codename='".$visit["codename"]."'>Reservar<span class=\"mdl-ripple\"></span></button>";
                      }
                      echo "<td".$placeholder_td." id='".$visit['date']."-".$i."-".$i2."'>".$placeholder.$td_cancel."</td>";
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
              <p>Usted recibirá en el futuro dos correos electrónicos confirmando sus solicitudes.</p>
              <p>Que tenga un buen día.</p>
              <a class="next mdl-button md-js-button mdl-button--raised mdl-button--fab mdl-js-ripple-effect mdl-button--accent" href="index.php?v=0"><i class="material-icons">arrow_forward</i><span class="mdl-ripple"></span></a>
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
