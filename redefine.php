<?php
session_start();

require 'resources/def.php';
require CLASSROOT."/class.connexion.php";
$token = $_GET["token"];
$mail = $_GET["mail"];	

$reqExist = "SELECT * FROM passreset WHERE mail='".$mail."' AND token='".$token."'";
$resExist = mysql_query($reqExist)or die (mysql_error());
$rowExist = mysql_num_rows($resExist);

if($rowExist != 1){

	// erreur
}
else{

include "layout/layout_php/header.php";
$_SESSION["mailchange"] = $mail;
?>

<div class="mainContainer">
    <div class="header">
        <div class="contentHeader">
            <h1>Streetzer <a id="display_connexion" href="#">Se connecter</a> </h1>

        </div>
    </div>

    <div class="welcome_signup">
        <div class="spacer"></div>

   

            <div  class="welcome_connexion log_fallback">
                <p class="decalageLeft">Connexion</p>
                    <form method="POST" action="resources/resetpass.php">
                        <p><label>Veuillez entrer un nouveau mot de passe</label><input type="password" name="newpass"></p>
                      
                        <p><input type="submit"></p>
                    </form>
         
            </div>

    </div>
</div>

    </body>
</html>



<?php
}

?>
