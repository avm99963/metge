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
  <title>Configuración – <?php echo $appname; ?></title>
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
          <h2>Configuración del config.php</h2>
          <p><b>Appname</b>: <?=$appname?></p>
      		<p><b>Anuncio</b>:<br><?=$anuncio?></p>
      		<p><b>Número de carácteres en las contraseñas</b>: <?=$config['password']['characters']?></p>
          <p><b>Enviar emails</b>: <?=($config['sendemails'] ? "sí" : "no")?></p>
          <p><b>Copyright</b>: <?=$config['copyright']?></p>
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
