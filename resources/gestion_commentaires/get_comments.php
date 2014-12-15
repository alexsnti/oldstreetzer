<?php  
require dirname(__DIR__).'/def.php';
require INCROOT."/inc.connexion.php";
$pid = $_POST["postId"];
$req = select("SELECT comments.*,utilisateurs.* FROM comments, utilisateurs WHERE comments.posts_id = '".$pid."' AND comments.utilisateurs_id = utilisateurs.id  ORDER BY stamp ASC", true);



echo json_encode($req);
	?>