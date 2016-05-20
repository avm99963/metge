<?php
require("core.php");
if (isadmin())
{
?>
<!DOCTYPE html>
<html>
<head>
<?php require ("head.php"); ?>
<title>Usuario - <?php echo $appname; ?></title>
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
		<h1>Usuario</h1>
		<?php
		$query = mysqli_query($con, "SELECT * FROM usuaris WHERE ID = '".mysqli_real_escape_string($con, $_GET['id'])."'") or die("<div class='alert-danger'>".mysqli_error()."</div>");
		while ($row = mysqli_fetch_array($query))
		{
			if ($row['admin'] == "1")
			{
				$type = "admin";
			}
			else
			{
				$type = "patient";
			}
			echo "<p><b>ID</b>: ".$row['ID']."</p><p><b>Nombre</b>: ".$row['nombre']."</p><p><b>Etapa</b>: ".$row['etapa']."</p><p><b>Tipo</b>: ".$type."</p><p><b>Email</b>: ".$row['email']."</p><p><b>DNI</b>: ".$row['dni']."</p><p><b>Password</b>: <span id='pass".$row['ID']."'><span style='cursor:pointer;color:blue;' onclick=\"document.getElementById('pass".$row['ID']."').innerHTML = '".$row['password']."';\">Pulsa</span></span></p><p><a href='edituser.php?id=".$row['ID']."' style='color:orange;'>Modificar usuario</a></p><p><a style='color:red;' href='deleteuser.php?id=".$row['ID']."'>Eliminar usuario</a></p>";
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