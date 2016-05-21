<header class="mdl-layout__header mdl-layout__header--scroll">
  <div class="mdl-layout__header-row">
    <!-- Title -->
    <span class="mdl-layout-title">Reservar hora</span>
  </div>
</header>
<div class="mdl-layout__drawer">
  <span class="mdl-layout-title"><?=$appname?></span>
  <nav class="mdl-navigation">
    <a class="mdl-navigation__link" href="index.php">Home</a>
    <?php if (isadmin()) { ?><a class="mdl-navigation__link" href="dashboard.php">Panel de control</a><?php } ?>
    <a class="mdl-navigation__link" href="logout.php">Cerrar sesión</a>
  </nav>
</div>
