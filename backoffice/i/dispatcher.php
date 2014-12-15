<?php 
require 'def.php';
require "inc.connexion.php";
$req = select_profil("SELECT posts.id, posts.contenu,posts.datepost, posts.public,posts.img_id, utilisateurs.nom, utilisateurs.prenom,utilisateurs.description,utilisateurs.id,utilisateurs.pseudo, images.root FROM posts, utilisateurs, images  WHERE posts.utilisateurs_id = utilisateurs.id AND posts.img_id = images.posts_id  ORDER BY stamp DESC", true );
		

		echo json_encode($req);


?>

