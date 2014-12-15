<?php
session_start();
require '../resources/class/lib/lessc.inc.php';
$less = new lessc;

if(empty($_SESSION["id"])){

  header("Location: ../");

}

$output = '../css/style.css';
$compile = $less->cachedCompile("../css/style.less");
file_put_contents($output, $compile["compiled"]);

$last_updated = $compile["updated"];
$cache = $less->cachedCompile($compile);
if ($compile["updated"] > $last_updated) {
  file_put_contents($outputFile, $compile["compiled"]);
}


?>
<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Streetzer | Home</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
      <![endif]-->

      <!-- CSS -->
      <link rel="stylesheet" href="../css/reset.css">
      <link rel="stylesheet" href="../css/jui.css">
      <link rel="stylesheet" href="../css/style.css">
        <!-- LESS 
        <link rel="stylesheet/less" type="text/css" href="../css/style.less" />
      -->
      <!-- JS -->
      <script src="http://connect.facebook.net/en_US/all.js"></script>
      <script src="../js/jquery.js"></script>       
      <script src="../js/juimin.js"></script>
      
      <script src="../js/placeholder.js"></script>
      <script src="../js/mousewheel2.js"></script>
      <script src="../js/crossDom.js"></script>
      <script src="../js/script.js"></script>
      
      

      <!-- Typekit -->
      <script type="text/javascript" src="//use.typekit.net/urg7ofy.js"></script>
      <script type="text/javascript">try{Typekit.load();}catch(e){}</script>

      
    </head>
    <body>
      <div class="loader"></div>

      <div class="sidebar">
        <div class="more"><div class="layoutChoice">
    <div class="layoutPetit"></div>
    <div class="layoutMoyen"></div>
    <div class="layoutGrand"></div>
      </div><div class="arrow-left"></div></div>
    <div class="topBar"><div class="notifs"></div><div class="topLogo"></div><div class="gearSettings">
    </div></div>
        <div class="welcomeUser">
          <div class="tooltipNotifs"></div>
          
         <!-- <div class="notifsMessages"></div>-->
          <div class="overlayPp"></div>
          <div style="background: url(../images/<?php echo sha1($_SESSION["id"]) ?>/profil_pic/pp.jpg)center center; background-size:cover;" class="ppBg"></div>
          <div class="tableau_bord"><a href="#">Mon tableau de bord</a></div>
          <div data-id='<?php echo $_SESSION['id']; ?>' class="nameSurname"><p class="pseudo"><?php echo "".$_SESSION["pseudo"]."" ?></p><p class="realname"><?php echo "".$_SESSION["prenom"]." ".$_SESSION["nom"]."";?></p></div>
          
        </div>

        <div class="blocMessage">
            <div class="preChoice"></div>
          <div class="theChoice">
            <div class="picture"><p class="centrage">AJOUTER <br/>UNE PHOTO</p></div>
            <div class="ytb"><p class="centrage">AJOUTER UNE VIDEO YOUTUBE</p></div>
          </div>

          <div class="enterLink">
            <div class="supprAddedPic"></div>
            <input class="yt"  style="display: block;" type="text" placeholder="Entrez votre lien Youtube"/>
          </div>
          <div class="form">
            <output style='position: absolute; margin-left: 10px;' id='filesInfo'></output>
            <div id="jti"  style='width:157px; height:210px;margin: 0 0 0 9px; overflow:hidden; background-repeat: no-repeat;'>
              <div class="containSpinner"><div class="spinner"></div></div>
              <input type='file' id='imgInp' accept='image/*' name='postImg' style='font-size:600px; opacity:0; -moz-opacity:0; filter:alpha(opacity=0); float:right; cursor:pointer' />
              <output id='filesInfo'></output>
              <iframe id='ifr1' name='send' style='width:1px; height:1px;'></iframe>

            </div>
            <input class="ctnForm" type="text" placeholder="Entrez votre message..." name="contenu"></input>	

            <img id="render" src="../layout/img/pisdead.png" alt="your image" />
            <div class="supprAddedPic"></div>
            
            <div class="clearfix"></div>
          </div>

        </div>
        <script>
        <?php echo ' msg = '.json_encode($_SESSION['id']).';'; ?>
        doctype = sessionStorage.setItem('globalArrayValue',<?php echo json_encode($_SESSION['id']) ?>);

        </script>
        <div class="menu">
          <ul style="background: #EEEBEB;" class="sidebar menuside">
            <li class="laneWorld whiteli"><p>WORLDLANE</p></li>
            <li class="laneFriends"><p>FRIENDSLANE</p></li>
            <li class="photoWall whiteli"><p>LIKES</p></li>
            <li class="listFriends"><p>FRIENDS</p></li>
            <li class="showMessages whiteli"><p>MESSAGES</p></li>        
          </ul>
        </div> 
        <div class="logOut"><a class="lgtxt" href="#">LOGOUT</a></div>
      </div>



      <div class="front_scroller">
        <div class="popContent">
          <input class="youtubelink" type="text"/>
          <div style="display:none;" class="disabledBody"></div>
          <button class="youtubego">Envoyer</button>
        </div>
        <div class="loaderWall"><div class="spinnerWall"><img src="../layout/img/spinner.gif" alt=""></div></div>
        <ul class='posts'>


         

        </ul>

      </div>
      <div class="clearfix"></div>

<!--
<div class="masterContainerConvers">
<div class="listAmisChat">
	<h3 class="hChat"><a href="">Liste des amis en ligne</a></h3>
	<ul class="listeInsideOnline">
		
	</ul>
</div>
-->


</div>

</body>
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-48472263-1', 'streetzer.com');
ga('send', 'pageview');

</script>
</html>