<?php
require_once __DIR__.'/mysql_shim.php';

$link = mysql_connect('127.0.0.1', 'streetzer', 'streetzer');
if (!$link) {
	die('Connexion MySQL impossible');
}
mysql_select_db('streetzer') or die(mysql_error());
mysql_query("SET NAMES utf8");
mysql_query("SET sql_mode=''");
