<?php
session_start();
require realpath(__DIR__).'/def.php';
require INCROOT."/inc.connexion.php";
$req = "SELECT * FROM utilisateurs WHERE id ='".$_SESSION["id"]."'";
$res = mysql_query($req) or die(mysql_error());
  

?>