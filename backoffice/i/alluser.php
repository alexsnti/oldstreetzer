<?php 
require 'def.php';
require "inc.connexion.php";
$req = select_profil("SELECT utilisateurs.nom, utilisateurs.prenom,utilisateurs.description,utilisateurs.id,utilisateurs.pseudo FROM utilisateurs  ORDER BY id DESC LIMIT 100", true );
		

		echo json_encode($req);


?>

