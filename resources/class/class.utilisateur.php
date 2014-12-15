<?php
session_start();
require INCROOT."/inc.connexion.php";
require CLASSROOT."/lib/WideImage.php";
require CLASSROOT."/lib/class.getimage.php";
class Utilisateur{

    private $nom;
    private $prenom;
    private $email;
    private $pseudo;
    private $motdepasse;
    private $id;
    private $path_id;
    function __construct($a){


      
      $this->id = $_SESSION['id'];
      $this->path_id = sha1($_SESSION['id']);
      $this->nom = $a[0];
      $this->prenom = $a[1];
      $this->email  = $a[2];
      $this->pseudo = $a[3];
      $this->motdepasse = sha1($a[4]);
      $this->motdepasseVerif = sha1($a[5]);


    }

    function NewUtilisateur($f){

      $v = $f[0];
      $vi = $f[1];
      $s = $f[2];
      $m = $f[3];
      $n = $f[4];

      $check = $f[5];
    
      if($check != 0){

        return;
      }

      //Aprés avoir fais mes vérifications dans mon traitement de form,
      //j'envoi mon nouvel utilisateur dans la base de données

      $req = "INSERT INTO utilisateurs(nom, prenom,email,pseudo,motdepasse,ville,vehicule,sexe,marque_vehicule,naissance) VALUES ('".$this->nom."','".$this->prenom."','".$this->email."','".$this->pseudo."','".$this->motdepasse."','".$vi."','".$v."','".$s."','".$m."','".$n."')";
      $res = mysql_query($req) or die(mysql_error());
      $id = mysql_insert_id();
      $cryptid = sha1($id);
      $old = umask(0);
      

      mkdir("../images/".$cryptid."",0777);
      mkdir("../images/".$cryptid."/profil_pic",0777);
      mkdir("../images/".$cryptid."/post_pic",0777);
      umask($old);

      $image = new GetImage;
      $image->source = "../layout/img/pp.jpg";
      $image->save_to = "../images/".$cryptid."/profil_pic/";
      $get = $image->download('gd'); 

      $_SESSION["id"] = $id;
      $_SESSION["prenom"] = $this->prenom;
      $_SESSION["nom"] = $this->nom;
      $_SESSION["pseudo"] = $this->pseudo;
      $_SESSION["mail"] = $this->email;
      //Ajouter fonction envoi de mail pour confirmation de compte


    }

    function deleteUtilisateur(){

      $req = "DELETE FROM WHERE ";
      $res = mysql_query($req) or die(mysql_error());


    }

    function updateUtilisateur($f){

      $v = $f[0];
      $vi = $f[1];
      $s = $f[2];
      $m = $f[3];
      $n = $f[4];
      $d = $f[5];
      $check = $f[6];
    
      if($check != 0){

        return;
      }


      $req = "UPDATE utilisateurs SET nom = '".$this->nom."', prenom  = '".$this->prenom."',email  ='".$this->email."',pseudo ='".$this->pseudo."',vehicule ='".$v."',ville ='".$vi."',sexe ='".$s."',marque_vehicule = '".$m."',naissance = '".$n."',description = '".$d."' WHERE id ='".$this->id."'  ";

      $res = mysql_query($req) or die(mysql_error());

      $_SESSION['pseudo'] = $this->pseudo;
      $_SESSION['nom'] = $this->nom;
      $_SESSION['prenom'] = $this->prenom;

    }
    function changeProfilPic(){
      if ($_FILES['postImgProfil']['error'] == UPLOAD_ERR_NO_FILE) {
      echo 'Aucun fichier';
      return;
    } 
      $image = WideImage::load('postImgProfil');
      
      unlink('../images/'.$this->path_id.'/profil_pic/pp.jpg');
     
      $exif = exif_read_data($img);
      
      // Dans le cas où l'image serait prise en portrait
      
      if ($exif['Orientation'] == 6) { 
      $resized = $image->rotate(90);
      $resized->saveToFile("../images/".$this->path_id."/profil_pic/pp.jpg",60);
      }
      // Dans les autres cas
        else if ($exif['Orientation'] == 3) { 
      $resized = $image->rotate(180);
      $resized->saveToFile("../images/".$this->path_id."/profil_pic/pp.jpg",60);
      }
      // Dans les autres cas
           else { 
      $resized = $image->resize('50%', '50%')->rotate(360);     
      $resized->saveToFile("../images/".$this->path_id."/profil_pic/pp.jpg",60);
      }

    }
    function getUtilisateur(){

    $req = select_profil("SELECT * FROM utilisateurs WHERE id = '".$this->id."'  ", true);
    echo json_encode($req);

    }

    function supprPostNotifs(){

    $req = "UPDATE notifications SET seen='1'WHERE  utilisateurs_id = '".$this->id."' ";
     mysql_query($req)or die(mysql_error());
    
    }

    function getPostNotifs(){

     

    $req = "SELECT * FROM notifications WHERE utilisateurs_id = '".$this->id."' AND seen='0' ";
    $res = mysql_query($req);
    $rows = mysql_num_rows($res); 

    if($rows != 0){

        echo $rows;
      }
    
    
    }
    function getNotifs(){

     

    $req = select_profil("SELECT notifications.*,posts.id,posts.utilisateurs_id,posts.img_id,utilisateurs.pseudo,utilisateurs.id,utilisateurs.nom,utilisateurs.prenom,images.root FROM notifications,utilisateurs, posts,images WHERE notifications.utilisateurs_id = '".$this->id."' AND utilisateurs_id_action = utilisateurs.id AND notifications.posts_id = posts.id AND posts.img_id = images.posts_id GROUP BY notifications.idnotifications DESC",true);
      echo json_encode($req);
   

    
    
    
    }

 
}


?>