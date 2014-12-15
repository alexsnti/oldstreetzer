<?php

session_start();
require 'resources/class/lib/lessc.inc.php';
$less = new lessc;


if(!empty($_SESSION["id"])){

  header("Location: home");
  
}

$output = 'css/style.css';
$compile = $less->cachedCompile("css/style.less");
file_put_contents($output, $compile["compiled"]);

$last_updated = $compile["updated"];
$cache = $less->cachedCompile($compile);

if ($compile["updated"] > $last_updated) {
    file_put_contents($outputFile, $compile["compiled"]);
}

?>

<!DOCTYPE html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Streetzer | Inscription</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width">
     <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
      <![endif]-->

      <!-- CSS -->
      <link rel="stylesheet" href="css/reset.css">
      <link rel="stylesheet" href="css/responsive.css">
      <link rel="stylesheet" href="css/jui.css">
      <!-- LESS -->
      <link rel="stylesheet/less" type="text/css" href="css/style.less" />

      <!-- JS -->
      <script src="js/jquery.js"></script>
      <script src="js/script_home.js"></script>
      <script src="js/placeholder.js"></script>
      <script src="js/modernizr.js"></script>
      <script src="js/less.js"></script>
      <script src="js/juimin.js"></script>
      <script src="js/imagesloaded.js"></script>
      <script src="js/video.js"></script>
      <script src="js/bigvideo.js"></script>

      <!-- Typekit -->
      <script type="text/javascript" src="//use.typekit.net/urg7ofy.js"></script>
      <script type="text/javascript">try{Typekit.load();}catch(e){}</script>

    </head>
    <body>
      <script>

        $(function() {

          var BV = new $.BigVideo();
          BV.init();
          BV.show($('.wrapper .screen:nth-child(1)').attr('data-video'),{ambient:false});
          BV.getPlayer().on("ended", function () {

            $('body,html').animate({scrollTop:'1000%'},1000);

          });


        });


      </script>

      <div class="wrapper">

        <div class="screen" id="screen-1" data-video="css/video2.mp4" ></div>
      </div>
      <div class="mainContainer">
        <div class="mainBloc">

          <div class="containForm">
            <h2 class="logoHome">Inscription</h2>
      

            <form id="comeonin" style="margin: 7% 0 0 0">
             <div class="containerInput">       
             <input class="nom" name="nom" placeholder="Nom (requis)" type="text">
               <input  class="prenom" name="prenom" placeholder="Prénom (requis)"  type="text">
               <div class="clearfix"></div>

             </div>
             <div class="containerInput">
               <input class="email" name="email" placeholder="Email (requis)" type="text">
               <input class="pseudo" name="pseudo" autocomplete="off" placeholder="Pseudo (requis)" type="text">
               <div class="clearfix"></div>
             </div>
             <div class="containerInput">
              <input class="passinscr" autocomplete="off" name="pass" placeholder="Mot de passe ( 6 caractères mini )" type="password">
              <input class="passverif" autocomplete="off" name="passverif" placeholder="Confirmation" type="password">
            </div>
            <div class="clearfix"></div>
            <div class="containerInput">
             <div class="ui-widget"><input name="ville" placeholder="Entrez votre ville (requis)" class="ville" type="text"></div>
             <input class="naissance" name="naissance" placeholder="Date de naissance (dd/mm/aaaa)" type="text">
             <div class="clearfix"></div>
           </div>
           <div class="containerInput">

            <select class="typevehicule" name="typevehicule">
              <option value="">Choisissez votre type de véhicule (requis)</option>
              <option value="voiture">Voiture</option>
              <option value="moto">Moto</option>
            </select>
            <input class="marque" name="marque" placeholder="Entrez la marque de votre véhicule" type="text">
            <div class="clearfix"></div>
          </div>
          <div class="containerInput">
              <select name="sexe" id="">
                  <option value="male">Votre sexe...</option>
                  <option value="male">Homme</option>
                  <option value="female">Femme</option>


              </select>
           

          </div>
          <div class="containerInputSubmit">
            <input class="inscrireSubmit" type="submit" value="S'inscrire">

          </div>
           <div class="fbsignup"></div>
          <div style="position: absolute; bottom:0;left:0;" id="fb-root"></div>
          <div class="clearfix"></div>
        </div>
      </form>
    </div>
    <div class="header">
      <div class="contentHeader">
        <h1><img src="layout/img/logo.png"> </h1>

        <div id="display_connexion" href="#"> 

          <form method="POST" action="resources/login.php">

            <input class="lgin"style="width:100px" type="text" name="email" placeholder="Email">

            <input class="pass" style="width:100px" type="password" name="passLog" placeholder="Mot de passe">

            <input type="submit" id="log" value="connexion"/>
            <div class="logfb"></div>
            <div class="inscrire"><p>S'inscrire</p></p></div>
          </form>
        </div> </h1>

      </div>
    </div>

    <div class="welcome_signup">
      <div class="spacer"></div>
      <div  class="welcome_inscription">





        <div  class="welcome_connexion">
          <p class="decalageLeft">Connexion via Facebook</p>
          <form method="POST" action="resources/login.php">
            <p><label>Adresse mail :</label><input type="text" name="email"></p>
            <p><label>Mot de passe :</label><input type="password" name="pass"></p>
            <p><input type="submit"></p>
          </form>
          <a href="forget.php">Mot de passe oublié ?</a>
        </div>

      </div>
    </div>

  </body>

  <script src="http://connect.facebook.net/fr_FR/all.js"></script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-48472263-1', 'streetzer.com');
  ga('send', 'pageview');

</script>
  </html>

