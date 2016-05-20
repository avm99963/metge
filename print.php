<?php
require("core.php");
if (isadmin())
{
?>
<!DOCTYPE html>
<html>
<head>
<?php require ("head.php"); ?>
<title>Imprimir horarios - <?php echo $appname; ?></title>
<style>
td, th
{
  padding:5px;
}
table
{
  border-collapse:collapse;
}
table, th, td
{
  border: 1px solid black;
}
</style>
</head>
<body>
<div class="content">
  <?php include "nav.php"; ?>
  <article>
    <?php anuncio(); ?>
    <?php require("sidebar.php"); ?>
    <div class="text right large">
    <h1>Imprimir horarios</h1>
    <?php
    if (isset($_POST["horario"])) {

    } else {
      if (isset($config["visits"])) {
    ?>
    <form action="print_2.php" method="POST">
      <select name="horario">
        <?php
        foreach ($config["visits"] as $i => $visit) {
          echo "<option value='".$i."'>".$visit["name"]." - ".date("d F", $visit["date"])."</option>";
        }
        ?>
      </select>
      <input type="submit" value="Imprimir">
    </form>
    <?php
      } else {
        echo "<div class='alert-warning'>No hay ninguna visita programada...</div>";
      }
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
