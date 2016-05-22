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
  <title>Editar usuario – <?php echo $appname; ?></title>
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
          <h2>Editar usuario</h2>
          <?php
          if (isset($_GET["sent"]) && $_GET['sent'] == "1") {
            $id = mysqli_real_escape_string($con, $_POST['id']);
            $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
            $etapa = mysqli_real_escape_string($con, $_POST['etapa']);
            $email = mysqli_real_escape_string($con, $_POST['email']);
            $dni = mysqli_real_escape_string($con, $_POST['dni']);
            $password = mysqli_real_escape_string($con, $_POST['password']);
            $admin = mysqli_real_escape_string($con, $_POST['admin']);
            if ($admin == "admin") {
                $admin = 1;
            } else {
                $admin = 0;
            }
            $sql6 = "UPDATE usuaris set nombre='".$nombre."', etapa='".$etapa."', admin=".$admin.", email='".$email."', dni='".$dni."', password='".$password."' WHERE ID = '".$id."' LIMIT 1";
            if (mysqli_query($con,$sql6)) {
              header("Location: users.php?msg=newsuccessful");
            } else {
            die ("<p class='alert-danger'>Error editando el usuario: " . mysqli_error($con) . "</p>");
            }
          } else {
            $query = mysqli_query($con, "SELECT * FROM usuaris WHERE ID = '".mysqli_real_escape_string($con, $_GET['id'])."'") or die("<div class='alert-danger'>".mysqli_error()."</div>");
            $row = mysqli_fetch_array($query);
            if ($row['admin'] == 1) {
              $admin1 = " selected='selected'";
              $student1 = "";
            } else {
              $admin1 = "";
              $student1 = " selected='selected'";
            }
            ?>
            <form action="edituser.php?sent=1" method="POST" autocomplete="off">
              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" name="id" id="id" value="<?=$row['ID']?>" readonly="readonly" autocomplete="off">
                <label class="mdl-textfield__label" for="nombre">ID</label>
              </div>
              <br>
              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" name="nombre" id="nombre" value="<?=$row['nombre']?>" autocomplete="off">
                <label class="mdl-textfield__label" for="nombre">Nombre</label>
              </div>
              <br>
              <div class="mdlext-selectfield mdlext-js-selectfield mdlext-selectfield--floating-label">
                <select name="admin" id="admin" class="mdlext-selectfield__select">
                  <option value=""></option>
                  <option value="student"<?php echo $student1; ?>>patient</option>
                  <option value="admin"<?php echo $admin1; ?>>admin</option>
                </select>
                <label for="admin" class="mdlext-selectfield__label">Tipo</label>
              </div>
              <br>
              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" name="etapa" id="etapa"  value="<?=$row['etapa']?>" autocomplete="off">
                <label class="mdl-textfield__label" for="etapa">Etapa</label>
              </div>
              <br>
              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" name="email" id="email"  value="<?=$row['email']?>" autocomplete="off">
                <label class="mdl-textfield__label" for="email">Email</label>
              </div>
              <br>
              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" name="dni" id="dni" maxlength="9"  value="<?=$row['dni']?>" autocomplete="off">
                <label class="mdl-textfield__label" for="dni">DNI</label>
              </div>
              <br>
              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="password" name="password" id="password" maxlength="9" autocomplete="off">
                <label class="mdl-textfield__label" for="password">Contraseña</label>
              </div>
              <p><button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--accent">Editar</button></p>
            </form>
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
