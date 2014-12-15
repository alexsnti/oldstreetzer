<?php
require realpath(__DIR__).'/def.php';
require CLASSROOT."/class.connexion.php";
$newpass = sha1($_POST["newpass"]);

$reqUpdatePass = "UPDATE utilisateurs SET motdepasse ='".$newpass."' WHERE email ='".$_SESSION["mailchange"]."' ";
$resUpdatePass = mysql_query($reqUpdatePass)or die(mysql_error());

if($resUpdatePass){
$reqRemoveEntry = "DELETE FROM passreset WHERE mail ='".$_SESSION["mailchange"]."' ";
$resRemoveEntry = mysql_query($reqRemoveEntry) or die(mysql_error());
$purge = new Connexion();
$purge->Deconnexion();
}




?>

