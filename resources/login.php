<?php
require realpath(__DIR__).'/def.php';
require CLASSROOT."/class.connexion.php";

$mail = $_POST["email"];
$pass = sha1($_POST["passLog"]);

$newUser = new Connexion($mail,$pass);
$newUser->NewConnexion();


?>