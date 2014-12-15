<?php 
session_start();
require dirname(__DIR__).'/def.php';
require CLASSROOT."/class.connexion.php";
$id = $_SESSION["id"];
$now = time();

$reqExist = "SELECT * FROM online WHERE utilisateurs_id =$id";
$resExist = mysql_query($reqExist)or die(mysql_error());
$rowExist = mysql_num_rows($resExist);
if($rowExist != 0){

$reqPing2 = "UPDATE online SET  lastlonline=$now WHERE utilisateurs_id=".$id."";
$resPing2 = mysql_query($reqPing2) or die(mysql_error());
}

else{

$reqPing = "INSERT INTO online(utilisateurs_id,lastlonline) VALUES ($id,$now)";
$resPing = mysql_query($reqPing) or die(mysql_error());
}


?>