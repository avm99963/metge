<?php
require("core.php");
if (isadmin())
{
?>
<!DOCTYPE html>
<html>
<head>
<?php require ("head.php"); ?>
<title>Editar usuario - <?php echo $appname; ?></title>
</head>
<body>
<div class="content">
      <?php include "nav.php"; ?>
      <article>
            <?php anuncio(); ?>
            <?php require("sidebar.php"); ?>
            <div class="text right large">
            <h1>Editar usuario</h1>
            <?php
            if (isset($_GET["sent"]) && $_GET['sent'] == "1")
            {
$id = mysqli_real_escape_string($con, $_POST['id']);
$nombre = mysqli_real_escape_string($con, $_POST['nombre']);
$etapa = mysqli_real_escape_string($con, $_POST['etapa']);
$email = mysqli_real_escape_string($con, $_POST['email']);
$dni = mysqli_real_escape_string($con, $_POST['dni']);
$password = mysqli_real_escape_string($con, $_POST['password']);
$admin = mysqli_real_escape_string($con, $_POST['admin']);
if ($admin == "admin")
{
      $admin = TRUE;
}
else
{
      $admin = FALSE;
}
$sql6 = "UPDATE usuaris set nombre='".$nombre."', etapa='".$etapa."', admin='".$admin."', email='".$email."', dni='".$dni."', password='".$password."' WHERE ID = '".$id."' LIMIT 1";
if (mysqli_query($con,$sql6))
  {
  header("Location: users.php?msg=newsuccessful");
  }
else
  {
  die ("<p class='alert-danger'>Error editando el usuario: " . mysqli_error($con) . "</p>");
  }
            }
            else
            {
            $query = mysqli_query($con, "SELECT * FROM usuaris WHERE ID = '".mysqli_real_escape_string($con, $_GET['id'])."'") or die("<div class='alert-danger'>".mysqli_error()."</div>");
			$row = mysqli_fetch_array($query);
      if ($row['admin'] == 1)
      {
        $admin1 = " selected='selected'";
        $student1 = "";
      }
      else
      {
        $admin1 = "";
        $student1 = " selected='selected'";
      }
            ?>
            <form action="edituser.php?sent=1" method="POST" id="install-form" autocomplete="off">
            <p><label for="id">ID</label>: <input type="number" name="id" id="id" required="required" value="<?php echo $row['ID']; ?>" readonly="readonly"></p>
            <p><label for="nombre">Nombre</label>: <input type="text" name="nombre" id="nombre" required="required" value="<?php echo $row['nombre']; ?>"></p>
            <p><label for="admin">Tipo</label>: <select name="admin"><option value="patient"<?php echo $student1; ?>>student</option><option value="admin"<?php echo $admin1; ?>>admin</option></select></p>
            <p><label for="etapa">Etapa</label>: <input type="text" name="etapa" id="etapa" required="required" value="<?php echo $row['etapa']; ?>"></p>
            <p><label for="email">Email</label>: <input type="email" name="email" id="email" required="required" value="<?php echo $row['email']; ?>"></p>
            <p><label for="dni">DNI</label>: <input type="text" name="dni" id="dni" required="required" maxlength="9" value="<?php echo $row['dni']; ?>"></p>
            <p><label for="password">Contraseña</label>: <input type="text" name="password" id="password" required="required" value="<?php echo $row['password']; ?>"></p>
            <p><input type="submit" class="button-link" value="Editar usuario"></p>
        </form>
        <?php
		}
        ?>
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