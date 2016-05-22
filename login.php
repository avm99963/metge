<?php
require('core.php');
$nombre = mysqli_real_escape_string($con, $_POST['id']);
$password = mysqli_real_escape_string($con, $_POST['password']);
if (empty($nombre) || empty($password)) {
	header("Location: index.php?msg=empty");
	echo "Please fill in all form.";
} else {
	$query = mysqli_query($con, "SELECT * FROM usuaris WHERE nombre='".$nombre."' and password='".$password."'");
	if (mysqli_num_rows($query))
	{
		$row = mysqli_fetch_array($query) or die(mysqli_error());
		$_SESSION['id'] = $row['ID'];
		if (isadmin()) {
			header("Location: dashboard.php");
		} else {
			header("Location: index.php");
		}
	} else {
		header("Location: index.php?msg=loginwrong");
		echo "User data incorrect :-(";
	}
}
?>
