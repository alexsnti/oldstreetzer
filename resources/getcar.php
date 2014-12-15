<?php 

require realpath(__DIR__).'/def.php';
require CLASSROOT."/class.connexion.php";
$id = $_SESSION["id"];
$req = select("SELECT * FROM car WHERE utilisateurs_id = '".$id."' ",true);
echo json_encode($req);



 ?>