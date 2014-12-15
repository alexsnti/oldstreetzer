<?php
require realpath(__DIR__).'/def.php';
require CLASSROOT."/class.connexion.php";

$mail = $_POST["email"];

$reqCheck = "SELECT * FROM utilisateurs WHERE email = '".$mail."'";
$resCheck = mysql_query($reqCheck) or die(mysql_error());
$rowsCheck =  mysql_num_rows($resCheck);
var_dump($rowsCheck);

if($rowsCheck != 1){
header("Location: ../index.php?error=nomail");
}
else{

	$token = getToken();
	$reqToken = "INSERT INTO passreset(token,mail) VALUES ('".$token."','".$mail."')";
	$resToken = mysql_query($reqToken) or die (mysql_error());
	if($resToken){
	$message_txt = "Vous avez oublié votre mot de passe, veuillez copier ce lien pour le redéfinir : http://sd-46194.dedibox.fr/~streetzer/redefine.php?token=".$token."&mail=".$mail."";
	$message_html = "Vous avez oublié votre mot de passe, veuillez copier ce lien pour le redéfinir : <a href='http://sd-46194.dedibox.fr/~streetzer/redefine.php?token=".$token."&mail=".$mail."'>Cliquez ici</a>";
	$sujet = "Redefinition de mot de passe";
	$fromMail = "noreply@streetzer.com";
	$replyMail = "noreply@streetzer.com";

	sendMail($mail, $message_txt,$message_html,$sujet,$fromMail,$replyMail);

	}

    // Déclaration de l'adresse de destination.

	// Je lance le token et j'écris le mail 
	

	//header("Location: ../forget.php?mailSend=ok");
}
?>