<?php
require("core.php");
$msg = "";
if (isset($_GET['msg']) && $_GET['msg'] == "loginwrong")
$msg = '<p class="alert-danger">Usuario y/o contraseña incorrecto</p>';
if (isset($_GET['msg']) && $_GET['msg'] == "empty")
$msg = '<p class="alert-warning">Por favor, rellena todos los campos</p>';
if (isset($_GET['msg']) && $_GET['msg'] == "logoutsuccess")
$msg = '<p class="alert-success">¡Has hecho "log out" correctamente! Ten un buen día :-)</p>';
?>
<!DOCTYPE html>
<html>
<head>
<?php require ("head.php"); ?>
<title><?php echo $appname; ?></title>
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
<body>
<div class="content">
	<?php include "nav.php"; ?>
	<article>
		<?php anuncio(); ?>
		<div class="text" style='text-align:center;'>
		<h1>Login</h1>
		<?=$msg?>
		<?php
		if (loggedin())
		{
		?>
		<p>¡Hola <?php echo userdata('nombre'); ?>!</p>
		<?php
		$whatif = mysqli_query($con, "SELECT * FROM reserva WHERE usuari = ".(INT)$_SESSION['id']);
		if (mysqli_num_rows($whatif)) {
			$link = "reservar.php";
		} else {
			$link = "welcome.php";
		}
		?>
		<p><a class="button-link" href="<?=$link?>">Reservar horas</a><?php if (isadmin()) {?> <a class="button-link" href="dashboard.php">Panel de control</a><?php } ?></p>
		<?php
        }
		else
		{
		?>
		<form action="login.php" method="POST" autocomplete="off" id="formulario">
			<p><label for="id">Nombre:</label> <input type="text" name="id" id="id" required="required" style="width:200px;" autocomplete="off"></p>
			<p><label for="password">Contraseña:</label> <input type="password" name="password" id="password" required="required"></p>
			<p><input type="submit" value="Login" class="button-link"></p>
		</form>
		<?php
		}
		?>
<?php
// Select * from table_name will return false if the table does not exist.
$val = mysqli_query($con, "select * from usuaris");
if($val === FALSE)
{
	echo "<a href='install.php' style='color:red;'>¡Instala la aplicación antes de usarla!</a>";
}
?>
		</div>
	</article>
</div>
</body>
</html>
