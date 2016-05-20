<?php
require("core.php");
if (isadmin())
{
?>
<!DOCTYPE html>
<html>
<head>
<?php require ("head.php"); ?>
<title>Usuarios - <?php echo $appname; ?></title>
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
<script>
$(document).ready(function() {
	var options, a;
	jQuery(function(){
	   options = { serviceUrl:'ajax/users.php?admin=1', onSelect: function(array){ window.location = "user.php?id="+array.data; } };
	   a = $('#usuario').autocomplete(options);
	});
});
</script>
</head>
<body>
<div class="content">
	<?php include "nav.php"; ?>
	<article>
		<?php anuncio(); ?>
		<?php require("sidebar.php"); ?>
		<div class="text right large">
		<h1>Lista de usuarios</h1>
		<?php
		$msg = "";
		if (isset($_GET["msg"]) && $_GET['msg'] == "newsuccessful")
			$msg = "<p class='alert-success'>Usuario editado.</p>";
		if (isset($_GET["msg"]) && $_GET['msg'] == "deletesuccessful")
			$msg = "<p class='alert-success'>Usuario eliminado.</p>";
		echo $msg;
		?>
		<p><b>Busca a un usuario por nombre</b>: <input type="text" id="usuario" style="width:200px;"> | <a href="email.php">Envía emails con contraseñas</a></p>
		<table>
			<thead>
				<tr>
					<th class="id">ID</th>
					<th class="nombre">Nombre</th>
					<th class="etapa">Etapa</th>
					<th class="email">Email</th>
					<th class="dni">DNI</th>
					<th class="admin">Admin</th>
					<th class="users_password">Password</th>
				</tr>
			</thead>
			<tbody>
		<?php
		$query = mysqli_query($con, "SELECT * FROM usuaris ORDER BY ID ASC") or die("<div class='alert-danger'>".mysqli_error()."</div>");
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
			echo "<tr><td class='id'>".$row['ID']."</td><td class='nombre'>".$row['nombre']."</td><td class='etapa'>".$row['etapa']."</td><td class='email'>".$row['email']."</td><td class='dni'>".$row['dni']."</td><td class='admin'>".$type."</td><td class='users_password' id='pass".$row['ID']."'><span style='cursor:pointer;color:blue;' onclick=\"document.getElementById('pass".$row['ID']."').innerHTML = '".$row['password']."';\">Pulsa</span></td><td class='newuser'><a href='user.php?id=".$row['ID']."'><img src='img/view_details.png'></a></td><td class='edituser'><a href='edituser.php?id=".$row['ID']."'><img src='img/make_decision.png'></a></td><td class='deleteuser'><a href='deleteuser.php?id=".$row['ID']."'><img src='img/bad_decision.png'></a></td></tr>";
		}
		?>
			</tbody>
		</table>
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