<?php
include "layout/layout_php/header.php";

$error = $_GET["error"];
?>

<div class="mainContainer">
    <div class="header">
        <div class="contentHeader">
        <h1><img src="layout/img/logo.png"> </h1>

        <div id="display_connexion" href="#"> 

          <form method="POST" action="resources/login.php">

            <input class="lgin"style="width:100px" type="text" name="email" placeholder="Email">

            <input class="pass" style="width:100px" type="password" name="pass" placeholder="Mot de passe">

            <input type="submit" id="log" value="connexion"/>
            <div class="logfb"></div>

          </form>
        </div> </h1>

      </div>
    </div>

    <div class="welcome_signup">
        <div class="spacer"></div>

   
            <div style="width:300px;height: 300px;" class="mainBloc">
            <div  class="welcome_connexion log_fallback">
                <div class="<?php echo $error?>"></div>
                    <div class="fallback">
                     
                       <form method="POST" action="resources/traitement_forget.php">
                        <p><label style="position: relative;top:-30px;font-size: bold;  ">Veuillez entrer une adresse mail valide</label><input style="border: 1px solid;" type="text" name="email"></p>
                      
                        <p><input type="submit"></p>
                    </form>
                     

                    </div>

                    
            </div>
    </div>
    </div>
</div>

    </body>
</html>

