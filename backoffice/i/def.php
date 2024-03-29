<?php


function clearDir($dossier) {
    $ouverture=@opendir($dossier);
    if (!$ouverture) return;
    while($fichier=readdir($ouverture)) {
        if ($fichier == '.' || $fichier == '..') continue;
            if (is_dir($dossier."/".$fichier)) {
                $r=clearDir($dossier."/".$fichier);
                if (!$r) return false;
            }
            else {
                $r=@unlink($dossier."/".$fichier);
                if (!$r) return false;
            }
    }
closedir($ouverture);
$r=@rmdir($dossier);
if (!$r) return false;
    return true;
}

function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 0) return $min; // not so random...
        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
}

function getToken($length=32){
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    for($i=0;$i<$length;$i++){
        $token .= $codeAlphabet[crypto_rand_secure(0,strlen($codeAlphabet))];
    }
    return $token;
}
function sendMail($mail, $message_txt,$message_html,$sujet,$fromMail,$replyMail){

if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
{
    $passage_ligne = "\r\n";
}
else
{
    $passage_ligne = "\n";
}

//=====Création de la boundary
$boundary = "-----=".md5(rand());
//==========

//=====Création du header de l'e-mail.
$header = "From: \"Traqer\"<".$fromMail.">".$passage_ligne;
$header.= "Reply-to: \"Traqer\" <".$replyMail.">".$passage_ligne;
$header.= "MIME-Version: 1.0".$passage_ligne;
$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
//==========

//=====Création du message.
$message = $passage_ligne."--".$boundary.$passage_ligne;
//=====Ajout du message au format texte.
$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
$message.= $passage_ligne.$message_txt.$passage_ligne;
//==========
$message.= $passage_ligne."--".$boundary.$passage_ligne;
//=====Ajout du message au format HTML
$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
$message.= $passage_ligne.$message_html.$passage_ligne;
//==========
$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
//==========

//=====Envoi de l'e-mail.
mail($mail,$sujet,$message,$header);
//==========


}
function select($sql, $foreach=false){

    $res=mysql_query($sql) or die("Select Error : \n \tRequete : ".$sql." \n\tErreur ".mysql_errno()." : ".mysql_error());

    if($foreach==true){

        if(mysql_num_rows($res)>0){

            $tab=array();

            $i = 0;
            while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
                $tab[$i] = array();
                foreach ($row as $key => $value) {
                    # code...
                    $tab[$i][$key] = mysql_real_escape_string(htmlentities(stripslashes($value))); // =$row;
                    //print_r($row);
                }
                $i++;
            }
            return $tab;
        }
        else{
            return array();
        }
    }
    else{
        return mysql_fetch_array($res, MYSQL_ASSOC);
    }
}

function select_profil($sql, $foreach=false){

    $res=mysql_query($sql) or die("Select Error : \n \tRequete : ".$sql." \n\tErreur ".mysql_errno()." : ".mysql_error());

    if($foreach==true){

        if(mysql_num_rows($res)>0){

            $tab=array();

            $i = 0;
            while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
                $tab[$i] = array();
                foreach ($row as $key => $value) {
                    # code...
                    $tab[$i][$key] = mysql_real_escape_string(htmlentities(stripslashes($value)));
                    if($key == "id"){
                        $tab[$i]['id'] = $value;
                         $tab[$i]['c1'] = sha1($value);
                    }
                    // =$row;
                    //print_r($row);
                }
                $i++;
            }
            return $tab;
        }
        else{
            return array();
        }
    }
    else{
        return mysql_fetch_array($res, MYSQL_ASSOC);
    }
}
?>