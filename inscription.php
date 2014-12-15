<?php
session_start();
// Je recupère les trois variables du pré_formulaire
if(empty($_SESSION["nom"]) || empty($_SESSION["prenom"]) || empty($_SESSION["mail"]) ){

$_SESSION["nom"] = $_POST["nom"];
$_SESSION["prenom"] = $_POST["prenom"];
$_SESSION["mail"] = $_POST["mail"];

}

include 'layout/layout_php/header.php';
$error = $_SESSION["erreur"];
var_dump($error);
//var_dump($_SESSION["erreur"]);

?>

<div class="mainContainer">
	
	<div class="header">
		<div class="contentHeader">
			<h1>Streetzer</h1>
	</div>

	</div>
<div class="welcome_signup">
	<div class="spacer"></div>
	<form method="post" action="resources/envoi_inscription.php"  class="inscription_next">
	<p>Nous y sommes presque !</p>
	<p><label>Nom :</label><input name="nom" type="text" value="<?php echo $_SESSION["nom"]; ?>"></p>
	<p><label>Prénom 1:</label><input name="prenom" type="text" value="<?php echo $_SESSION["prenom"]; ?>"></p>
	<p><label>E-mail :</label><input name="email" type="text" value="<?php echo $_SESSION["mail"]; ?>"></p>
	<p><label>Pseudo :</label><input name="pseudo" type="text" ></p>
	<p><label>Mot de passe :</label><input name="pass" type="password" ></p>
	<p><label>Confirmer le mot de passe :</label><input type="password" name="passverif"></p>
	<input type="submit" value="Inscription">
	</form>

</div>

</div>



<?php
include 'layout/layout_php/footer.php';
 ?>