<?php 
header('Content-Type: text/html; charset=utf-8');
session_start();
require CLASSROOT."/lib/WideImage.php";
require CLASSROOT."/lib/class.getimage.php";
require INCROOT."/inc.connexion.php";
class Post{

	private $path_id;
	private $utilisateur_id;
	private $contenu;
	private $date;
	private $stamp;
	private $type;
	private $idp;
	private $uid;
	private $getPostId;

	function __construct($p){

		$this->path_id = sha1($_SESSION['id']);
		$this->utilisateur_id = $_SESSION['id'];
		$this->contenu = mysql_real_escape_string($p[1]);
		$jour = date('d/m');
		$heure = date('H:i:s');
		$this->date = mysql_real_escape_string($jour.' - '.$heure);
		$this->stamp = time();
		$this->getPostId = $gid;

		
	}
	function displayPost($f){

		$pid = $f[0];

		$req = select("SELECT DISTINCT posts.*,utilisateurs.id,utilisateurs.nom,utilisateurs.prenom,utilisateurs.pseudo FROM posts,utilisateurs WHERE posts.id = '".$pid."' AND posts.utilisateurs_id = utilisateurs.id  GROUP BY posts.id ", true);
		
		
		echo json_encode($req);


	}

	function newPost($f){

		if($f[0] != "youtube"){

			$escapectn = addslashes($this->contenu);
			$req = "INSERT INTO posts(utilisateurs_id,contenu,datepost,rootpic,stamp,type) VALUES ('".$this->utilisateur_id."','".htmlentities($escapectn)."','".addslashes($this->date)."', '".$this->path_id."','".$this->stamp."','img')";
			mysql_query("SET NAMES utf8" );
			$res = mysql_query($req) or die(mysql_error());
			$imgid = mysql_insert_id();
			if ($_FILES['postImg']['error'] == UPLOAD_ERR_NO_FILE) {
				echo 'Aucun fichier';
				return;
			} 
			
			else {

				$image = WideImage::load('postImg');
				$pathImg = mysql_real_escape_string("/images/".$this->path_id."/post_pic/".$imgid.".jpg");
				$pathImgSec = htmlspecialchars($pathImg);
				$reqImg = "UPDATE posts SET img_id='".$imgid."' WHERE id=$imgid";
				$reqPathImg = "INSERT INTO images(root, posts_id) VALUES ('".$pathImgSec."','".$imgid."')";
				mysql_query("SET NAMES utf8" );
				$res = mysql_query($reqImg) or die(mysql_error());mysql_query($reqPathImg) or die(mysql_error()); 
				$exif = exif_read_data($img);
			// Dans le cas où l'image serait prise en portrait
				if ($exif['Orientation'] === 6) {	
					$resized = $image->rotate(90);
					$resized->saveToFile("../images/".$this->path_id."/post_pic/".$imgid.".jpg",60);
					return;
				}
			// Dans les autres cas
				if ($exif['Orientation'] === 3) {	
					$resized = $image->rotate(180);
					$resized->saveToFile("../images/".$this->path_id."/post_pic/".$imgid.".jpg",60);
					return;
				}
			// Dans les autres cas			
				$image->saveToFile("../images/".$this->path_id."/post_pic/".$imgid.".jpg",60);
				
				

				
				
			}

		}
		else{

			
			$og = fetch_og($f[1]);

			$getCode = explode("=", $og['url']);
			$setEmbed = '//www.youtube.com/embed/'.$getCode[1].'';

			$req = "INSERT INTO posts(utilisateurs_id,contenu,datepost,rootpic,stamp,type,embed) VALUES ('".$this->utilisateur_id."','".mysql_real_escape_string(htmlspecialchars(htmlentities($og["title"])))."','".addslashes($this->date)."', '".$this->path_id."','".$this->stamp."','youtube','".$setEmbed."')";
			mysql_query("SET NAMES utf8" );
			$res = mysql_query($req) or die(mysql_error());
			$imgid = mysql_insert_id();


			
			$reqImg = "UPDATE posts SET img_id='".$imgid."' WHERE id=$imgid";
			$pathImg = mysql_real_escape_string("/images/".$this->path_id."/post_pic/".$imgid.".jpg");
			$pathImgSec = htmlspecialchars($pathImg);
			$reqPathImg = "INSERT INTO images(root, posts_id) VALUES ('".$pathImgSec."','".$imgid."')";
			
			mysql_query("SET NAMES utf8" );
			$res = mysql_query($reqImg) or die(mysql_error());mysql_query($reqPathImg) or die(mysql_error()); 

			$image = new GetImage;
			$image->source = $og["image"];
			$image->save_to = "../images/".$this->path_id."/post_pic/";
			$get = $image->download('gd',$imgid,40); 


		}


		
	}
	
