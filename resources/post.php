<?php


require realpath(__DIR__).'/def.php';
require CLASSROOT."/class.post.php";
session_start();
$id = $_SESSION["id"];
$contenu = $_POST["contenu"];
$jour =  date('d/m/Y'); 
$heure = date('H:i:s');
$date = ''.$jour.'à '.$heure.'';
$post = new Post($id,$contenu,$date,"");
$post->newPost();

?>