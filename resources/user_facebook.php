<?php 
session_start();

require realpath(__DIR__).'/def.php';
require CLASSROOT."/class.connexion.php";
require CLASSROOT."/lib/class.getimage.php";
$tab = $_POST["tab"];


$fbid = $tab[8];
$imgurl = $tab[9];
$email = $tab[5];

$mailcheck = "SELECT utilisateurs.fb_id,utilisateurs.id,utilisateurs.prenom,utilisateurs.nom,utilisateurs.pseudo,utilisateurs.email FROM utilisateurs WHERE utilisateurs.email = '".$email."' AND utilisateurs.fb_id != ".$fbid."  ";

$mailres = mysql_query($mailcheck);
$mailrows = mysql_num_rows($mailres);

if($mailrows != 0){

	echo "a";
	return;
}


$req = "SELECT utilisateurs.fb_id,utilisateurs.id,utilisateurs.prenom,utilisateurs.nom,utilisateurs.pseudo,utilisateurs.email FROM utilisateurs WHERE fb_id = '".$fbid."' ";

$res = mysql_query($req);
$rows = mysql_num_rows($res);
$array = mysql_fetch_array($res);


if($rows != 0){

	echo "e";
	$_SESSION["id"] = $array["id"];
	$_SESSION["prenom"] = $array["prenom"];
	$_SESSION["nom"] = $array["nom"];
	$_SESSION["pseudo"] = $array["pseudo"];
	$_SESSION["mail"] = $array["email"];
	
	return;
}
else{


$prenom = $tab[0];
$nom = $tab[1];
$pseudo = $tab[2];
$description = $tab[3];
$naissanceform = $tab[4];
$explodenaissance = explode("/", $naissanceform);
$naissance = $explodenaissance[1].'/'.$explodenaissance[0].'/'.$explodenaissance[2];
$email = $tab[5];
$sexe  = $tab[6];
$ville = explode(",", $tab[7]);
$villeEx = $ville[0];



$reqInsert = "INSERT INTO utilisateurs(nom, prenom,email,pseudo,ville,description,sexe,naissance,fb_id) VALUES ('".$nom."','".$prenom."','".$email."','".$pseudo."','".$villeEx."','".$description."','".$sexe."','".$naissance."','".$fbid."') ";
 $resInsert = mysql_query($reqInsert)or die(mysql_error());
$id = mysql_insert_id();
$cryptid = sha1($id);
$old = umask(0);     
mkdir("../images/".$cryptid."",0777);
mkdir("../images/".$cryptid."/profil_pic",0777);
mkdir("../images/".$cryptid."/post_pic",0777);
umask($old);


$image = new GetImage;
$image->source = $imgurl;
$image->save_to = "../images/".$cryptid."/profil_pic/";
$get = $image->download('gd','pp'); 

    
}



 ?>