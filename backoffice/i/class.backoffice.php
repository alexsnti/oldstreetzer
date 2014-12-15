<?php 
 session_start();
require "def.php";
require "inc.connexion.php";

Class Backoffice{

private $adminId;


function __construct(){




}
function login($login,$pass){



	$req = "SELECT * FROM admin WHERE pseudo = '".$login."' AND pass = '".$pass."' ";
	$res = mysql_query($req) or die(mysql_error());
	$tab = mysql_fetch_array($res);
	$rows = mysql_num_rows($res);
	var_dump($rows);
	
	if($rows === 1){

		$_SESSION["admin_id"] = $tab["id"];
		$_SESSION["admin_login"] = $tab["pseudo"];
		$_SESSION["rank"] = $tab["rank"];

	header("Location: insidethematrice.php");

	}

}
function showAllPost(){
	
	$req = select_profil("SELECT posts.id, posts.contenu,posts.datepost, posts.public,posts.img_id, utilisateurs.nom, utilisateurs.prenom,utilisateurs.description,utilisateurs.id,utilisateurs.pseudo, images.root FROM posts, utilisateurs, images  WHERE posts.utilisateurs_id = utilisateurs.id AND posts.img_id = images.posts_id  ORDER BY stamp DESC", true );
		

		echo json_encode($req);

}
/*
function logout($f){


}
*/
/*
function showAllAccount(){

	$req = select_profil("SELECT  utilisateurs.nom, utilisateurs.prenom,utilisateurs.description,utilisateurs.id,utilisateurs.pseudo FROM  utilisateurs ", true );
		

		echo json_encode($req);
	

}
*/

/*
function showAccount($f){

	$req = select("SELECT * FROM utilsateurs WHERE id = '".$id."' ",true);
	json_encode($req);

}
function showPost($f){

	$req = select("SELECT * FROM posts WHERE id = '".$id."' ",true);
	json_encode($req);

}
function supprAccount($f){

	$req = "DELETE FROM utilsateurs WHERE id = '".$id."' ";

}
function supprPost($f){

	$req = "DELETE FROM posts WHERE id = '".$id."' ";

}
function countUser($f){


}
function countPost(){


}
*/
}


 ?>