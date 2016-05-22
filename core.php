<?php
/**
 * Core
 *

    /////  //////  //////  /////
   //     //  //  //  //  //___
  //     //  //  //////  //´´´´
 /////  //////  // //   /////

 */

// Timezone and language
date_default_timezone_set("Europe/Madrid");
setlocale(LC_ALL,"es_ES");

if (!file_exists("config.php")) {
  die("Please, edit the config in config.default.php and save the file as config.php.");
}

// Aquí se recoge la configuración
require("config.php");

// Aquí se accede a la BD y a la sesión
$con = @mysqli_connect($host_db, $usuario_db, $clave_db,$nombre_db) or die("Check Mysqli settings in config.php"); // Conectamos y seleccionamos BD

session_start();

// Custom error handler

function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return;
    }

    switch ($errno) {
    case E_USER_ERROR:
        echo "<div class='alert alert-danger'><b>Error:</b> [$errno] $errstr<br>\n";
        echo "  Fatal error on line $errline in file $errfile";
        echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
        echo "Aborting...</div>\n";
        exit(1);
        break;

    case E_USER_WARNING:
        echo "<div class='alert alert-warning'><b>Warning:</b> [$errno] $errstr on line $errline in file $errfile</div>\n";
        break;

    case E_WARNING:
        echo "<div class='alert alert-warning'><b>Warning:</b> [$errno] $errstr on line $errline in file $errfile</div>\n";
        break;

    case E_ERROR:
        echo "<div class='alert alert-danger'><b>Error:</b> [$errno] $errstr<br>\n";
        echo "  Fatal error on line $errline in file $errfile";
        echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
        echo "Aborting...</div>\n";
        exit(1);
        break;

    case E_USER_NOTICE:
        echo "<div class='alert alert-warning'><b>Notice:</b> [$errno] $errstr on line $errline in file $errfile</div>\n";
        break;

    default:
        echo "<div class='alert alert-warning'>Unknown error type: [$errno] $errstr on line $errline in file $errfile</div>\n";
        break;
    }

    /* Don't execute PHP internal error handler */
    return true;
}

$old_error_handler = set_error_handler("myErrorHandler");

// Aquí van todas las funciones
function anuncio()
{
	echo $GLOBALS['anuncio'];
}

