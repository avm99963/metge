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
$query = mysqli_query($con, "SELECT * FROM usuaris WHERE ID = '".mysqli_real_escape_string($con, $_GET['id'])."'") or die("<div class='alert-danger'>".mysqli_error()."</div>");
if (!mysqli_num_rows($query)) {
  die("This user doesn't exist.");
}
$row = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html>
<head>
  <title><?=$row["nombre"]?> – <?php echo $appname; ?></title>
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
</head>
<body class="mdl-color--green">
  <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header mdl-layout--fixed-drawer">
    <?php require("adminmdnav.php"); ?>
    <main class="mdl-layout__content">
      <div class="page-content">
        <div class="main mdl-shadow--4dp">
          <h2><?=$row["nombre"]?></h2>
          <?php
    			if ($row['admin'] == "1")
    			{
    				$type = "admin";
    			}
    			else
    			{
    				$type = "patient";
    			}
      		echo "<p><b>ID</b>: ".$row['ID']."</p><p><b>Nombre</b>: ".$row['nombre']."</p><p><b>Etapa</b>: ".$row['etapa']."</p><p><b>Tipo</b>: ".$type."</p><p><b>Email</b>: ".$row['email']."</p><p><b>DNI</b>: ".$row['dni']."</p><p><b>Password</b>: <span id='pass".$row['ID']."'><span style='cursor:pointer;color:blue;' onclick=\"document.getElementById('pass".$row['ID']."').innerHTML = '".$row['password']."';\">Pulsa</span></span></p><p><a href='edituser.php?id=".$row['ID']."' class='mdl-button mdl-color-text--orange mdl-js-ripple-effect'>Modificar usuario<span class='mdl-ripple'></span></a> <a style='color:red;' href='deleteuser.php?id=".$row['ID']."' class='mdl-button mdl-color-text--red mdl-js-ripple-effect'>Eliminar usuario<span class='mdl-ripple'></span></a></p>";
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
