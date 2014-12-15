<?php
session_start();
require dirname(__DIR__).'/def.php';
require CLASSROOT."/class.connexion.php";
$id = $_GET["id"];
$reqCompare = "SELECT * FROM online WHERE utilisateurs_id ='".$id."'";
$resCompare = mysql_query($reqCompare) or die(mysql_error());
while($var = mysql_fetch_array($resCompare,MYSQL_ASSOC)){

	$tocompare = $var["lastlonline"];
	$elapsed = (time() - $tocompare);
	if($elapsed > 10){

		echo "cacahuete";
	}
}


?>