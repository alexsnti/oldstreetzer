	<?php 
	session_start();

if(empty($_SESSION["first_compare_time"])){

		
		$_SESSION["init_time"] = time();
	}

$_SESSION["first_compare_time"] = time();
$soustrac = $_SESSION["first_compare_time"] - $_SESSION["init_time"];
if($soustrac < 30){

	echo "spam";
}





		 ?>
