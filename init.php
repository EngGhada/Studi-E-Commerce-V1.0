<?php
//Error reporting
ini_set('display_errors','on');
error_reporting(E_ALL);

include "admin/connect.php";

$sessionUser='';
$sessionUserID ='';


if(isset($_SESSION['User']))
{
    $sessionUser   = $_SESSION['User'];
    $sessionUserID = $_SESSION['uid'];
  
}

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
?>
<div class="global-body">


 