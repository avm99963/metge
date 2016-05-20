<?php
require_once("core.php");
$msg = "";
if (isset($_GET['msg'])) {
  if ($_GET['msg'] == "loginwrong")
    $msg = 'Usuario y/o contraseña incorrecto';
  if ($_GET['msg'] == "empty")
    $msg = 'Por favor, rellena todos los campos';
  if ($_GET['msg'] == "logoutsuccess")
    $msg = '¡Has cerrado la sesión correctamente! Ten un buen día :-)';
}
?>
<!DOCTYPE html>
<html>
<head>
  <title><?php echo $appname; ?></title>
  <meta charset="UTF-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Chrome for Android theme color -->
  <!--<meta name="theme-color" content="#2E3AA1">-->

  <link rel="stylesheet" href="bower_components/material-design-lite/material.min.css">
  <script src="bower_components/material-design-lite/material.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

  <link rel="stylesheet" href="css/index.css">

  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
  <script src="js/jquery.autocomplete.min.js"></script>

  <script>
  $(document).ready(function() {
  	var options, a;
  	jQuery(function(){
  	   options = { serviceUrl:'ajax/users.php' };
  	   a = $('#id').autocomplete(options);
  	});
  });
  </script>
</head>
<body class="mdl-color--green">
  <div class="login mdl-shadow--4dp">
    <h2><?=$appname?></h2>
    <form action="login.php" method="POST" autocomplete="off" id="formulario">
      <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        <input class="mdl-textfield__input" type="text" name="id" id="id" autocomplete="off">
        <label class="mdl-textfield__label" for="id">Nombre</label>
      </div>
      <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        <input class="mdl-textfield__input" type="password" name="password" id="password" autocomplete="off">
        <label class="mdl-textfield__label" for="password">Contraseña</label>
      </div>
      <p><button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">Iniciar sesión</button></p>
		</form>
    <p>&copy; <?=date("Y")?> Adrià Vilanova Martínez</p>
  </div>

  <?php
  if (isset($_GET['msg'])) {
    ?>
    <div class="mdl-snackbar mdl-js-snackbar">
      <div class="mdl-snackbar__text"></div>
      <button type="button" class="mdl-snackbar__action"></button>
    </div>
    <script>
    window.addEventListener("load", function() {
      var notification = document.querySelector('.mdl-js-snackbar');
      notification.MaterialSnackbar.showSnackbar(
        {
          message: '<?=$msg?>'
        }
      );
    });
    </script>
    <?php
  }
  ?>
</body>
</html>
