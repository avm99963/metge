<?php
if (isadmin()) {
?>
<div class="text left small">
	<p><img src="img/dashboard.png"> <a href="dashboard.php">Panel de Control</a></p>
	<p class="withoutmarginbottom"><img src="img/group.png"> <a href="users.php">Usuarios</a></p>
		<p class="padding40 withoutmarginbottom" style="margin-top:5px;"><a href="newuser.php">Añadir un usuario</a></p>
    	<p class="padding40" style="margin-top:5px;"><a href="csv.php">Subir múltiples usuarios</a></p>
		<p><img src="img/print.png"> <a href="print.php">Imprimir horarios</a></p>
    <p><img src="img/settings.png"> <a href="configuracion.php">Configuración</a></p>
</div>
<?php
}
?>
