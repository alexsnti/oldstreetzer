<?php
class dbConnect{

private $host;
private $login;
private $pass;
private $db;

function __construct($_host,$_login,$_pass,$_db){

$this->host = $_host;
$this->login = $_login;
$this->pass = $_pass;
$this->db = $_db;

}

function connect(){

$connexion = mysql_connect($this->host,$this->login,$this->pass);
$selectDb = mysql_select_db($this->db,$connexion) or die(mysql_error());

if($selectDb){

	//echo "Connexion ok";
}
else{

	echo "Fail";
}

}	

}

?>