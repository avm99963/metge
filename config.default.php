<?php
/**
 * Configuration
 *

    /////  //////  ////  //  //////  //  //////
   //     //  //  // // //  //          //
  //     //  //  //  ////  //////  //  //  //
 /////  //////  //   ///  //      //  //////

 */

// Welcome to the configuration file! In comments you have the explanation of what each variable does.
// Save this file as config.php so the application works

// Define the website name:
$appname = "Metges";

// Define the MySQL DataBase settings:
$host_db = ''; // DB Host (default: localhost)
$usuario_db = ''; // DB User
$clave_db = ''; // DB Password
$nombre_db = ''; // DB name

// If you want an alert at the beggining of each page, define it here (NO LONGER WORKS WITH THE MATERIAL DESIGN REDESIGN):
$anuncio = "";

$config = array();

$config['password']['characters'] = "10"; // Number of characters of the default password of users **(it has to be a string!!!!!)**
$config['sendemails'] = true; // Set to true if you want the app to send emails with the reservation details
$config['copyright'] = "&copy; ".date("Y")." Adrià Vilanova Martínez"; // Copyright footer
$config['debugenabled'] = false; // Leave this as false, otherwise the app will start to show some debugging info

// Below configure your STMP connection to send the mails:
$config['stmp'] = array();
$config['stmp']['stmpauth'] = true;
$config['stmp']['host'] = "";
$config['stmp']['port'] = 587;
$config['stmp']['username'] = "";
$config['stmp']['password'] = "";

// Configure the calendar event sent with the mail:
$config['calevent'] = array();
$config['calevent']['address'] = "Av. Pearson, 39-45, 08034, Barcelona, Spain";
$config['calevent']['uri'] = "https://avm99963.com";
$config['calevent']['description'] = "Visita al médico de la escuela.";

// Duplicate the following array and change the key from 0 to the index key if you want to create more visits:
$config["visits"][0]["name"] = "Analítica"; // Name
$config["visits"][0]["codename"] = "analitica"; // Codename for the development of the app
$config["visits"][0]["date"] = strtotime("2014-06-02"); // Date of visits
$config["visits"][0]["number"] = 1; // Number of visits per hour
$config["visits"][0]["firsttime"] = 8*60+15; // First visit in minutes
$config["visits"][0]["interval"] = "4"; // Interval in minutes
$config["visits"][0]["lasttime"] = 11*60+20; // Last visit in minutes (if it doesn't match a multiple of first visit multiplied by interval, it will not be counted)
