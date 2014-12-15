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
                     
                        <form method="POST" action="resources/login.php">
                        
                        <p><input placeholder="Adresse mail..." type="text" name="email"></p>
                        <p><input type="password" placeholder="Mot de passe..." name="pass"></p>
                        <p><input type="submit"></p>
                    </form>
                     <a class="forget" href="forget.php">Mot de passe oublié ?</a> 

                    </div>

                    
            </div>
    </div>
    </div>
</div>

    </body>
</html>
