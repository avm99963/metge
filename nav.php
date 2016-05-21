<nav>
	<ul>
			<li><a href="index.php">Home</a></li>
			<?php
			if (isset($_SESSION['id']))
			{
			if (isadmin())
			{
			?>
            <li><a href="dashboard.php">Panel de control</a></li>
			<?php
			}
			?>
			<li><a href="logout.php">Logout</a></li>
			<li class="stickright firstnavright" style="color:#57B9FF;">Hola <b><?php echo userdata('nombre') ?></b></li>
			<?php
			}
			?>
	</ul>
</nav>
