<?php
/*
Neoterranos & LkY
Page fonctions.php

Contient quelques fonctions globales.

Quelques indications : (utiliser l'outil de recherche et rechercher les mentions donnÃ©es)

Liste des fonctions :
--------------------------
sqlquery($requete,$number)
connexionbdd()
actualiser_session()
vider_cookie()
--------------------------


Liste des informations/erreurs :
--------------------------
Mot de passe de session incorrect
Mot de passe de cookie incorrect
L'id de cookie est incorrect
--------------------------
*/

/**************************************************************/
// CONNEXION A LA BDD
/*try
{   
	$bdd = new PDO('mysql:host=localhost;dbname=espace_membre2;charset=utf8', 'root', '');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}*/

try
{  
	$db_config = array();
	$db_config['SGBD']	= 'mysql';
	$db_config['HOST']	= 'pronospoggpyb.mysql.db';
	$db_config['DB_NAME']	= 'pronospoggpyb';
	$db_config['USER']	= 'pronospoggpyb';
	$db_config['PASSWORD']	= 'Aslan4lion';
	$db_config['OPTIONS']	= array(
		// Activation des exceptions PDO :
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		// Change le fetch mode par défaut sur FETCH_ASSOC ( fetch() retournera un tableau associatif ) :
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
	);
	
	$bdd = new PDO($db_config['SGBD'] .':host='. $db_config['HOST'] .';dbname='. $db_config['DB_NAME'].';charset=utf8',
	$db_config['USER'],
	$db_config['PASSWORD'],
	$db_config['OPTIONS']);
	unset($db_config);
}
catch(Exception $e)
{
	trigger_error($e->getMessage(), E_USER_ERROR);
}
/**************************************************************/



function actualiser_session()
{
    global $bdd;
	if(isset($_SESSION['membre_id']) && intval($_SESSION['membre_id']) != 0) //Vérification id
        
	{
		//utilisation de la fonction sqlquery, on sait qu'on aura qu'un résultat car l'id d'un membre est unique.
        $retour = $bdd->prepare('SELECT membre_id, membre_pseudo, membre_mdp,membre_avatar,membre_statut,membre_groupe, membre_club FROM membres WHERE membre_id = :id ');
        $retour->execute(array('id' => $_SESSION['membre_id'] ));
        $data = $retour->fetch(PDO::FETCH_ASSOC);
		
		//Si la requête a un résultat (c'est-à-dire si l'id existe dans la table membres)
		if(isset($data['membre_pseudo']) && $data['membre_pseudo'] != '')
            
		{
			if($_SESSION['membre_mdp'] != $data['membre_mdp'])
                
			{
				//Dehors vilain pas beau !
				$informations = Array(/*Mot de passe de session incorrect*/
									true,
									'Session invalide',
									'Le mot de passe de votre session est incorrect, vous devez vous reconnecter.',
									'',
									'membres/connexion.php',
									3
									);
				require_once('./information.php');
				vider_cookie();
				session_destroy();
				exit();
			}
			
			else
			{
				//Validation de la session.
					$_SESSION['membre_id'] = $data['membre_id'];
					$_SESSION['membre_pseudo'] = $data['membre_pseudo'];
					$_SESSION['membre_mdp'] = $data['membre_mdp'];
                    $_SESSION['membre_avatar'] = $data['membre_avatar'];
                    $_SESSION['membre_statut'] = $data['membre_statut'];
                    $_SESSION['membre_groupe'] = $data['membre_groupe'];
		      $_SESSION['membre_club'] = $data['membre_club'];
			}
		}
	}
	
	else //On vérifie les cookies et sinon pas de session
	{
		if(isset($_COOKIE['membre_id']) && isset($_COOKIE['membre_mdp'])) //S'il en manque un, pas de session.
		{
			if(intval($_COOKIE['membre_id']) != 0)
			{
				//idem qu'avec les $_SESSION
            $retour = $bdd->prepare('SELECT membre_id, membre_pseudo, membre_mdp,membre_avatar,membre_statut,membre_groupe FROM membres WHERE membre_id = :id ');
            $retour->execute(array('id' => $_COOKIE['membre_id'] ));
            $data = $retour->fetch(PDO::FETCH_ASSOC);
				
				if(isset($data['membre_pseudo']) && $data['membre_pseudo'] != '')
				{
					if($_COOKIE['membre_mdp'] != $data['membre_mdp'])
					{
						//Dehors vilain tout moche !
						$informations = Array(/*Mot de passe de cookie incorrect*/
											true,
											'Mot de passe cookie erroné',
											'Le mot de passe conservé sur votre cookie est incorrect vous devez vous reconnecter.',
											'',
											'membres/connexion.php',
											3
											);
						require_once('../information.php');
						vider_cookie();
						session_destroy();
						exit();
					}
					
					else
					{
						//Bienvenue :D
						$_SESSION['membre_id'] = $data['membre_id'];
						$_SESSION['membre_pseudo'] = $data['membre_pseudo'];
						$_SESSION['membre_mdp'] = $data['membre_mdp'];
                        $_SESSION['membre_avatar'] = $data['membre_avatar'];
                        $_SESSION['membre_statut'] = $data['membre_statut'];
                        $_SESSION['membre_groupe'] = $data['membre_groupe'];
                        $_SESSION['membre_club'] = $data['membre_club'];
					}
				}
			}
			
			else //cookie invalide, erreur plus suppression des cookies.
			{
				$informations = Array(/*L'id de cookie est incorrect*/
									true,
									'Cookie invalide',
									'Le cookie conservant votre id est corrompu, il va donc être détruit vous devez vous reconnecter.',
									'',
									'membres/connexion.php',
									3
									);
				require_once('../information.php');
				vider_cookie();
				session_destroy();
				exit();
			}
		}
		
		else
		{
			//Fonction de suppression de toutes les variables de cookie.
			if(isset($_SESSION['membre_id'])) unset($_SESSION['membre_id']);
			vider_cookie();
		}
	}
}

