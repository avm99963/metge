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
if (isadmin())
{
?>
<!DOCTYPE html>
<html>
<head>
  <title>Borrar usuario – <?php echo $appname; ?></title>
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

  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
  <script src="js/jquery.autocomplete.min.js"></script>

  <link rel="stylesheet" href="lib/mdl-ext/mdl-ext.min.css">
  <script src="lib/mdl-ext/mdl-ext.min.js"></script>

  <script src="bower_components/dialog-polyfill/dialog-polyfill.js"></script>
	<link rel="stylesheet" type="text/css" href="bower_components/dialog-polyfill/dialog-polyfill.css">
</head>
<body class="mdl-color--green">
  <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header mdl-layout--fixed-drawer">
    <?php require("adminmdnav.php"); ?>
    <main class="mdl-layout__content">
      <div class="page-content">
        <div class="main mdl-shadow--4dp">
          <h2>Borrar usuario</h2>
          <?php
      		if (isset($_GET["sent"]) && $_GET['sent'] == "1")
      		{
      			$sql1 = "DELETE FROM reserva WHERE usuari = '".(INT)$_GET['id']."' LIMIT 1";
      			$sql = "DELETE FROM usuaris WHERE ID = '".(INT)$_GET['id']."' LIMIT 1";
      			if (mysqli_query($con, $sql1)) {
        				if (mysqli_query($con, $sql)) {
      	  				header("Location: users.php?msg=deletesuccessful");
      	 			}
      				else {
      					die ("<p class='alert-danger'>Error eliminando el usuario: " . mysqli_error($con) . "</p>");
      				}
       			}
      			else {
      				die ("<p class='alert-danger'>Error eliminando la visita del usuario: " . mysqli_error($con) . "</p>");
      			}
      		}
      		else
      		{
      		$query = mysqli_query($con, "SELECT * FROM usuaris WHERE ID = '".mysqli_real_escape_string($con, $_GET['id'])."' LIMIT 1") or die("<div class='alert-danger'>".mysqli_error()."</div>");
      		$row = mysqli_fetch_array($query);
      		?>
      		<p>¿Estás seguro que quieres eliminar "<?=$row["nombre"]?>"? <span style="color:red;font-weight:bold;">Esta acción no se puede revertir</span></p>
          <p><a class="mdl-button md-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" href="deleteuser.php?id=<?php echo $_GET['id'];?>&sent=1" class="button-link-red">Sí<span class="mdl-ripple"></span></a> <a class="mdl-button md-js-button mdl-button--raised mdl-js-ripple-effect" href="users.php" class="button-link">No<span class="mdl-ripple"></span></a></p>
      		<?php
      		}
      		?>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
<?php
}
else
{
	header('HTTP/1.0 404 Not Found');
}
?>
