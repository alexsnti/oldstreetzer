<?php 
require realpath(__DIR__).'/def.php';
require CLASSROOT."/class.connexion.php";

$ville = $_POST['ville'];

$req = select("SELECT DISTINCT villes_france.ville_nom_reel FROM villes_france WHERE ville_nom_reel LIKE '".$ville."%' GROUP BY villes_france.ville_nom_reel", true);


echo json_encode($req);


 ?>