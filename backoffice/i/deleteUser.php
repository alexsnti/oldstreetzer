<?php 
require 'def.php';
require "inc.connexion.php";

$idtodelete = $_POST["iduser"]; 

$delete = "DELETE FROM utilisateurs WHERE id = '".$idtodelete."'   ";

mysql_query($delete);





 ?>