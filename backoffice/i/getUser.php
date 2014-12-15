<?php 
require 'def.php';
require "inc.connexion.php";

$id = $_POST["id"];

$req = select_profil("SELECT * FROM utilisateurs WHERE id = '".$id."'  ", true);
    echo json_encode($req);


 ?>