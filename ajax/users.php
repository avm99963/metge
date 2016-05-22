<?php
require("../core.php");
$json = array();
$nombre = mysqli_real_escape_string($con, $_GET['query']);
$json['query'] = $nombre;
if (!isset($_GET["admin"]) || (isset($_GET["admin"]) && $_GET["admin"] != "1")) {
	$condition = " AND admin = 0";
} else {
	$condition = "";
}
$query = mysqli_query($con, "SELECT nombre, ID FROM usuaris WHERE (nombre LIKE '%".$nombre."%')".$condition);
if (mysqli_num_rows($query))
{
	$i = 0;
	while ($row = mysqli_fetch_array($query))
	{
		$json['suggestions'][$i]['value'] = $row['nombre'];
		$json['suggestions'][$i]['data'] = $row['ID'];
		$i++;
	}
}
else
{
	$json['suggestions'] = "";
}
echo json_encode($json);
?>
