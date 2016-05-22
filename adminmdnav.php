<?php
require_once("core.php");
if (isadmin()) {
?>
<header class="mdl-layout__header mdl-layout__header--scroll">
  <div class="mdl-layout__header-row">
    <!-- Title -->
    <span class="mdl-layout-title">Panel de control</span>
    <?php if (isset($md_header_row_more)) { echo $md_header_row_more; } ?>
  </div>
  <?php if (isset($md_header_more)) { echo $md_header_more; } ?>
</header>
<div class="mdl-layout__drawer">
  <span class="mdl-layout-title"><?=$appname?></span>
  <nav class="mdl-navigation">
    <a class="mdl-navigation__link" href="dashboard.php"><i class="material-icons">dashboard</i> Panel de Control</a>
    <a class="mdl-navigation__link" href="reservar.php"><i class="material-icons">event_seat</i> Reservas</a>
    <a class="mdl-navigation__link" href="users.php"><i class="material-icons">group</i> Usuarios</a>
    <!--<a class="mdl-navigation__link" href="newuser.php">Añadir un usuario</a>
    <a class="mdl-navigation__link" href="csv.php">Subir múltiples usuarios</a>-->
    <a class="mdl-navigation__link" href="print.php"><i class="material-icons">print</i> Imprimir horarios</a>
    <a class="mdl-navigation__link" href="configuracion.php"><i class="material-icons">settings</i> Configuración</a>
    <a class="mdl-navigation__link" class="mdl-navigation__link" href="logout.php"><i class="material-icons">power_settings_new</i> Cerrar sesión</a>
  </nav>
</div>
<?php
}
else
{
	header('HTTP/1.0 404 Not Found');
}
?>