function isadmin()
{
	$id = $_SESSION['id'];
	$query = mysqli_query($GLOBALS['con'], "SELECT * FROM usuaris WHERE ID = '".$id."'");
	$row = mysqli_fetch_array($query);
	if ($row['admin'] == 1)
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

function userdata($data2, $userid='currentuser')
{
	if ($userid == 'currentuser')
	{
		$id = $_SESSION['id'];
	}
	else
	{
		$id = $userid;
	}
	$data = mysqli_real_escape_string($GLOBALS['con'], $data2);
	$query = mysqli_query($GLOBALS['con'], "SELECT ".$data." FROM usuaris WHERE ID = '".$id."'");
    if (mysqli_num_rows($query)) {
    	$row = mysqli_fetch_array($query);
    	return $row[$data];
    } else {
        return false;
    }
}

function loggedin()
{
	if (isset($_SESSION['id']))
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

function randomfilename(){
	$chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
	$length = $GLOBALS['filenamelength'];
	$return = '';
	for($i = 0; $i < $length; $i++)
	{
	    $return .= $chars[mt_rand(0, 36)];
	}
	return $return;
}

function fancybox(){
?>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.4/jquery.fancybox.pack.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.4/jquery.fancybox.css" />
<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="http://avm99963.tk/pedagogia/lib/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="http://avm99963.tk/pedagogia/lib/fancybox//source/jquery.fancybox.js?v=2.1.4"></script>
<link rel="stylesheet" type="text/css" href="http://avm99963.tk/pedagogia/lib/fancybox//source/jquery.fancybox.css?v=2.1.4" media="screen">
<!-- Add Button helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="http://avm99963.tk/pedagogia/lib/fancybox//source/helpers/jquery.fancybox-buttons.css?v=1.0.5">
<script type="text/javascript" src="http://avm99963.tk/pedagogia/lib/fancybox//source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<!-- Add Thumbnail helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="http://avm99963.tk/pedagogia/lib/fancybox//source/helpers/jquery.fancybox-thumbs.css?v=1.0.7">
<script type="text/javascript" src="http://avm99963.tk/pedagogia/lib/fancybox//source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
<!-- Add Media helper (this is optional) -->
<script type="text/javascript" src="http://avm99963.tk/pedagogia/lib/fancybox//source/helpers/jquery.fancybox-media.js?v=1.0.5"></script>
<meta name="viewport" content="initial-scale = 1.0,width = device-width" />
<?php
}

// A simple FAST parser to convert BBCode to HTML
// Trade-in more restrictive grammar for speed and simplicty
//
// Syntax Sample:
// --------------
// [img]http://elouai.com/images/star.gif[/img]
// [url="http://elouai.com"]eLouai[/url]
// [mail="webmaster@elouai.com"]Webmaster[/mail]
// [size="25"]HUGE[/size]
// [color="red"]RED[/color]
// [b]bold[/b]
// [i]italic[/i]
// [u]underline[/u]
// [list][*]item[*]item[*]item[/list]
// [code]value="123";[/code]
// [quote]John said yadda yadda yadda[/quote]
//
// Usage:
// ------
/* <?php include 'bb2html.php'; ?>
// <?php $htmltext = bb2html($bbtext); ?> */
//
// Credits:
// ------
// author: Louai Munajim
// website: http://elouai.com
// date: 2004/Apr/18


function bb2html($text)
{
  $bbcode = array("<", ">",
                "[list]", "[*]", "[/list]",
                "[img]", "[/img]",
                "[b]", "[/b]",
                "[u]", "[/u]",
                "[i]", "[/i]",
                '[color="', "[/color]",
                "[size=\"", "[/size]",
                '[url="', "[/url]",
                "[mail=\"", "[/mail]",
                "[code]", "[/code]",
                "[quote]", "[/quote]",
                '"]');
  $htmlcode = array("&lt;", "&gt;",
                "<ul>", "<li>", "</ul>",
                "<img src=\"", "\">",
                "<b>", "</b>",
                "<u>", "</u>",
                "<i>", "</i>",
                "<span style=\"color:", "</span>",
                "<span style=\"font-size:", "</span>",
                '<a href="', "</a>",
                "<a href=\"mailto:", "</a>",
                "<code>", "</code>",
                "<table width=100% bgcolor=lightgray><tr><td bgcolor=white>", "</td></tr></table>",
                '">');
  $newtext = str_replace($bbcode, $htmlcode, $text);
  $newtext = nl2br($newtext);//second pass
  return $newtext;
}

function mintohourmin($time, $format = '%d:%s') {
    settype($time, 'integer');
    if ($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    if ($minutes < 10)
        $minutes = "0".$minutes;
    return sprintf($format, $hours, $minutes);
}

function mintotime($time) {
    settype($time, 'integer');
    if ($time < 1) {
        return;
    }
    $hours = str_pad(floor($time / 60), 2, '0', STR_PAD_LEFT);
    $minutes = str_pad(($time % 60), 2, '0', STR_PAD_LEFT);
    return $hours.$minutes."00";
}

function rand_hex() {
    return dechex(mt_rand(0, mt_getrandmax()));
}

function md_snackbar($msg) {
  ?>
  <div class="mdl-snackbar mdl-js-snackbar">
    <div class="mdl-snackbar__text"></div>
    <button type="button" class="mdl-snackbar__action"></button>
  </div>
  <script>
  window.addEventListener("load", function() {
    var notification = document.querySelector('.mdl-js-snackbar');
    notification.MaterialSnackbar.showSnackbar(
      {
        message: '<?=htmlspecialchars($msg)?>'
      }
    );
  });
  </script>
  <?php
}
?>
