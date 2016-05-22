<?php
require("core.php");
?>
<!DOCTYPE html>
<html>
<head>
<?php require ("head.php"); ?>
<title>Cancelar reserva - <?php echo $appname; ?></title>
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
		<?php if (isadmin()) { require("sidebar.php"); } ?>
		<div <?php if (!isadmin()) { ?>style="display: inline-block; width: 100%;"<?php } ?> class="text <?php if (isadmin()) { ?>right large<?php } ?>">
		<h1>Cancelar reserva</h1>
		<?php
		$query = mysqli_query($con, "SELECT * FROM reserva WHERE usuari = ".(INT)$_SESSION["id"]);
		if (mysqli_num_rows($query)) {
			if (!isset($_GET["sent"]) || (isset($_GET["sent"]) && $_GET["sent"] != "1")) {
			?>
		<p>¿Estás seguro que quieres eliminar la reserva de tu visita? <span style="color:red;font-weight:bold;">Esta acción no se puede revertir</span></p>
		<p><a href="cancelreservar.php?sent=1&dia=<?=$_GET["dia"]?>" class="button-link-red">Sí</a> <a href="reservar.php" class="button-link">No</a></p>
			<?php
			} else {
				if (isset($_GET["dia"])) {
					$row = mysqli_fetch_assoc($query);
					$query2 = mysqli_query($con, "DELETE FROM reserva WHERE usuari = '".(INT)$_SESSION["id"]."' AND dia = '".(INT)$_GET["dia"]."' LIMIT 1");
					if ($query2) {
						header("Location: reservar.php#".$row["codename"]);
					} else {
						echo "<p class='alert-danger'>Error cancelando la reserva.</p>";
					}
				} else {
					echo "<p class='alert-warning'>No se ha realizado ninguna acción.</p>";
				}
			}
		} else {
			echo "<p>No tienes ninguna reserva activa.</p>";
		}
		?>
		</div>
	</article>
</div>
</body>
</html>
