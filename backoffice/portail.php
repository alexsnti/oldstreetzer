<?php 


require "i/class.backoffice.php";


$login = $_POST['login'];
$pass = $_POST['pass'];


$c = new Backoffice();
$c->login($login,$pass);

 ?>