function vider_cookie()
{
	foreach($_COOKIE as $cle => $element)
	{
		setcookie($cle, '', time()-3600,'/');
	}
}
?>


<?php
function checkpseudo($pseudo)
{
    global $bdd;
	if($pseudo == '') return 'empty';
	else if(strlen($pseudo) < 3) return 'tooshort';
	else if(strlen($pseudo) > 20) return 'toolong';
	
	else
	{
$result = $bdd->prepare('SELECT COUNT(*) AS nbr FROM membres WHERE membre_pseudo = :pseudo ');
		global $queries;
		$queries++; 
        $result->execute(array('pseudo' => $pseudo));
        $result = $result->fetch(PDO::FETCH_ASSOC);

        if ($result['nbr'] > 0) return 'exists';
        else return 'ok';
	}
}
?>

<?php
function checkmdp($mdp)
{
	if($mdp == '') return 'empty';
	else if(strlen($mdp) < 4) return 'tooshort';
	else if(strlen($mdp) > 50) return 'toolong';
	else return 'ok';
	/*else
	{
		if(!preg_match('#[0-9]{1,}#', $mdp)) return 'nofigure';
		else if(!preg_match('#[A-Z]{1,}#', $mdp)) return 'noupcap';
		
	}*/
}
?>

<?php
function checkmdpS($mdp, $mdp2)
{
	if($mdp != $mdp2 && $mdp != '' && $mdp2 != '') return 'different';
	else return checkmdp($mdp);
}
?>

<?php
function checkmail($email)
{
    global $bdd;
	if($email == '') return 'empty';
	else if(!preg_match('#^[a-z0-9A-Z._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#', $email)) return 'isnt';
    
    else
	{
        $result = $bdd->prepare('SELECT COUNT(*) AS nbr FROM membres WHERE membre_mail = :mail ');
        $result->execute(array('mail' => $email));
		global $queries;
		$queries++;
        $result = $result->fetch(PDO::FETCH_ASSOC);

        if ($result['nbr'] > 0) return 'exists';
        else return 'ok';
	}
}
?>

<?php
function checkmailS($email, $email2)
{
	if($email != $email2 && $email != '' && $email2 != '') return 'different';
	else return 'ok';
}
?>

<?php
function checkSexe($sexe)
{
	if($sexe == '') return 'empty';
	else return 'ok';
}
?>

<?php
function birthdate($date)
{
	if($date == '') return 'empty';

	else if(substr_count($date, '/') != 2) return 'format';
	else
	{
		$DATE = explode('/', $date);
		if(date('Y') - $DATE[2] <= 4) return 'tooyoung';
		else if(date('Y') - $DATE[2] >= 135) return 'tooold';
		
		else if($DATE[2]%4 == 0)
		{
			$maxdays = Array('31', '29', '31', '30', '31', '30', '31', '31', '30', '31', '30', '31');
			if($DATE[0] > $maxdays[$DATE[1]-1]) return 'invalid';
			else return 'ok';
		}
		
		else
		{
			$maxdays = Array('31', '28', '31', '30', '31', '30', '31', '31', '30', '31', '30', '31');
			if($DATE[0] > $maxdays[$DATE[1]-1]) return 'invalid';
			else return 'ok';
		}
	}
}
?>

<?php
function checkClub($club)
{
	if($club == 'S�lectionnez') return 'empty';
	else return 'ok';
}
?>




<?php
function checkclef($clef)
{
	if($clef != 'baobab') return 'different';
    else if ($clef == '') return 'empty';
	else return 'ok';
}
?>

