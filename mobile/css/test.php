<?php

include 'http://www.levillage.org/inc/dns.inc.php';
$file = file_get_contents("http://hebergement.levillage.org/login.cbb");
echo htmlentities($file);
?>