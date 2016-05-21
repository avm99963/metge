<?php
require("core.php");
if (isadmin())
{
?>
<!DOCTYPE html>
<html>
<head>
<?php require ("head.php"); ?>
<title>Nuevo usuario - <?php echo $appname; ?></title>
</head>
<body>
<div class="content">
      <?php include "nav.php"; ?>
      <article>
            <?php anuncio(); ?>
            <?php require("sidebar.php"); ?>
            <div class="text right large">
            <h1>Nuevo usuario</h1>
            <?php
            if (isset($_GET["sent"]) && $_GET['sent'] == "1")
            {
$nombre = mysqli_real_escape_string($con, $_POST['nombre']);
$etapa = mysqli_real_escape_string($con, $_POST['etapa']);
$email = mysqli_real_escape_string($con, $_POST['email']);
$dni = mysqli_real_escape_string($con, $_POST['dni']);
$num_caracteres = $config['password']['characters']; // asignamos el número de caracteres que va a tener la nueva contraseña
$nueva_clave = $dni; // generamos una nueva contraseña de forma aleatoria
$password = mysqli_real_escape_string($con, $nueva_clave);
$admin = mysqli_real_escape_string($con, $_POST['admin']);
if ($admin == "admin")
{
      $admin = "TRUE";
}
else
{
      $admin = "FALSE";
}
$sql6 = "INSERT INTO usuaris (nombre, admin, password, etapa, email, dni) VALUES ('".$nombre."', $admin, '".$password."', '".$etapa."', '".$email."', '".$dni."')";
if (mysqli_query($con,$sql6))
  {
  echo "<p class='alert-success'>Usuario creado.</p>";
  }
else
  {
  die ("<p class='alert-danger'>Error creando el usuario: " . mysqli_error($con) . "</p>");
  }
            }
            ?>
          <form action="newuser.php?sent=1" method="POST" id="install-form" autocomplete="off">
            <p><label for="nombre">Nombre</label>: <input type="text" name="nombre" id="nombre" required="required"></p>
            <p><label for="admin">Tipo</label>: <select name="admin"><option value="student">patient</option><option value="admin">admin</option></select></p>
            <p><label for="etapa">Etapa</label>: <input type="text" name="etapa" id="etapa" required="required"></p>
            <p><label for="email">Email</label>: <input type="email" name="email" id="email" required="required"></p>
            <p><label for="dni">DNI</label>: <input type="text" name="dni" id="dni" required="required" maxlength="9"></p>
            <p><input type="submit" value="Crear nuevo usuario" class="button-link"></a></p>
          </form>
            </div>
      </article>
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
