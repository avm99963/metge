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
  <title>Panel de control – <?php echo $appname; ?></title>
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
          <?php
					$fichero = $_FILES['file']['tmp_name'];
					$csv = file_get_contents($fichero);
					//$csv = iconv("ISO-8859-1", "UTF-8", $csv);
					$lineas = explode("\r", $csv);
					$start = FALSE;
					foreach($lineas as $linea) {
						$linea = str_replace("\n", "", $linea);
						if ($start === FALSE) {
							if ($linea != "ETAPA;NOMBRE;EMAIL;DNI" && $linea != "ETAPA,NOMBRE,EMAIL,DNI") {
								die ("<div class='alert alert-danger'>Has elegido el archivo equivocado. El header tiene que ser 'ETAPA;NOMBRE;EMAIL;DNI'.</div>");
							}
							$start = TRUE;
						} else {
							if (!empty($linea)) {
								$values = str_getcsv($linea); // 0 => ETAPA; 1 => NOMBRE; 2 => EMAIL; 3 => DNI
								$etapa = ucfirst(strtolower(mysqli_real_escape_string($con, $values[0])));
								$nombre = mb_convert_case(mysqli_real_escape_string($con, $values[1]), MB_CASE_TITLE, "UTF-8");
								$originalemail = mysqli_real_escape_string($con, $values[2]);
								$email = mb_strtolower($originalemail, "UTF-8");
								$dni = mysqli_real_escape_string($con, $values[3]);
								$dni = str_replace("-", "", $dni);
								$num_caracteres = $config['password']['characters'];
								$nueva_clave = $dni; // generamos una nueva contraseña de forma aleatoria
								$password = mysqli_real_escape_string($con, $nueva_clave);
								$sql = "INSERT INTO usuaris (etapa, nombre, email, dni, password, admin) VALUES ('{$etapa}', '{$nombre}', '{$email}', '{$dni}', '{$password}', FALSE)";
								if (mysqli_query($con, $sql)) {
									echo "<p style='color:green;'>Hemos añadido a <b>".$nombre."</b> (de la etapa <b>".$etapa."</b>) con email <b>".$email."</b> y DNI <b>".$dni."</b></p>";
								} else {
									echo "<p style='color:red;'>No se ha podido añadir a <b>".$nombre."</b> en la tabla. (<i>".mysqli_error($con)."</i>)</p>";
								}
							}
						}
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