<?php
function move_avatar($avatar)
{
    $extension_upload = strtolower(substr(  strrchr($avatar['name'], '.')  ,1));
    $name = time();
    $nomavatar = str_replace(' ','',$name).".".$extension_upload;
    $name = "../img/avatars/".str_replace(' ','',$name).".".$extension_upload;
    move_uploaded_file($avatar['tmp_name'],$name);
    return $nomavatar;
}
?>



<?php
function vidersession()
{
	foreach($_SESSION as $cle => $element)
	{
		unset($_SESSION[$cle]);
	}
}
?>

<?php
function inscription_mail($mail, $pseudo, $passe)
{
	$to = $mail;
	$subject = 'Inscription sur '.TITRESITE.' - '.$pseudo;
    
    $message = '<html>
                    <head>
                        <title></title>
                    </head>
                    
                    <body>
                        <div>Bienvenue sur '.TITRESITE.' !<br/>
                        Vous avez complété une inscription avec le pseudo
                        '.htmlspecialchars($pseudo, ENT_QUOTES).' à l\'instant.<br/>
                        Votre mot de passe est : '.htmlspecialchars($passe, ENT_QUOTES).'.<br/>
                        Veillez à le garder secret et à ne pas l\'oublier.<br/><br/>
                        
                        En vous remerciant.<br/><br/>
                        Moi - Wembaster de '.TITRESITE.'
                    </body>
                </html>';

//headers principaux.
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
//headers supplémentaires
$headers .= 'From: "Mon super site" <contact@supersite.com>' . "\r\n";
/*$headers .= 'Cc: "Duplicata" <duplicata@supersite.com>' . "\r\n";
$headers .= 'Reply-To: "Membres" <membres@supersite.com>' . "\r\n";*/

$mail = mail($to, $subject, $message, $headers); //marche

if($mail) return true;
return false;
}
?>




<?php
function get($type) //je ne me suis pas foulé. :D
{
        if($type == 'nb_membres')
        {
                $count = sqlquery("SELECT COUNT(*) AS nbr FROM membres", 1);
                return $count['nbr'];
        }
        
        else if($type == 'connectes')
        {
                $count = sqlquery("SELECT COUNT(*) AS nbr FROM connectes", 1);
                return $count['nbr'];
        }
        
        else
        {
                return 0;
        }
}
?>

<?php
function mepd($date)
{
        if(intval($date) == 0) return $date;
        
        $tampon = time();
        $diff = $tampon - $date;
        
        $dateDay = date('d', $date);
        $tamponDay = date('d', $tampon);
        $diffDay = $tamponDay - $dateDay;
        
        if($diff < 60 && $diffDay == 0)
        {
                return 'Il y a '.$diff.'s';
        }
        
        else if($diff < 600 && $diffDay == 0)
        {
                return 'Il y a '.floor($diff/60).'m et '.floor($diff%60).'s';
        }
        
        else if($diff < 3600 && $diffDay == 0)
        {
                return 'Il y a '.floor($diff/60).'m';
        }
        
        else if($diff < 7200 && $diffDay == 0)
        {
                return 'Il y a '.floor($diff/3600).'h et '.floor(($diff%3600)/60).'m';
        }
        
        else if($diff < 24*3600 && $diffDay == 0)
        {
                return 'Aujourd\'hui à '.date('H\hi', $date);
        }
        
        else if($diff < 48*3600 && $diffDay == 1)
        {
                return 'Hier à '.date('H\hi', $date);
        }
        
        else
        {
                return 'Le '.date('d/m/Y', $date).' à '.date('h\hi', $date).'.';
        }
}
?>

<?php
function updateConnectes($id)
{
        $ip = getIp();
        if($id != -1)
        {
                $id = $_SESSION['membre_id'];
                $additionnal = 1; //la variable à mettre dans connectes_membre
        }
        
        else
        {
                $additionnal = $ip;
        }
        
        mysql_query("DELETE FROM connectes WHERE connectes_actualisation < ".(time()-300)) or exit(mysql_error()); //MàJ générale des connectés
        mysql_query("INSERT INTO connectes VALUES(".$id.", '".$ip."', '".$additionnal."', ".time().")
        ON DUPLICATE KEY UPDATE connectes_actualisation=".time().", connectes_ip='".$ip."'") or exit(mysql_error()); //tiens, tiens, ON DUPLICATE... :o
        queries(2);
}

function getIp()
{
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) return $_SERVER['HTTP_X_FORWARDED_FOR'];
        else return $_SERVER['REMOTE_ADDR'];
}

/**** Pour vérifier si une chaîne ne contient que des chiffres ****/

function isNum($element) {
  return !preg_match ("/[^0-9]/", $element);
}

/* Vérification des pronostics ***/

function checkProno($prono) {
	if($prono == '') return 'empty';
	else if (!isNum($prono)) return 'KOchiffre';
    else return 'ok';
}


?>

