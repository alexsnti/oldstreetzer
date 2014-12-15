<?php 
session_start();
require dirname(__DIR__).'/def.php';
require CLASSROOT."/class.messagerie.php";

$id = $_SESSION["id"];
$id_distant = $_POST["idDist"];
$contenu = mysql_real_escape_string($_POST['cnt']);
$type =  $_POST["type"];

$a = new Messagerie($id,$id_distant);

switch($type){

	case "ajout":
	$a->addMessage($contenu);
	break;
	case  "get":
	$a->getMessage();
	break;
	case "getLast":
	$a->getLastMessage();
	break;
	

}


 ?>