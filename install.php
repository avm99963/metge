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
<?php require("core.php"); ?>
<!DOCTYPE html>
<html>
<head>
<?php require ("head.php"); ?>
<title>Install <?php $appname; ?></title>
</head>
<body>
<div class="content">
	<article>
		<div class="text" style='margin-top:10px;'>
		<h1 style='text-align:center;'>Install!</h1>
<?php
if (isset($_GET['install']) && $_GET['install'] == "1")
{
$sql = "CREATE TABLE usuaris
(
ID INT(13) NOT NULL AUTO_INCREMENT,
PRIMARY KEY(ID),
etapa VARCHAR(100),
nombre VARCHAR(100),
email VARCHAR(100),
dni VARCHAR(13),
admin BOOLEAN,
password VARCHAR(50)
)";
$sql2 = "CREATE TABLE reserva
(
ID INT(13) NOT NULL AUTO_INCREMENT,
PRIMARY KEY(ID),
hora VARCHAR(5),
dia INT(2),
posicio INT(2),
usuari INT(13)
)";
$nombre = mysqli_real_escape_string($con, $_POST['nombre']);
$password = mysqli_real_escape_string($con, $_POST['password']);
$etapa = mysqli_real_escape_string($con, $_POST['etapa']);
$email = mysqli_real_escape_string($con, $_POST['email']);
$dni = mysqli_real_escape_string($con, $_POST['dni']);
$sql6 = "INSERT INTO usuaris (nombre, admin, password, etapa, email, dni) VALUES ('".$nombre."', TRUE, '".$password."', '".$etapa."', '".$email."', '".$dni."')";
if (mysqli_query($con,$sql))
  {
  echo "<p style='color:green;'>Table 'usuaris' created successfully.</p>";
  }
else
  {
  die ("<p style='color:red;'>Error creating table 'usuaris': " . mysqli_error($con) . "</p>");
  }
  if (mysqli_query($con,$sql2))
  {
  echo "<p style='color:green;'>Table 'reserva' created successfully.</p>";
  }
else
  {
  die ("<p style='color:red;'>Error creating table 'reserva': " . mysqli_error($con) . "</p>");
  }
  if (mysqli_query($con,$sql6))
  {
  echo "<p style='color:green;'>Admin user created.</p>";
  }
else
  {
  die ("<p style='color:red;'>Error creating the admin user: " . mysqli_error($con) . "</p>");
  }
  echo "<p style='color:orange;'>Please, delete the file install.php!</p>";
  echo "<p><a href='index.php'>Go back and login with the data that you provided</a></p>";
}
else
{
// Select * from table_name will return false if the table does not exist.
$val = mysqli_query($con, "select * from usuaris");
if($val !== FALSE)
{
	echo "<p>The app is already installed!</p>";
}
else
{
?>
		<p>Welcome to the installer! Fill in your admin user data and click continue to create the Database :-)</p>
        <form action="install.php?install=1" method="POST" id="install-form" autocomplete="off">
            <p><label for="nombre">Nombre</label>: <input type="text" name="nombre" id="nombre" required="required"></p>
            <p><label for="ID">ID</label>: <input type="number" name="ID" id="ID" readonly="readonly" value="1"></p>
            <p><label for="password">Contraseña</label>: <input type="password" name="password" id="password" required="required" maxlength="50"></p>
            <p><label for="etapa">Etapa</label>: <input type="text" name="etapa" id="etapa" required="required"></p>
            <p><label for="email">Email</label>: <input type="email" name="email" id="email" required="required"></p>
            <p><label for="dni">DNI</label>: <input type="text" name="dni" id="dni" required="required" maxlength="9"></p>
		    <p><input type="submit" value="Install now!" class="button-link"></p>
      </form>
<?php
}
}
?>
		</div>
	</article>
</div>
</body>
</html>
