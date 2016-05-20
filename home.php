<?php
require_once("core.php");
?>
<!DOCTYPE html>
<html>
<head>
  <title><?php echo $appname; ?></title>
  <meta charset="UTF-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Chrome for Android theme color -->
  <!--<meta name="theme-color" content="#2E3AA1">-->

  <link rel="stylesheet" href="bower_components/material-design-lite/material.min.css">
  <script src="bower_components/material-design-lite/material.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

  <link rel="stylesheet" href="css/index.css">

  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
  <script src="js/jquery.autocomplete.min.js"></script>

  <script>
  $(document).ready(function() {
  	var options, a;
  	jQuery(function(){
  	   options = { serviceUrl:'ajax/users.php' };
  	   a = $('#id').autocomplete(options);
  	});
  });
  </script>
</head>
<body>

</body>
</html>
