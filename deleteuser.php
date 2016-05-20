<?php
require("core.php");
if (isadmin())
{
?>
<!DOCTYPE html>
<html>
<head>
<?php require ("head.php"); ?>
<title>Borrar usuario - <?php echo $appname; ?></title>
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
		<h1>Borrar usuario</h1>
		<?php
		if (isset($_GET["sent"]) && $_GET['sent'] == "1")
		{
			$sql1 = "DELETE FROM reserva WHERE usuari = '".(INT)$_GET['id']."' LIMIT 1";
			$sql = "DELETE FROM usuaris WHERE ID = '".(INT)$_GET['id']."' LIMIT 1";
			if (mysqli_query($con, $sql1)) {
  				if (mysqli_query($con, $sql)) {
	  				header("Location: users.php?msg=deletesuccessful");
	 			}
				else {
					die ("<p class='alert-danger'>Error eliminando el usuario: " . mysqli_error($con) . "</p>");
				}
 			}
			else {
				die ("<p class='alert-danger'>Error eliminando la visita del usuario: " . mysqli_error($con) . "</p>");
			}
		}
		else
		{
		?>
		<table>
			<thead>
				<tr>
					<th>ID</th>
					<th>Nombre</th>
					<th>Etapa</th>
					<th>Email</th>
					<th>DNI</th>
					<th>Admin</th>
					<th>Password</th>
				</tr>
			</thead>
			<tbody>
		<?php
		$query = mysqli_query($con, "SELECT * FROM usuaris WHERE ID = '".mysqli_real_escape_string($con, $_GET['id'])."' LIMIT 1") or die("<div class='alert-danger'>".mysqli_error()."</div>");
		$row = mysqli_fetch_array($query);
			if ($row['admin'] == "1")
			{
				$type = "admin";
			}
			else
			{
				$type = "patient";
			}
			echo "<tr><td>".$row['ID']."</td><td>".$row['nombre']."</td><td>".$row['etapa']."</td><td>".$row['email']."</td><td>".$row['dni']."</td><td>".$type."</td><td class='users_password' id='pass".$row['ID']."'><span style='cursor:pointer;color:blue;' onclick=\"document.getElementById('pass".$row['ID']."').innerHTML = '".$row['password']."';\">Pulsa</span></td></tr>";
		?>
			</tbody>
		</table>
		<p>¿Estás seguro? <span style="color:red;font-weight:bold;">Esta acción no se puede revertir</span></p>
		<p><a href="deleteuser.php?id=<?php echo $_GET['id'];?>&sent=1" class="button-link-red">Sí</a> <a href="users.php" class="button-link">No</a></p>
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