<?php 

require realpath(__DIR__).'/def.php';
require CLASSROOT."/class.connexion.php";

$id = $_SESSION['id'];
$texture = $_POST["texture"];
$spoiler = $_POST["spoiler"];

$req = "SELECT * FROM car WHERE utilisateurs_id = '".$id."' ";
$res = mysql_query($req);
$rows = mysql_num_rows($res);

if($rows != 0){

	$update = "UPDATE car SET texture = '".$texture."', spoiler = '".$spoiler."' WHERE utilisateurs_id = '".$id."' ";
	$resupdate = mysql_query($update);
}
else{

	$insertion = "INSERT INTO car VALUES ('','".$texture."','".$spoiler."','".$id."') ";
	$resinsertion = mysql_query($insertion);
}


 ?>