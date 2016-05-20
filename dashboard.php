<?php
require("core.php");
if (isadmin())
{
?>
<!DOCTYPE html>
<html>
<head>
<?php require ("head.php"); ?>
<title>Dashboard - <?php echo $appname; ?></title>
</head>
<body>
<div class="content">
	<?php include "nav.php"; ?>
	<article>
		<?php anuncio(); ?>
		<?php require("sidebar.php"); ?>
		<div class="text right large">
		<h1>Panel de Control</h1>
		<?php
		if (isset($_GET["msg"]) && $_GET['msg'] == "bulkuploadsuccess")
			echo "<div class='alert-success'>Usuarios subidos correctamente.</div>";
		?>
		¡Hola <?php echo userdata('nombre'); ?>! Bienvenido a tu Panel de Control.
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