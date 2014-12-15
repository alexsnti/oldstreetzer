<?php 
require realpath(__DIR__).'/def.php';
require CLASSROOT."/class.connexion.php";

$marque = $_POST['marque'];

$req = select("SELECT marques_auto.marque FROM marques_auto WHERE marque LIKE '".$marque."%' ", true);

echo json_encode($req);


 ?>