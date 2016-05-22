<!--
The MIT License (MIT)

Copyright (c) 2016 Adrià Vilanova Martínez

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
-->
<?php
require("core.php");
if (isadmin())
{
?>
<!DOCTYPE html>
<html>
<head>
<?php require ("head.php"); ?>
<title>Enviar correos con contraseñas - <?php echo $appname; ?></title>
<script>
$(document).ready(function() {
	var options, a;
	jQuery(function(){
	   options = { serviceUrl:'ajax/users.php', onSelect: function(array){ window.location = "user.php?id="+array.data; } };
	   a = $('#usuario').autocomplete(options);
	});
});
</script>
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
		<h1>Enviar correos con contraseñas</h1>
		<?php
		if (isset($_POST['send']) && $_POST['send'] == "1")
		{
			require_once('lib/recaptchalib.php');
			$privatekey = "6Levu-ISAAAAAIJV9V6q7D0TriUuq0lEs1MiVlw7";
			$resp = recaptcha_check_answer ($privatekey,
			                                $_SERVER["REMOTE_ADDR"],
			                                $_POST["recaptcha_challenge_field"],
			                                $_POST["recaptcha_response_field"]);

			if (!$resp->is_valid) {
				die ("The reCAPTCHA wasn't entered correctly. <a href='email.php'>Try again</a>.");
			} else {
				$query = mysqli_query($con, "SELECT * FROM usuaris");
				while($row = mysqli_fetch_array($query))
				{
					$nombre = $row['nombre'];
					$para = $row['email'];
					$contraseña = $row['password'];

					// subject
					$titulo = 'Contraseña ';

					// message
					$mensaje = '
					<html>
					<head>
					  <title>Contraseña de la revisión médica</title>
					  <meta charset="UTF-8">
					</head>
					<body>
					  <p>Hola '.$nombre.'!</p>
					  <p>A continuación tienes la contraseña con la que puedes entrar para reservar hora en la revisión médica:</p>
					  <p><b>'.$contraseña.'</b></p>
					  <p>Atentamente,</p>
					  <p>{empresa}</p>
					</body>
					</html>
					';

					// Para enviar un correo HTML mail, la cabecera Content-type debe fijarse
					$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
					$cabeceras .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

					// Cabeceras adicionales
					$cabeceras .= 'To: '.$nombre.' <'.$para.'>' . "\r\n";
					$cabeceras .= 'From: Adrià <postmaster@avm99963.com>' . "\r\n";
					// Mail it
					if (mail($para, $titulo, $mensaje, $cabeceras))
					{
						echo "<p style='color:green;'>Hemos enviado el email a <b>".$nombre."</b> correctamente</p>";
					}
					else
					{
						echo "<div class='alert-danger'>No se ha podido enviar el email a <b>".$nombre."</b></div>";
					}
				}
				echo "<div class='alert alert-success'><b>Emails</b> enviados correctamente.</div>";
			}
		}
		else
		{
		?>
			<p>¿Quieres enviar un correo a todos los usuarios (pacientes y administradores)?</p>
			<form method="POST">
				<input type="hidden" name="send" value="1">
				<?php
				require_once('lib/recaptchalib.php');
				$publickey = "6Levu-ISAAAAAJmFOWndp0Y-8ZKAwx6wgD5wLEbU"; // you got this from the signup page
				echo recaptcha_get_html($publickey);
				?>
			    <hr>
				<input type="submit" class="button-link-red" value="Sí"> <a href="dashboard.php" class="button-link">No</a>
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
