<?php 
session_start();
require 'def.php';

$class = $_POST['clas'];
$paramsClass = json_decode($_POST['paramsClass'],true);
$method = $_POST['method'];
$params = json_decode($_POST["params"], true);


/******
// ANTISPAM
******/

if($method == "addMessage" || $method == "addComments" || $method == "newPost"){

if(isset($_SESSION["last_compare_time"])){
$_SESSION["last_compare_time"] = time();
$calc_debloque = $_SESSION["last_compare_time"] -  $_SESSION["init_time"];
				
				if($calc_debloque < 120){

					echo "spam";
					return;
				}
					

					else{


							unset($_SESSION["init_time"]);
							unset($_SESSION["spamvar"]);
							unset($_SESSION["second_counter"]);
							unset($_SESSION["first_compare_time"]);
							unset($_SESSION["last_compare_time"]);
					}

}

if(!isset($_SESSION["init_time"])){

	$_SESSION["init_time"] = time();
	$_SESSION["spamvar"] =  1;

}

if($_SESSION["spamvar"] > 0 && $_SESSION["spamvar"] < 12 ){

	$_SESSION["spamvar"]++;

}

if($_SESSION["spamvar"] > 11 ){

	

	$_SESSION["first_compare_time"] = time();

	$calc = $_SESSION["init_time"] - $_SESSION["first_compare_time"];
		
			if( $calc < 10){

			$_SESSION["last_compare_time"] = time();
			
				}

		}

}

require "class/class.".$class.".php";
$c = new $class($paramsClass);
$c->$method($params);


?>

