<?php
require INCROOT."/inc.connexion.php";
session_start();
class Connexion{

	private $identifiant;
	private $motdepasse;

	function __construct($_identifiant, $_motdepasse){

	$this->identifiant = $_identifiant;
	$this->motdepasse = $_motdepasse;

	}

	function NewConnexion(){

			//Je recherche les données de compte dans la base par rapport aux données entrées en POST
			$req = "SELECT * FROM utilisateurs WHERE email='".$this->identifiant."' && motdepasse='".$this->motdepasse."'";
			$res = mysql_query($req) or die(mysql_error());

			//Je compte les lignes en sortie de requete
			$row = mysql_num_rows($res);
			$array = mysql_fetch_array($res);
			var_dump($row);

			//Si il y en a qu'une, je connecte l'utilisateur
			if($row == 1){

			$_SESSION["id"] = $array["id"];
			$_SESSION["prenom"] = $array["prenom"];
			$_SESSION["nom"] = $array["nom"];
			$_SESSION["pseudo"] = $array["pseudo"];
			$_SESSION["mail"] = $array["email"];

			header("Location: ../home");

			}
	//Sinon je renvoi un message d'erreur
		else{

				header("Location: ../login.php?error=wronglog");

			}

	}

	function Deconnexion(){
			//Je desactive les sessions
			unset($_SESSION[""]);

			//Je les détruits
			session_destroy();

	}

}


?>