<?php
if (isadmin()) {
?>
<div class="text left small sidebar">
	<p><i class="material-icons">dashboard</i> <a href="dashboard.php">Panel de Control</a></p>
	<p class="withoutmarginbottom"><i class="material-icons">group</i> <a href="users.php">Usuarios</a></p>
		<p class="padding40 withoutmarginbottom" style="margin-top:5px;"><a href="newuser.php">Añadir un usuario</a></p>
    	<p class="padding40" style="margin-top:5px;"><a href="csv.php">Subir múltiples usuarios</a></p>
		<p><i class="material-icons">print</i> <a href="print.php">Imprimir horarios</a></p>
    <p><i class="material-icons">settings</i> <a href="configuracion.php">Configuración</a></p>
</div>
<?php
}
?>
