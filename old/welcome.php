<?php
require("core.php");
?>
<!DOCTYPE html>
<html>
<head>
<?php require ("head.php"); ?>
<title>Reservar hora - <?php echo $appname; ?></title>
<style>
h1 {
  text-align: center;
  font-size: 30px;
}
.welcome {
  text-align:center;
  font-size: 15px;
  font-weight: 400;
}
</style>
</head>
<body>
<div class="content">
  <?php include "nav.php"; ?>
  <article>
    <?php anuncio(); ?>
    <?php if (isadmin()) { require("sidebar.php"); } ?>
    <?php
    $whatif = mysqli_query($con, "SELECT * FROM reserva WHERE usuari = ".(INT)$_SESSION['id']);
    if (mysqli_num_rows($whatif)) {
      header("Location: reservar.php");
    }
    ?>
    <div <?php if (!isadmin()) { ?>style="display: inline-block; width: 100%;"<?php } ?> class="text <?php if (isadmin()) { ?>right large<?php } ?>">
    <h1>Bienvenido</h1>
    <p class="welcome">Bienvenido al sistema de horas para el médico de St. Paul's School. Para reservar le guiaremos en una serie de pasos. Después de completar estos pasos puede modificar sus horarios entrando de nuevo a esta página web.</p>
    <p style="text-align:center;"><a href="reservar_assist.php?v=0" class="button-link">Empecemos</a></p>
    </div>
  </article>
</div>
</body>
</html>
