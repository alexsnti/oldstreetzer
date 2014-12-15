<?php
session_start();
require realpath(__DIR__).'/def.php';
require CLASSROOT."/class.utilisateur.php";
$regexMail = '`^[a-zA-Z0-9_%-]+@[a-zA-Z0-9_%-]+\.[a-zA-Z]{2,6}$`';

$regexNaissance =  '^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$';
//$id = "1";

$nom = $_POST["nom"];
$prenom = $_POST["prenom"];
$mail = $_POST["email"];
$pseudo = $_POST["pseudo"];
$pass = sha1($_POST["pass"]);
$passverif = sha1($_POST["passverif"]);

$naissance = $_POST["naissance"]:
$vehicule = $_POST["typevehicule"];
$ville = $_POST["ville"];
$sexe = $_POST["sexe"];
$marque = $_POST["marque"];

$error = array();


$newUser = new Utilisateur($nom,$prenom,$mail,$pseudo,$pass);
$newUser->NewUtilisateur($vehicule,$ville,$sexe,$marque);
/*if(!preg_match($regexMail, $mail)){

	array_push($error, "wrongmail", "Le mail est non conforme");

}*/
/*if(!preg_match($regexNaissance, $naissance)){

	array_push($error, "wrongyear", "Mauvaise année de naissance");

}

if($pass != $passverif /*|| strlen($pass) > 8*/){
/*
	array_push($error,"wrongpass",  "mauvaise confirmation / mot de passe trop court ( 8 caractères minimum )");
	echo count($error);
	var_dump($error);

}

if(empty($nom) || empty($prenom) || empty($mail) || empty($pseudo) || empty($pass)){

	array_push($error,"champs",  "Il manque un élement");

}

if(count($error) != 0){

	$_SESSION["erreur"] = $error;
	header("Location: ../index.php ");

}
else{


$newUser = new Utilisateur($nom,$prenom,$mail,$pseudo,$pass);
$newUser->NewUtilisateur($vehicule,$ville,$sexe,$marque);

}

	*/


?>