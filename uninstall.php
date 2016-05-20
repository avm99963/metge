<?php
require("core.php");
$result = mysqli_query($con, "SHOW TABLES FROM ".mysqli_real_escape_string($con, $nombre_db));
while ($row = mysqli_fetch_array($result))
{
	mysqli_query($con, "DROP TABLE ".$row[0]) or die("Error");
}
// comprobamos que se haya cerradi la sesión
    if(isset($_SESSION['id'])) { 
        session_destroy();
    }else { 
        echo "No se ha hecho logout. "; 
    }
echo "All deleted!";
?>