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
</head>
<body class="mdl-color--green">
  <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <?php require("mdnav.php"); ?>
    <main class="mdl-layout__content">
      <div class="page-content">
        <div class="main mdl-shadow--4dp">
          <h2>Bienvenido</h2>
          <p>Bienvenido al sistema de horas para el médico de St. Paul's School. Para reservar le guiaremos en una serie de pasos. Después de completar estos pasos puede modificar sus horarios entrando de nuevo a esta página web.</p>
          <a class="next mdl-button md-js-button mdl-button--raised mdl-button--fab mdl-js-ripple-effect mdl-button--accent" href="reservar_assist.php?v=0"><i class="material-icons">arrow_forward</i><span class="mdl-ripple"></span></a>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
