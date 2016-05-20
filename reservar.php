<?php
require("core.php");
?>
<!DOCTYPE html>
<html>
<head>
<?php require ("head.php"); ?>
<title>Reservar hora - <?php echo $appname; ?></title>
<script>
window.load = function() {
	var options, a;
	jQuery(function(){
	   options = { serviceUrl:'ajax/users.php', onSelect: function(array){ window.location = "user.php?id="+array.data; } };
	   a = $('#usuario').autocomplete(options);
	});
};

var working = false;

function reservar(dia, hora, posicio, idvisit, codename)
{
	if (!working) {
		working = true;
		var id = dia+"-"+hora+"-"+posicio;
		var button_elements = document.getElementsByTagName("button");
		for (i = 0; i < button_elements.length; i++) {
			if (button_elements[i].disabled) {
				button_elements[i].setAttribute("data-disabled", "yes");
			}
			button_elements[i].disabled = true;
		}
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				console.log(xmlhttp.responseText);
				obj = JSON.parse(xmlhttp.responseText);
				if (obj.status == "ok") {
					document.getElementById(id).innerHTML = "<span>"+obj.text+" <a href='cancelreservar.php?dia="+obj.dia+"'><img style='height:16px;' src='img/cancel.png'></a></span>";
					document.getElementById(id).className = "user";
					for (i = 0; i < button_elements.length; i++) {
						if (button_elements[i].getAttribute("data-codename") != codename && button_elements[i].getAttribute("data-disabled") != "yes") {
							button_elements[i].disabled = false;
						}
					}
				} else {
					console.error("Error '"+obj.status+"': \""+obj.statustxt+"\"");
					alert("Error '"+obj.status+"': \""+obj.statustxt+"\"");
				}
			}
			working = false;
		}
		xmlhttp.open("POST","ajax/reservar.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("dia="+dia+"&hora="+hora+"&posicio="+posicio+"&idvisit="+idvisit);
	} else {
		console.warn("Hey, I'm working. Let me finish before sending another request :-P");
	}
}
</script>
<style>
td, th
{
	padding:5px;
}
table
{
	border-collapse:collapse;
	margin-bottom: 5px;
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
		<?php if (isadmin()) { require("sidebar.php"); } ?>
		<div <?php if (!isadmin()) { ?>style="display: inline-block; width: 100%;"<?php } ?> class="text <?php if (isadmin()) { ?>right large<?php } ?>">
		<h1>Reservar hora</h1>
		<?php
		if (isset($config['visits']))
		{
			$query = mysqli_query($con, "SELECT * FROM reserva");
			if (mysqli_num_rows($query)) {
				$reserva = array();
				while ($row = mysqli_fetch_array($query)) {
					$reserva[$row["dia"]][$row["hora"]][$row["posicio"]]["usuari"] = $row["usuari"];
					$reserva[$row["dia"]][$row["hora"]][$row["posicio"]]["nombre"] = userdata("nombre", $row["usuari"]);
				}
			}
			$groups = array();
			foreach ($config['visits'] as $visit) {
				$groups[$visit["codename"]] = "";
			}
			/*for ($i = 0; $i < (count($groups) - 1); $i++) {
				if ($groups[$i] === $groups[$i+1]) {
					unset($groups[$i+1]);
				}
			}*/ // Don't know why we have this code, so commenting it.
			$disabled = $groups;
			$letscheck = mysqli_query($con, "SELECT * FROM reserva WHERE usuari = ".(INT)$_SESSION['id']."");
			if (mysqli_num_rows($letscheck)) {
				while($rowtheboat = mysqli_fetch_array($letscheck)) {
					foreach ($config['visits'] as $visit) {
						if ($visit["date"] == $rowtheboat["dia"]) {
							$disabled[$visit["codename"]] = " disabled='true'";
						}
					}
				}
			}
			foreach($config['visits'] as $idvisit => $visit) {
				echo "<div class='day-reservar'>";
				if ($config['debugenabled'])
					echo "Firsttime: ".$visit["firsttime"]."; Interval: ".$visit["interval"]."; Lasttime".$visit["lasttime"];
				echo "<table><thead><tr><th>".$visit["name"]."</th>";
				for($i=1; $i<=$visit["number"]; $i++) {
					$fecha = date("d F", $visit["date"]);
					echo "<th>".$fecha."</th>";
				}
				echo "</thead><tbody>";
				for($i=$visit['firsttime']; $i<=$visit['lasttime']; $i=$i+$visit['interval']) {
					echo "<tr><td>".mintohourmin($i)."</td>";
					for($i2=1; $i2<=$visit["number"]; $i2++) {
						$placeholder_td = "";
						$td_cancel = "";
						if (isset($reserva[$visit["date"]][$i][$i2])) {
							if ($reserva[$visit["date"]][$i][$i2]["usuari"] == $_SESSION["id"]) {
								$placeholder_td = " class='user'";
								$td_cancel = " <a href='cancelreservar.php?dia=".$visit["date"]."'><img style='height:16px;' src='img/cancel.png'></a>";
								$name = $reserva[$visit["date"]][$i][$i2]["nombre"];
							} else {
								$placeholder_td = " class='reserved'";
								if (isadmin()) {
									$name = $reserva[$visit["date"]][$i][$i2]["nombre"];
								} else {
									$name = "-";
								}
							}
							$placeholder = "<span>".$name."</span>";
						} else {
							$placeholder = "<button onclick=\"reservar('".$visit["date"]."', '".$i."', '".$i2."', '".$idvisit."', '".$visit["codename"]."')\"".$disabled[$visit["codename"]]." data-codename='".$visit["codename"]."'>Reservar</button>";
						}
						echo "<td".$placeholder_td." id='".$visit['date']."-".$i."-".$i2."'>".$placeholder.$td_cancel."</td>";
					}
					echo "</tr>";
				}
				echo "</tbody></table></div>";
			}
			/*$i = 0;
			foreach($config['dates'][0] as $data)
			{
				$i++;
				$fecha = date("d F", $data);
				echo "<th>".$fecha."</th><th style='border-right:solid 4px black;'>".$fecha."</th>";
			}
			echo "</tr></thead><tbody>";
			foreach($config['hores'][0] as $id => $hora)
			{
				echo "<tr><td>".$hora."</td>";
				for($i2=0;$i2<$i;$i2++)
				{
					echo "<td id='".$config['dates'][0][$i2]."-".$config['hores'][0][$i2]."-1'><button onclick=\"reservar('".$config['hores'][0][$i2]."', '".$config['dates'][0][$i2]."', '1')\">Reservar</button></td><td style='border-right:solid 4px black;' id='".$config['dates'][0][$i2]."-".$config['hores'][0][$i2]."-2'><button onclick=\"reservar('".$config['hores'][0][$i2]."', '".$config['dates'][0][$i2]."', '2')\">Reservar</button></td>";
				}
				echo "</tr>";
			}
			echo "</tbody></table>";*/
		}
		else
		{
			echo "<div class='alert alert-danger'>No hay fechas definidas</div>";
		}
		?>
		</div>
	</article>
</div>
</body>
</html>
