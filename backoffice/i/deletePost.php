<?php 
require 'def.php';
require "inc.connexion.php";

$idtodelete = $_POST["iduser"]; 

$delete = "DELETE FROM posts WHERE id = '".$idtodelete."'   ";

mysql_query($delete);





 ?>