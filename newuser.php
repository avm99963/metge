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
if (isadmin()) {
  $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
  $etapa = mysqli_real_escape_string($con, $_POST['etapa']);
  $email = mysqli_real_escape_string($con, $_POST['email']);
  $dni = mysqli_real_escape_string($con, $_POST['dni']);
  $num_caracteres = $config['password']['characters']; // asignamos el número de caracteres que va a tener la nueva contraseña
  $nueva_clave = $dni; // generamos una nueva contraseña de forma aleatoria
  $password = mysqli_real_escape_string($con, $nueva_clave);
  $admin = mysqli_real_escape_string($con, $_POST['admin']);
  if ($admin == "admin") {
        $admin = "TRUE";
  } else {
        $admin = "FALSE";
  }
  $sql6 = "INSERT INTO usuaris (nombre, admin, password, etapa, email, dni) VALUES ('".$nombre."', $admin, '".$password."', '".$etapa."', '".$email."', '".$dni."')";
  if (mysqli_query($con,$sql6)) {
    header("Location: users.php?msg=addedsuccessful");
    exit();
  } else {
    die ("<p class='alert-danger'>Error creando el usuario: " . mysqli_error($con) . "</p>");
  }
} else {
      header('HTTP/1.0 404 Not Found');
}
?>
