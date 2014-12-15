<?php 
session_start();
require 'def.php';
require "inc.connexion.php";
$id = $_POST["id"];

$req = "SELECT * FROM utilisateurs WHERE id = '".$id."'  ";
$res = mysql_query($req);
$array = mysql_fetch_array($res);


	$_SESSION["id"] = $array["id"];
	$_SESSION["prenom"] = $array["prenom"];
	$_SESSION["nom"] = $array["nom"];
	$_SESSION["pseudo"] = $array["pseudo"];
	$_SESSION["mail"] = $array["email"]; 

 ?>