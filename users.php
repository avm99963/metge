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
require_once("core.php");
if (isadmin())
{
$msg = "";
if (isset($_GET["msg"])) {
  if ($_GET['msg'] == "addedsuccessful")
    $msg = "Usuario añadido.";
  if ($_GET['msg'] == "newsuccessful")
    $msg = "Usuario editado.";
  if ($_GET['msg'] == "deletesuccessful")
    $msg = "Usuario eliminado.";
}

$md_header_row_more = '<div class="mdl-layout-spacer"></div>
      <div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable
                  mdl-textfield--floating-label mdl-textfield--align-right">
        <label class="mdl-button mdl-js-button mdl-button--icon"
               for="usuario">
          <i class="material-icons">search</i>
        </label>
        <div class="mdl-textfield__expandable-holder">
          <input class="mdl-textfield__input" type="text" name="usuario"
                 id="usuario">
        </div>
      </div>';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Usuarios – <?php echo $appname; ?></title>
  <meta charset="UTF-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Chrome for Android theme color -->
  <!--<meta name="theme-color" content="#2E3AA1">-->

  <link rel="stylesheet" href="bower_components/material-design-lite/material.min.css">
  <script src="bower_components/material-design-lite/material.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link href='https://fonts.googleapis.com/css?family=Roboto:400,500,700,300' rel='stylesheet' type='text/css'>

  <link rel="stylesheet" href="css/stylemd.css">
  <link rel="stylesheet" href="css/dashboard.css">

  <link rel="stylesheet" href="lib/mdl-ext/mdl-ext.min.css">
  <script src="lib/mdl-ext/mdl-ext.min.js"></script>

  <script src="js/users.js"></script>

  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
  <link rel="stylesheet" href="lib/datatables/dataTables.material.min.css">
  <script src="lib/datatables/jquery.dataTables.min.js"></script>
  <script src="lib/datatables/dataTables.material.min.js"></script>

  <script>
  window.addEventListener("load", function() {
    var datatable = $('.datatable').DataTable({
      paging:   false,
      ordering: false,
      info:     false,
      searching:true
    });

    document.querySelector("#usuario").addEventListener("input", function(evt) {
      this.search(evt.target.value);
      this.draw(true);
    }.bind(datatable));
  });
  </script>

  <style>
  .adduser {
    position: fixed;
    bottom: 16px;
    right: 16px;
  }

  .importcsv {
    position:fixed;
    bottom: 80px;
    right: 25px;
  }

  .adduser, .importcsv {
    z-index: 1000;
  }

  @media (max-width: 655px) {
    .extra {
      display: none;
    }
  }

  /* Hide datable's search box */
  .dataTables_wrapper .mdl-grid:first-child {
    display: none;
  }

  .dt-table {
    padding: 0!important;
  }

  #usuario {
    position: relative;
  }
  </style>

  <script src="bower_components/dialog-polyfill/dialog-polyfill.js"></script>
	<link rel="stylesheet" type="text/css" href="bower_components/dialog-polyfill/dialog-polyfill.css">
</head>
<body class="mdl-color--green">
  <button class="adduser mdl-button md-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--accent"><i class="material-icons">add</i><span class="mdl-ripple"></span></button>
  <button class="importcsv mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-color--grey-200"><i class="material-icons">file_upload</i></button>
  <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header mdl-layout--fixed-drawer">
    <?php require("adminmdnav.php"); ?>
    <main class="mdl-layout__content">
      <div class="page-content">
        <div class="main mdl-shadow--4dp">
          <h2>Usuarios</h2>
            <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp datatable">
        			<thead>
        				<tr>
        					<th class='extra'>ID</th>
        					<th class='mdl-data-table__cell--non-numeric'>Nombre</th>
        					<th class='mdl-data-table__cell--non-numeric extra'>DNI</th>
        					<th class='mdl-data-table__cell--non-numeric extra'>Admin</th>
                  <th></th>
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
            			echo "<tr><td class='extra'>".$row['ID']."</td><td class='mdl-data-table__cell--non-numeric'>".$row['nombre']."</td><td class='mdl-data-table__cell--non-numeric extra'>".$row['dni']."</td><td class='mdl-data-table__cell--non-numeric extra'>".$type."</td><td class='mdl-data-table__cell--non-numeric'><a href='user.php?id=".$row['ID']."'><img src='img/view_details.png'></a> <a href='edituser.php?id=".$row['ID']."'><img src='img/make_decision.png'></a> <a href='deleteuser.php?id=".$row['ID']."'><img src='img/bad_decision.png'></a></td></tr>";
            		}
            		?>
        			</tbody>
        		</table>
          </div>
        </div>
      </div>
    </main>
  </div>
  <dialog class="mdl-dialog" id="adduser">
    <form action="newuser.php" method="POST" autocomplete="off">
      <h4 class="mdl-dialog__title">Añadir usuario</h4>
      <div class="mdl-dialog__content">
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
          <input class="mdl-textfield__input" type="text" name="nombre" id="nombre" autocomplete="off">
          <label class="mdl-textfield__label" for="nombre">Nombre</label>
        </div>
        <br>
        <div class="mdlext-selectfield mdlext-js-selectfield mdlext-selectfield--floating-label">
          <select name="admin" id="admin" class="mdlext-selectfield__select">
            <option value=""></option>
            <option value="student">patient</option>
            <option value="admin">admin</option>
          </select>
          <label for="admin" class="mdlext-selectfield__label">Tipo</label>
        </div>
        <br>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
          <input class="mdl-textfield__input" type="text" name="etapa" id="etapa" autocomplete="off">
          <label class="mdl-textfield__label" for="etapa">Etapa</label>
        </div>
        <br>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
          <input class="mdl-textfield__input" type="text" name="email" id="email" autocomplete="off">
          <label class="mdl-textfield__label" for="email">Email</label>
        </div>
        <br>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
          <input class="mdl-textfield__input" type="text" name="dni" id="dni" maxlength="9" autocomplete="off">
          <label class="mdl-textfield__label" for="dni">DNI</label>
        </div>
      </div>
      <div class="mdl-dialog__actions">
        <button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--accent">Añadir</button>
        <button onclick="event.preventDefault(); document.querySelector('#adduser').close();" class="mdl-button mdl-js-button mdl-js-ripple-effect cancel">Cancelar</button>
      </div>
    </form>
  </dialog>
  <dialog class="mdl-dialog" id="importcsv">
    <form action="csv.php" method="POST" enctype="multipart/form-data">
      <h4 class="mdl-dialog__title">Importar CSV</h4>
      <div class="mdl-dialog__content">
        <p>Selecciona debajo el archivo CSV:</p>
        	<p><input type="file" name="file" accept=".csv"></p>
      </div>
      <div class="mdl-dialog__actions">
        <button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--accent">Importar</button>
        <button onclick="event.preventDefault(); document.querySelector('#importcsv').close();" class="mdl-button mdl-js-button mdl-js-ripple-effect cancel">Cancelar</button>
      </div>
    </form>
  </dialog>
  <?php
  if (isset($_GET['msg'])) {
    md_snackbar($msg);
  }
  ?>
</body>
</html>
<?php
}
else
{
	header('HTTP/1.0 404 Not Found');
}
?>
