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
?>
<!DOCTYPE html>
<html>
<head>
  <title>Cancelar reserva – <?php echo $appname; ?></title>
  <meta charset="UTF-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Chrome for Android theme color -->
  <!--<meta name="theme-color" content="#2E3AA1">-->

  <link rel="stylesheet" href="bower_components/material-design-lite/material.min.css">
  <script src="bower_components/material-design-lite/material.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link href='https://fonts.googleapis.com/css?family=Roboto:400,500,700,300' rel='stylesheet' type='text/css'>

  <link rel="stylesheet" href="css/dashboard.css">
</head>
<body class="mdl-color--green">
  <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header mdl-layout--fixed-drawer">
    <?php if (isadmin()) { require("adminmdnav.php"); } else { require("mdnav.php"); } ?>
    <main class="mdl-layout__content">
      <div class="page-content">
        <div class="main mdl-shadow--4dp">
          <h2>Cancelar reserva</h2>
          <?php
          if (!isset($_GET["dia"]) || !isset($_GET["hora"]) || !isset($_GET["posicio"])) {
            die("<p class='alert-danger'>No existe esta reserva.</p>");
          }
      		$query = mysqli_query($con, "SELECT * FROM reserva WHERE dia = ".(INT)$_GET["dia"]." AND hora = ".(INT)$_GET["hora"]." AND posicio = ".(INT)$_GET["posicio"]);
      		if (mysqli_num_rows($query)) {
      			if (!isset($_GET["sent"]) || (isset($_GET["sent"]) && $_GET["sent"] != "1")) {
      			?>
      		<p>¿Estás seguro que quieres eliminar la reserva de tu visita? <span style="color:red;font-weight:bold;">Esta acción no se puede revertir</span></p>
      		<p><a class="mdl-button md-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" href="cancelreservar.php?sent=1&dia=<?=$_GET["dia"]?>&hora=<?=$_GET["hora"]?>&posicio=<?=$_GET["posicio"]?>" class="button-link-red">Sí<span class="mdl-ripple"></span></a> <a class="mdl-button md-js-button mdl-button--raised mdl-js-ripple-effect" href="reservar.php" class="button-link">No<span class="mdl-ripple"></span></a></p>
      			<?php
      			} else {
      				if (isset($_GET["dia"]) && isset($_GET["hora"]) && isset($_GET["posicio"])) {
      					$row = mysqli_fetch_assoc($query);
                if (!isadmin() && $_SESSION["id"] != $row["usuari"]) {
                  die("<p class='alert-danger'>No se puede eliminar la reserva de otro usuario.</p>");
                }
      					$query2 = mysqli_query($con, "DELETE FROM reserva WHERE dia = '".(INT)$_GET["dia"]."' AND hora = '".(INT)$_GET["hora"]."' AND posicio = '".(INT)$_GET["posicio"]."' LIMIT 1");
      					if ($query2) {
      						header("Location: reservar.php#".$row["codename"]);
      					} else {
      						echo "<p class='alert-danger'>Error cancelando la reserva.</p>";
      					}
      				} else {
      					echo "<p class='alert-warning'>No se ha realizado ninguna acción.</p>";
      				}
      			}
      		} else {
      			echo "<p>No tienes ninguna reserva activa.</p>";
      		}
      		?>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
