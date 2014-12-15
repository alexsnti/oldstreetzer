<?php 
session_start();
header('Content-Type: text/html; charset=utf-8');
require INCROOT."/inc.connexion.php";

class Wall{
	private $id;
	private $postId;
	private $userId;
	private $exist = "exist";
	private $date;

		function __construct($arg){
			$this->id = $_SESSION['id'];
			$this->postId = $arg[0];
			$this->userId = $arg[1];
			$jour = date('d/m');
			$heure = date('H:i:s');
			$this->date = mysql_real_escape_string($jour.' - '.$heure);
			
		}
		
		function filterLike($argMethod){

			$type = $argMethod[0];
			$idu = $argMethod[1];

			$check = "SELECT * from mur_photos WHERE utilisateurs_id_mur = '".$this->userId."' AND posts_id = '".$this->postId."'  ";
			
			$resCheck = mysql_query($check);
			
			if(mysql_num_rows($resCheck) !=0){

				echo "pinned";

				
			}
			
			switch($type){

					case "add":
					$this->addLike($idu);
					return;
					
					case "delete":
					$this->deleteLike();

					break;
			}
			
		}

		function addLike($idu){
			$stamp = time();
		
			$req = "INSERT INTO mur_photos(utilisateurs_id_mur,posts_id) VALUES ('".$this->userId."','".$this->postId."')";
			
			$reqNotif = "INSERT INTO notifications  VALUES ('','newLike','".$this->date."','".$this->postId."','".$idu."','".$this->id."','0')";

			mysql_query($req);
			mysql_query($reqNotif); 
			
			echo "unpinned";
			return;

		}

		function deleteLike(){

			
			$suppr = "DELETE FROM mur_photos WHERE utilisateurs_id_mur = '".$this->userId."' AND posts_id = '".$this->postId."' ";
			$res = mysql_query($suppr) or die(mysql_error());

			if($res){

				echo "ok";
				return;



			}
		}

}

	?>



