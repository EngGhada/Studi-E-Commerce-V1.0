<?php

include "connect.php";

// Routes

$tpl  ='includes/templates/'; // template directory
$css  ='layout/css/'; //css directory
$func='includes/functions/'; //functions directory
$js   ='layout/js/'; // javascript directory
//$lang ='includes/languages/';//languages directory

// Include the important files.
include $func."functions.php";
//include $lang."english.php";
include $tpl."header.php";


  // include navbar to all pages except the one has $noNavbar

  if(!isset($noNavbar)) { include $tpl."navbar.php"; }
?>
  <div class="global-body">