<?php 
session_start();
header('Content-Type: text/html; charset=utf-8');
require INCROOT."/inc.connexion.php";	

class Commentaires{

	private $date;
	private $time;
	private $id;


	function __construct(){

		$this->id = $_SESSION['id'];
		$this->time = time();
		$jour = date('d/m');
		$heure = date('H:i:s');
		$this->date = mysql_real_escape_string($jour.' - '.$heure);

	}

	function addComments($f){
		var_dump($f[2]);
		$securedContenu = addslashes($f[0]);
		$pid = $f[1];
		$idu = $f[2];
		$date = time();
		mysql_query("SET NAMES utf8" );
		
		$req = "INSERT INTO comments(posts_id,utilisateurs_id,contenu,datepost,stamp) VALUES ('".$pid."','".$this->id."','".$securedContenu."','".$date."','".$time."')";

		//	if($this->id != $idu){

			$reqNotif = "INSERT INTO notifications  VALUES ('','newComm','".$this->date."','".$pid."','".$idu."','".$this->id."','0')";
		//}
		
		mysql_query($req);

		mysql_query($reqNotif)or die(mysql_error());	
		


	}

	function getComments($f){

		$pid = $f[0];
		
		$req = select("SELECT comments.*,utilisateurs.id,utilisateurs.prenom, utilisateurs.nom,utilisateurs.pseudo FROM comments, utilisateurs WHERE comments.posts_id = '".$pid."' AND comments.utilisateurs_id = utilisateurs.id  ORDER BY comments.id ASC", true);


		echo json_encode($req);

	}





}


 ?>