<?php
require("../core.php");
require("../lib/PHPMailerAutoload.php");

$return = array();

$dia = (INT)$_POST['dia'];
$hora = (INT)$_POST['hora'];
$posicio = (INT)$_POST['posicio'];
$idvisit = (INT)$_POST['idvisit'];

if (loggedin()) {
	$userid = $_SESSION['id'];
	try {
		if (!isset($config["visits"])) {
			throw new Exception("Server error");
		}
		$found = false;
		foreach($config["visits"] as $visit) {
			if ($visit["date"] == $dia) {
				$possiblehacking = "The arguments were not given correctly. Are you trying to hack my website?";
				$found = true;
				if ($hora < $visit["firsttime"] || $hora > $visit["lasttime"]) {
					throw new Exception($possiblehacking);
				}
				if ($posicio > $visit["number"]) {
					throw new Exception($possiblehacking);
				}
				break;
			}
		}
		if (!$found) {
			throw new Exception($possiblehacking);
		}
	} catch(Exception $e) {
		$return['status'] = "errorreservation";
		$errorcode = rand_hex();
		$return['statustxt'] = "An error happened during registration: '".$e->getMessage()."' Please, give the developer (Adrià Vilanova) the following error code: '".$errorcode."'";
		file_put_contents("../logs/reservar.log", "- Error '".$errorcode."': '".$e->getFile().":".$e->getLine()."'\n", FILE_APPEND | LOCK_EX);
		die(json_encode($return));
	}
	$letscheck = mysqli_query($con, "SELECT ID FROM reserva WHERE usuari = ".(INT)$_SESSION['id']." AND dia = '".$config["visits"][$idvisit]["date"]."'");
	if (!mysqli_num_rows($letscheck)) {
		$rowtheboat = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM reserva WHERE usuari = ".(INT)$_SESSION['id']));
		$codename = "";
		foreach ($config['visits'] as $visit) {
			if ($visit["date"] == $rowtheboat["dia"]) {
				$codename = $visit["codename"];
				break;
			}
		}
		if ($codename == $config["visits"][$idvisit]["codename"]) {
			goto heyitswrong;
		}
		$query = mysqli_query($con, "INSERT INTO reserva (dia, hora, posicio, usuari) VALUES (".$dia.", ".$hora.", ".$posicio.", ".$userid.")");
		if ($query) {
			/* DELETE */
			$fecha = date("Ymd", $dia);
			/* ICS metadata */
			$summary = "Visita médica";
			$datestart = "TZID=Europe/Madrid:".$fecha."T".mintotime($hora);
			$dateend = "TZID=Europe/Madrid:".$fecha."T".mintotime((INT)$hora + (INT)$config["visits"][$idvisit]["interval"]);
			$address = "Av. Pearson, 39-45, 08034, Barcelona, Spain";
			$uri = "https://metge.stpauls.es/";
			$description = "Visita al médico de la escuela.";
			function dateToCal($timestamp) {
				return date('Ymd\THis\Z', $timestamp);
			}
			function escapeString($string) {
				return preg_replace('/([\,;])/','\\\$1', $string);
			}
			$ics = "BEGIN:VCALENDAR\nVERSION:2.0\nPRODID:-//hacksw/handcal//NONSGML v1.0//EN\nCALSCALE:GREGORIAN\nBEGIN:VEVENT\nDTEND;".$dateend."\nUID:".uniqid()."\nDTSTAMP:".dateToCal(time())."\nLOCATION:".escapeString($address)."\nDESCRIPTION:".escapeString($description)."\nURL;VALUE=URI:".escapeString($uri)."\nSUMMARY:".escapeString($summary)."\nDTSTART;".$datestart."\nEND:VEVENT\nEND:VCALENDAR";

			$emailto = userdata("email");
			$nombre = userdata("nombre");

			$mail = new PHPMailer(true);

			try {
				$mail->IsSMTP(); // telling the class to use SMTP
				$mail->SMTPAuth   = true;                  // enable SMTP authentication
				$mail->Host       = "smtp.mailgun.org"; // sets the SMTP server
				$mail->Port       = 587;                    // set the SMTP port for the GMAIL server
				$mail->Username   = "postmaster@avm99963.com"; // SMTP account username
				$mail->Password   = "18bcaa75fdf6fe7da72a66199565b973";        // SMTP account password


				$mail->setFrom('postmaster@avm99963.com', 'St. Paul\'s School');
				$mail->addReplyTo('postmaster@avm99963.com', 'St. Paul\'s School');
				$mail->addAddress($emailto, $nombre);

				$mail->CharSet = 'UTF-8';

				$mail->Subject = 'Confirmación de reserva del médico – St. Paul\'s school';
				$mail->msgHTML('<p>Hola '.$nombre.':</p> <p>Le informamos de que su visita del médico del colegio para el día '.date('d', $dia).'/'.date('m', $dia).'/'.date('Y', $dia).' a las '.mintohourmin($hora).' ha sido correctamente procesada.</p> <p>Le adjuntamos junto a este correo un archivo de calendario que puede usar para acordarse de su visita.</p> <p>Reciba un cordial saludo.</p>');

				$mail->AddStringAttachment($ics, "event.ics");

				if ($mail->Send()) {
					$return['status'] = "ok";
					$return['text'] = userdata("nombre");
					$return['dia'] = $dia;
					$return['hora'] = $hora;
					$return['posicio'] = $posicio;
				} else {
					$return['status'] = "mailnotsend";
					$return['statustxt'] = "No se ha podido enviar un email de confirmación a su dirección de correo electrónico. Igualmente, la reserva se ha efectuado correctamente.";
				}
			} catch (phpmailerException $e) {
				$return['status'] = "mailnotsend_critical";
			} catch (Exception $e) {
				$return['status'] = "mailnotsend_warning";
			}
			$return['status'] = "ok";
			$return['text'] = userdata("nombre");
			$return['dia'] = $dia;
			$return['hora'] = $hora;
			$return['posicio'] = $posicio;
		} else {
			$return['status'] = "errorreservation";
			$errorcode = rand_hex();
			$return['statustxt'] = "An error happened during registration. Please, give the developer (Adrià Vilanova) the following error code: '".$errorcode."'";
			file_put_contents("../logs/reservar.log", "- Error '".$errorcode."': '".mysqli_error($con)."'\n", FILE_APPEND | LOCK_EX);
		}
	} else {
		heyitswrong:
		$return['status'] = "reservationactive";
		$return['statustxt'] = "You have already made a reservation.";
	}
} else {
	$return['status'] = "notlogged";
	$return['statustxt'] = "User was not logged in with the account.";
}

echo json_encode($return);
?>
