<?php
session_start();
require dirname(__DIR__).'/def.php';
require INCROOT."/inc.connexion.php";

$uid = $_SESSION["id"];
$pid = $_POST["postId"];

$contenu = mysql_real_escape_string($_POST["contenu"]);
$securedContenu = htmlentities($contenu);

$time = time();

$jour = date('Y/m/d ');
$heure = date('H:i:s');
$date = $jour.'à '.$heure;
mysql_query("SET NAMES utf8" );
$req = "INSERT INTO comments(posts_id,utilisateurs_id,contenu,datepost,stamp) VALUES ('".$pid."','".$uid."','".$securedContenu."','".$date."','".$time."')";
$res = mysql_query($req) or die(mysql_error());

?>             
 