	function deletePost($f){

		$idp = $f[0];
		$uid = $f[1];		
		unlink('../images/'.$this->path_id.'/post_pic/'.$idp.'.jpg');
		
		$reqimg = "DELETE FROM images WHERE posts_id = '".$idp."'  ";
		mysql_query($reqimg) or die(mysql_error());

		
		$reqwall = "DELETE FROM mur_photos WHERE posts_id = '".$idp."'  ";
		mysql_query($reqwall) or die(mysql_error());

		
		$reqcomm = "DELETE FROM comments WHERE posts_id = '".$idp."'  ";
		mysql_query($reqcomm) or die(mysql_error());


		$req = "DELETE FROM posts WHERE id = '".$idp."'  ";
		mysql_query($req) or die(mysql_error());
		
	}

	function getPost($p){

		$id = $p[0];
		$type = $p[1];
		$idp = $p[2];
		$uid = $p[3];
		$tdiv = $p[4];


		switch ($type) {

			case 'counter':
			
			if($tdiv == "coeur")$tdiv = "mur_photos";
			
			$counter = "SELECT * FROM ".$tdiv." WHERE posts_id = '".$idp."'  ";
			$res = mysql_query($counter);
			$rows = mysql_num_rows($res);
			echo $rows;
			break;	

			case 'world':

			if($idp == 0){

				$req = select( "SELECT posts.id, posts.contenu,posts.datepost, posts.public,posts.img_id, utilisateurs.nom, utilisateurs.prenom,utilisateurs.description,utilisateurs.id,utilisateurs.pseudo, images.root FROM posts, utilisateurs, images  WHERE posts.id > 0 AND posts.utilisateurs_id = utilisateurs.id AND posts.img_id = images.posts_id  ORDER BY stamp DESC LIMIT 10", true );
				
				echo json_encode($req);

			}

			else{

				$req = select( "SELECT posts.id, posts.contenu,posts.datepost, posts.public,posts.img_id, utilisateurs.nom, utilisateurs.prenom,utilisateurs.description,utilisateurs.id,utilisateurs.pseudo, images.root FROM posts, utilisateurs, images  WHERE posts.id < '".$idp."' AND posts.id > 0 AND   posts.utilisateurs_id = utilisateurs.id AND posts.img_id = images.posts_id  ORDER BY stamp DESC LIMIT 10", true );


				echo json_encode($req);

			}
			break;

			case 'friends':
			if($idp == 0){

				$req = select("SELECT DISTINCT p.* , u.id, u.prenom,u.nom,u.description,u.pseudo, a.*,i.* 
					FROM posts p, utilisateurs u, amis a, images i WHERE p.id > 0 AND u.id != '".$this->utilisateur_id."' AND (a.utilisateurs_id_current != '".$this->utilisateur_id."' OR a.utilisateurs_id_distant != '".$this->utilisateur_id."') AND (a.utilisateurs_id_current = '".$this->utilisateur_id."' OR a.utilisateurs_id_distant = '".$this->utilisateur_id."') AND (p.utilisateurs_id = a.utilisateurs_id_current OR  p.utilisateurs_id = a.utilisateurs_id_distant AND p.utilisateurs_id != '".$this->utilisateur_id."' AND p.utilisateurs_id != '".$this->utilisateur_id."') AND p.utilisateurs_id = u.id AND p.img_id = i.posts_id  GROUP BY p.id ORDER BY stamp DESC LIMIT 10", true);

				echo json_encode($req);
			}


			else{

				$req = select("SELECT DISTINCT p.* ,u.id, u.prenom,u.nom,u.description,u.pseudo, a.*,i.* FROM posts p, utilisateurs u, amis a, images i WHERE p.id > 0 AND p.id < '".$idp."' AND  u.id != '".$this->utilisateur_id."' AND (a.utilisateurs_id_current != '".$this->utilisateur_id."' OR a.utilisateurs_id_distant != '".$this->utilisateur_id."') AND (a.utilisateurs_id_current = '".$this->utilisateur_id."' OR a.utilisateurs_id_distant = '".$this->utilisateur_id."') AND (p.utilisateurs_id = a.utilisateurs_id_current OR  p.utilisateurs_id = a.utilisateurs_id_distant) AND p.utilisateurs_id = u.id AND p.img_id = i.posts_id GROUP BY p.id ORDER BY stamp DESC LIMIT 10", true);

				echo json_encode($req);
				
			}
			break;

			case "wall":
			if($idp == 0){
				
				$req = select( "SELECT posts.*,utilisateurs.id,utilisateurs.nom,utilisateurs.pseudo,utilisateurs.prenom,mur_photos.idpinned_photos, images.root FROM posts, utilisateurs, images, mur_photos  WHERE posts.id > 0 AND mur_photos.utilisateurs_id_mur = '".$id."' AND posts.utilisateurs_id = utilisateurs.id AND mur_photos.posts_id = posts.id AND posts.img_id = images.posts_id ORDER BY mur_photos.idpinned_photos DESC LIMIT 10", true );
			}
			else{
				
				$req = select( "SELECT posts.*,utilisateurs.id,utilisateurs.nom,utilisateurs.pseudo,utilisateurs.prenom, images.root, mur_photos.* FROM posts, utilisateurs, images, mur_photos  WHERE mur_photos.idpinned_photos < '".$idp."' AND posts.id > 0 AND mur_photos.utilisateurs_id_mur = '".$id."' AND posts.utilisateurs_id = utilisateurs.id AND mur_photos.posts_id = posts.id AND posts.img_id = images.posts_id ORDER BY mur_photos.idpinned_photos DESC LIMIT 10", true );

			}
			echo json_encode($req);

			break;
			
			case "profile":

			if($idp == 0){	
				
				$req = select_profil("SELECT posts.contenu,posts.datepost, posts.public,posts.img_id, utilisateurs.nom, utilisateurs.prenom,utilisateurs.id,utilisateurs.description,utilisateurs.pseudo, utilisateurs.vehicule, utilisateurs.marque_vehicule,images.root FROM posts, utilisateurs, images  WHERE posts.id > 0 AND posts.utilisateurs_id = '".$uid."' AND posts.utilisateurs_id = utilisateurs.id AND posts.img_id = images.posts_id  ORDER BY stamp DESC LIMIT 10", true );
				

				echo json_encode($req);
			}
			else{

				$req = select_profil( "SELECT posts.contenu,posts.id,posts.datepost, posts.public,posts.img_id, utilisateurs.nom, utilisateurs.prenom,utilisateurs.id,utilisateurs.description,utilisateurs.pseudo, utilisateurs.vehicule, utilisateurs.marque_vehicule,images.root FROM posts, utilisateurs, images  WHERE posts.utilisateurs_id = '".$uid."' AND posts.id > 0 AND posts.utilisateurs_id = utilisateurs.id AND posts.img_id = images.posts_id AND posts.id < '".$idp."'  ORDER BY stamp DESC LIMIT 10", true );

				echo json_encode($req);

			}
			break;
			case "notifs":

			$req = select("SELECT DISTINCT notifications.type, notifications.posts_id,utilisateurs.id, utilisateurs.prenom,utilisateurs.nom,utilisateurs.pseudo,utilisateurs.pseudo, posts.id, posts.utilisateurs_id,posts.contenu,posts.img_id,images.root FROM notifications,utilisateurs, posts,images WHERE notifications.posts_id = posts.id AND utilisateurs.id = posts.utilisateurs_id AND posts.utilisateurs_id = '".$this->utilisateur_id."' AND posts.img_id = images.posts_id GROUP BY posts.id ", true);
			
			echo json_encode($req);

			break;



			
		}

	}



}

?>