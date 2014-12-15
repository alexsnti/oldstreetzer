<?php 
session_start();
require INCROOT."/inc.connexion.php";
class Amis{

	private $id;
	private $date;

	function __construct($i){

	$this->id = $_SESSION['id'];
	$jour = date('d/m');
	$heure = date('H:i:s');
	$this->date = mysql_real_escape_string($jour.' - '.$heure);

	}
	function supprAmis($f){

		$IDuserCible = $f[0];

		$req = "DELETE FROM amis WHERE utilisateurs_id_current IN('".$IDuserCible."','".$this->id."') && utilisateurs_id_distant IN('".$IDuserCible."','".$this->id."')"; 

		$res = mysql_query($req);

	}
	function checkAmitie($f){

		$IDuserCible = $f[0];


		$reqCheck = "SELECT * FROM amis WHERE utilisateurs_id_current IN('".$IDuserCible."','".$this->id."') && utilisateurs_id_distant IN('".$IDuserCible."','".$this->id."')";

		$resCheck = mysql_query($reqCheck);

		if(mysql_num_rows($resCheck) != 0){


		echo 1;


		}

	}

	function ajoutAmis($argtab){

		$IDuserCible = $argtab[0];
		
		// Je vérifie que l'utilisateur ne se demande pas lui même en ami

		if($IDuserCible == $this->id){

		echo "sameuser";
		return;
	}

	// Je verifie que le lien d'amitié n'existe pas déja

	$reqCheck = "SELECT * FROM amis WHERE utilisateurs_id_current IN('".$IDuserCible."','".$this->id."') && utilisateurs_id_distant IN('".$IDuserCible."','".$this->id."')";

	$resCheck = mysql_query($reqCheck);

	if(mysql_num_rows($resCheck) != 0){


	echo "exist";
	return;

	}

	// Maintenant j'insere la relation d'amitié

	$req = "INSERT INTO amis(utilisateurs_id_current,utilisateurs_id_distant,statut) VALUES  ('".$this->id."','".$IDuserCible."','1')";
	$reqNotif = "INSERT INTO notifications  VALUES ('','newFriend','".$this->date."','0','".$IDuserCible."','".$this->id."','0')";
	$resNotif = mysql_query($reqNotif) or die(mysql_error());
	$res = mysql_query($req) or die(mysql_error());

	}

	function getAmis($argtab){

		$idAmis = $argtab[0];
		$req = select("SELECT * FROM utilisateurs WHERE id = '".$idAmis."'", true);
		echo json_encode($req);


	}

	function listDemandeAmitie(){

		$req = select( "SELECT * FROM amis WHERE utilisateurs_id_distant = '".$this->id."' && statut = '0'", true );
		echo json_encode($req);
	}


	function listAmisSuggest($f){

		$arg = $f[0];

		$req = "SELECT utilisateurs.id FROM utilisateurs WHERE nom LIKE '%".$arg."%' OR prenom LIKE '%".$arg."%'";
		$res = mysql_query($req) or die(mysql_error());

		$a = array();
		$finalJson = array();
		while($var = mysql_fetch_array($res,MYSQL_ASSOC)){



		$check = "SELECT * FROM amis WHERE utilisateurs_id_current IN('".$this->id."','".$var['id']."') AND utilisateurs_id_distant IN('".$this->id."','".$var['id']."') ";
		
		$resCheck = mysql_query($check);

		while($varcheck = mysql_fetch_array($resCheck,MYSQL_ASSOC)){


				
			if($varcheck['utilisateurs_id_distant'] != $this->id) {
				
				$rf = "SELECT utilisateurs.nom,utilisateurs.prenom FROM utilisateurs WHERE utilisateurs.id = '".$varcheck['utilisateurs_id_distant']."'";	

				$rsf = mysql_query($rf)or die(mysql_error());

				while($varrfs = mysql_fetch_array($rsf,MYSQL_ASSOC)){

					$finalJson[] = array('nom'=>$varrfs['nom'],'prenom'=>$varrfs['prenom'],'id'=>$varrfs['id']);
				}	 
			}

			if($varcheck['utilisateurs_id_current'] != $this->id) {
				
				$rfc = "SELECT utilisateurs.id,utilisateurs.nom,utilisateurs.prenom FROM utilisateurs WHERE utilisateurs.id = '".$varcheck['utilisateurs_id_current']."'";	

				$rfcss = mysql_query($rfc);

				while($varrfsc = mysql_fetch_array($rfcss,MYSQL_ASSOC)){

					$finalJson[] = array('nom'=>$varrfsc['nom'],'prenom'=>$varrfsc['prenom'],'id'=>$varrfsc['id']);
				}	

			}

		}
		


	}

	echo json_encode($finalJson);
	}
	
	function listAmis(){

	$req = "SELECT * FROM amis WHERE  statut = '1' && 	utilisateurs_id_current = '".$this->id."' || utilisateurs_id_distant = '".$this->id."' ";
	$res = mysql_query($req);
	$a = array();
	$b = array();


	while($var = mysql_fetch_array($res,MYSQL_ASSOC)){

		if($var['utilisateurs_id_distant'] != $this->id) {
			$a[]=$var['utilisateurs_id_distant'];		 
		}

		if($var['utilisateurs_id_current'] != $this->id) {
			$a[]=$var['utilisateurs_id_current'];
		}

	}

	for($i=0;$i<sizeof($a);$i++){

		$reqFinal= "SELECT utilisateurs.id,utilisateurs.prenom,utilisateurs.nom,utilisateurs.pseudo FROM utilisateurs WHERE id =  '".$a[$i]."'  ";
		$resFinal = mysql_query($reqFinal);

		while($varF = mysql_fetch_array($resFinal,MYSQL_ASSOC)){


			$b[] = array('id'=>$varF['id'],'nom'=>$varF['nom'],'prenom'=>$varF['prenom'],'pseudo'=>$varF['pseudo'],'pp'=>sha1($varF['id']));


		}



	}
	echo json_encode($b);
	}

	function onlineFriends($argtab){
	$idAmis2 = $argtab[0];

	$req = select("SELECT * FROM online WHERE utilisateurs_id = '".$idAmis2."'", true);
	echo json_encode($req);

	}

}

?>