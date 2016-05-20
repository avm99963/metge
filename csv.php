<?php
require("core.php");
if (isadmin())
{
?>
<!DOCTYPE html>
<html>
<head>
<?php require ("head.php"); ?>
<title>Añadir múltiples usuarios a la vez - <?php echo $appname; ?></title>
</head>
<body>
<div class="content">
	<?php include "nav.php"; ?>
	<article>
		<?php anuncio(); ?>
		<?php require("sidebar.php"); ?>
		<div class="text right large">
		<h1>Añadir múltiples usuarios a la vez</h1>
		<?php
		if (isset($_GET["sent"]) && $_GET['sent'] == "1")
		{
			$fichero = $_FILES['file']['tmp_name'];
			$csv = file_get_contents($fichero);
			$csv = iconv("ISO-8859-1", "UTF-8", $csv);
			$lineas = explode("\r", $csv);
			$start = FALSE;
			foreach($lineas as $linea)
			{
				$linea = str_replace("\n", "", $linea);
				if ($start === FALSE)
				{
					if ($linea != "ETAPA;NOMBRE;EMAIL;DNI")
					{
						die ("<div class='alert alert-danger'>Has elegido el archivo equivocado.</div>");
					}
					$start = TRUE;
				}
				else
				{
					$sinespacios = preg_replace('/\s+/', '', $linea);
					if (!empty($sinespacios))
					{
						$values = explode(";", $linea); // 0 => ETAPA; 1 => NOMBRE; 2 => EMAIL; 3 => DNI
						$etapa = mysqli_real_escape_string($con, $values[0]);
						$nombre = mysqli_real_escape_string($con, $values[1]);
						$originalemail = mysqli_real_escape_string($con, $values[2]);
						$email = strtolower($originalemail)."@stpauls.es";
						$dni = mysqli_real_escape_string($con, $values[3]);
						$dni = str_replace("-", "", $dni);
						$num_caracteres = $config['password']['characters'];
						$nueva_clave = $dni; // generamos una nueva contraseña de forma aleatoria 
						$password = mysqli_real_escape_string($con, $nueva_clave);
						$sql = "INSERT INTO usuaris (etapa, nombre, email, dni, password, admin) VALUES ('{$etapa}', '{$nombre}', '{$email}', '{$dni}', '{$password}', FALSE)";
						if (mysqli_query($con, $sql))
						{
							echo "<p style='color:green;'>Hemos añadido a <b>".$nombre."</b> (de la etapa <b>".$etapa."</b>) con email <b>".$email."</b> y DNI <b>".$dni."</b></p>";
						}
						else
						{
							echo "<div class='alert-danger'>No se ha podido añadir a <b>".$nombre."</b> en la tabla. (<i>".mysqli_error($con)."</i>)</div>";
						}
					}
				}
			}
		}
		else
		{
		?>
		<p>Importa el archivo CSV:</p>
        <form action="csv.php?sent=1" method="POST" enctype="multipart/form-data">
        	<p><input type="file" name="file" accept=".csv"></p>
            <p><input type="submit" value="Envia"></p>
        </form>
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