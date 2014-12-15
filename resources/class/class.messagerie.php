<?php
session_start();

require INCROOT."/inc.connexion.php";

Class Messagerie{
	
	private $id;
	private $idDistant;
	private $stamp;

	function __construct($constructarr){

		$this->id = $_SESSION['id'];
		$this->idDistant = $constructarr[1];
		$this->stamp = microtime(true);
		$heure = date('H:i:s');
		$this->date = $heure;

	}

	function getId($f){

		$explode = explode(" ", $f[0]);
		$prenom =  $explode[0];
		$nom =  $explode[1];
		$req = select("SELECT utilisateurs.id FROM utilisateurs WHERE utilisateurs.prenom = '".$prenom."' AND utilisateurs.nom = '".$nom."'");

		echo json_encode($req);

	}
	

	function supprConvers($f){

		$idDistant = $f[0];
		$idC = $f[1];

		// Je verifie si l'utilisateur à déja mis la conversation en invisible

		$r1 = "SELECT * FROM conversation WHERE link = '".$idC."' ";
		$s1 = mysql_query($r1);
		$nm1 = mysql_num_rows($s1);
		
		
		// Si non, je la rend invisible uniquement pour lui
		
		if($nm1 == 2){

			$makeInvisible =  "DELETE FROM conversation WHERE utilisateurs_id_current = ('".$this->id."') AND utilisateurs_id_distant = ('".$idDistant."') ";
			mysql_query($makeInvisible);
		}
		//Si oui, je verifie si l'autre utilisateur à fait la même pour supprimer la conversation entièrement
		else if ($nm1 == 1) {

			$req = "DELETE FROM chat_messages WHERE id_conversations = '".$idC."' ";

			$req0 = "DELETE FROM conversation WHERE utilisateurs_id_current = ('".$this->id."') AND utilisateurs_id_distant = ('".$idDistant."')  ";
			
			echo $req0;
			mysql_query($req);
			mysql_query($req0);
			


		}

	}	
	function getConvers(){

		$req = select("SELECT DISTINCT conversation.*,utilisateurs.id, utilisateurs.nom,utilisateurs.prenom,utilisateurs.pseudo FROM conversation, utilisateurs WHERE conversation.utilisateurs_id_current = '".$this->id."' AND conversation.utilisateurs_id_distant = utilisateurs.id GROUP BY utilisateurs_id_distant ",true);

		echo json_encode($req);	

	}
	
	
	
	function getNotif(){


		$req = "SELECT * FROM conversation WHERE utilisateurs_id_current = '".$this->id."' AND new_message = '1' ";
		$res = mysql_query($req);
		$rows = mysql_num_rows($res);

		if($rows != 0){

			echo $rows;
		}
	}

	function addMessage($ctn){

		$contenu = $ctn[0];
		$conv = $ctn[1];
		$escapedContenu  = addslashes($contenu);
		
		// Je vérifie si la conversation existe pour l'utilisateurs
		
		$newConv =  "SELECT * FROM conversation WHERE utilisateurs_id_current  IN('".$this->id."','".$this->idDistant."') AND utilisateurs_id_distant IN('".$this->id."','".$this->idDistant."') ";
		
		$newConvQuery = mysql_query($newConv);

		$nombreUser = mysql_num_rows($newConvQuery);
		
		// Si il n'ya  qu'un utilisateur active dans la conversation,
		if($nombreUser == 1){



		}
		// Si il y a deux utilisateurs actifs dans la conversations,  j'insere le message
		if($nombreUser == 2){

			$req = "INSERT INTO chat_messages VALUES('','".$this->id."','".$this->idDistant."','".$escapedContenu."','".$this->stamp."','".$this->date."', '1', '1','".$conv."')";

			
			$gostamp = "UPDATE conversation  SET last_message='".$stamp."'  WHERE utilisateurs_id_current  IN('".$this->id."','".$this->idDistant."') AND utilisateurs_id_distant IN('".$this->id."','".$this->idDistant."')";

			$setNotif = "UPDATE conversation  SET new_message = 1  WHERE utilisateurs_id_current = '".$this->idDistant."' AND utilisateurs_id_distant ='".$this->id."'  ";
			
			$res = mysql_query($req) or die(mysql_error());
			$resgoStamp = mysql_query($gostamp);
			$resSetNotif = mysql_query($setNotif);

		}

		// Si non, je crée la conversation et j'insere les messages
		else if($nombreUser == 0 || $nombreUser == 1){

			//j'insere la conversation 1
			$inserConv = "INSERT INTO conversation VALUES ('','".$this->id."','".$this->idDistant."','','','')";
			
			//j'insere la conversation 2
			$sameid = "INSERT INTO conversation VALUES ('','".$this->idDistant."','".$this->id."','','','')";

			//j'insere le message
			$reqAfter = "INSERT INTO chat_messages VALUES('','".$this->id."','".$this->idDistant."','".$escapedContenu."','".$this->stamp."','".$this->date."', '1', '1','')";
			
			//je met à jour le message pour faire tilter les notifs
			$gostamp = "UPDATE conversation  SET last_message='".$stamp."'  WHERE utilisateurs_id_current IN('".$this->id."','".$this->idDistant."') AND utilisateurs_id_distant IN('".$this->id."','".$this->idDistant."')";

			//ici aussi
			$setNotif = "UPDATE conversation  SET new_message = 1  WHERE utilisateurs_id_current = '".$this->idDistant."' AND utilisateurs_id_distant ='".$this->id."'  ";


			
			
			//query conversation 1
			$reqInserConv = mysql_query($inserConv);
			$idConv1 = mysql_insert_id();

			// mise à jour du lien
			$updateLink1 = "UPDATE conversation  SET link = '".$idConv1."'  WHERE idconversation = '".$idConv1."'  ";

			//query conversation 2
			$reqInserConv2 = mysql_query($sameid);
			$idConv2 = mysql_insert_id();

			// mise à jour du lien là aussi
			$updateLink2 = "UPDATE conversation  SET link = '".$idConv1."'  WHERE idconversation = '".$idConv2."'  ";
			

			$resLink1 = mysql_query($updateLink1) or die(mysql_error());
			$resLink2 = mysql_query($updateLink2) or die(mysql_error());
			
			
			$resAfter = mysql_query($reqAfter);
			$idMess = mysql_insert_id();

			$idMessUpdate = "UPDATE chat_messages SET id_conversations = '".$idConv1."' WHERE idchat_messages = '".$idMess."'  ";

			$updateLinkMess = mysql_query($idMessUpdate);
			$resgoStamp = mysql_query($gostamp);
			$resSetNotif = mysql_query($setNotif);
			

		}

		else{

			return;
		}



	}

	function getMessage(){

		$firstTab = "SELECT * FROM conversation WHERE utilisateurs_id_current = '".$this->id."' AND utilisateurs_id_distant = '".$this->idDistant."' ";
		$q = mysql_query($firstTab);
		$t  = mysql_fetch_array($q);
		$j = array();
		
		foreach ($t as $k => $v) {

			if($k === "link"){
				
				$req = "SELECT DISTINCT conversation.*,chat_messages.*,utilisateurs.pseudo, utilisateurs.nom, utilisateurs.prenom, utilisateurs.id FROM conversation, chat_messages, utilisateurs WHERE chat_messages.utilisateurs_id_current IN('".$this->id."','".$this->idDistant."') AND chat_messages.utilisateurs_id_distant IN('".$this->id."','".$this->idDistant."') AND chat_messages.utilisateurs_id_current = utilisateurs.id AND chat_messages.id_conversations = '".$v."' GROUP BY idchat_messages  ORDER BY chat_messages.idchat_messages ASC	";

				$q = mysql_query($req);

				while($var = mysql_fetch_array($q,MYSQL_ASSOC)){

					$j[] = array('idconvers'=>$var['id_conversations'],'contenu'=>$var['contenu'],'id'=>$var['id'],'nom'=>$var["nom"],'utilisateurs_id_current'=>$var["utilisateurs_id_current"],'utilisateurs_id_distant'=>$var["utilisateurs_id_distant"],'pseudo'=>$var["pseudo"],'datepost'=>$var["datepost"],'idchat_messages'=>$var["idchat_messages"],);
				}	


			}
			

		}
		
		echo json_encode($j);
		

		$reqUpdate = "UPDATE chat_messages SET new_current='0', new_distant='0' WHERE chat_messages.utilisateurs_id_current IN('".$this->id."','".$this->idDistant."') AND chat_messages.utilisateurs_id_distant IN('".$this->id."','".$this->idDistant."') ";
		$reqUpdateConvers = "UPDATE conversation SET new_message = '0' WHERE utilisateurs_id_current = '".$this->id."' "; 
		$resUpdateConvers = mysql_query($reqUpdateConvers);
		$resUpdate = mysql_query($reqUpdate) or die(mysql_error());





	}	


	function getLastMessage(){

		$req = select("SELECT * FROM chat_messages WHERE utilisateurs_id_current IN('".$this->id."','".$this->idDistant."') AND utilisateurs_id_distant IN('".$this->id."','".$this->idDistant."') ", true);

	//---------------------------------------------------------------
	// Dans cette boucle, j'indique que les messages sont lus
	//---------------------------------------------------------------

		foreach ($req as $key => $value) {


			if($this->id == $value["utilisateurs_id_current"]){

				$reqCurrent = select("SELECT *,utilisateurs.pseudo, utilisateurs.nom, utilisateurs.prenom, utilisateurs.id FROM chat_messages, utilisateurs WHERE utilisateurs_id_current IN('".$this->id."','".$this->idDistant."') AND utilisateurs_id_distant IN('".$this->id."','".$this->idDistant."') AND chat_messages.utilisateurs_id_current = utilisateurs.id AND new_current='1' ", true);

				foreach ($reqCurrent as $key => $value2) {

					$idToUpdate1 = $value2["idchat_messages"];

					$reqUpdate1 = "UPDATE chat_messages SET new_current='0' WHERE idchat_messages='".$idToUpdate1."' ";

					$obiwan = "UPDATE conversation SET new_message = '0' WHERE utilisateurs_id_current = '".$this->id."' ";

					$resUpdate1 = mysql_query($reqUpdate1) or die(mysql_error());
					
					mysql_query($obiwan);

				}
				echo json_encode($reqCurrent);
				return;
			}


			if($this->id == $value["utilisateurs_id_distant"]){

				$reqDistant = select("SELECT *,utilisateurs.pseudo, utilisateurs.nom, utilisateurs.prenom, utilisateurs.id FROM chat_messages, utilisateurs WHERE utilisateurs_id_current IN('".$this->id."','".$this->idDistant."') AND utilisateurs_id_distant IN('".$this->id."','".$this->idDistant."') AND chat_messages.utilisateurs_id_current = utilisateurs.id AND new_distant='1' ", true);

				foreach ($reqDistant as $key => $value3) {

					$idToUpdate2 = $value3["idchat_messages"];

					$reqUpdate2 = "UPDATE chat_messages SET new_distant='0' WHERE idchat_messages='".$idToUpdate2."' ";

					$kenobi = "UPDATE conversation SET new_message = '0' WHERE utilisateurs_id_current = '".$this->id."' ";

					$resUpdate2 = mysql_query($reqUpdate2) or die(mysql_error());
					mysql_query($kenobi);

				}
				echo json_encode($reqDistant);
				return;
			}



		}

	}
}



?>