<?php
require dirname(__DIR__).'/def.php';
require INCROOT."/inc.connexion.php";
$pid = $_POST["postId"];

$counter = "SELECT * from comments WHERE posts_id = '".$pid."'  ";
$res = mysql_query($counter);

$rows = mysql_num_rows($res);

echo $rows

?>