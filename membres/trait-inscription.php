<?php
/*

Page trait-inscription.php

Permet de valider son inscription.

*/

session_start();
header('Content-type: text/html; charset=utf-8');
include('../includes/config.php');

/********Actualisation de la session...**********/

include('../includes/fonctions.php');
actualiser_session();

/********Fin actualisation de session...**********/




/********Étude du bazar envoyé***********/

$_SESSION['erreurs'] = 0;

//Pseudo
if(isset($_POST['pseudo']))
{
	$pseudo = trim($_POST['pseudo']);
	$pseudo_result = checkpseudo($pseudo);
	if($pseudo_result == 'tooshort')
	{
		$_SESSION['pseudo_info'] = '<span class="erreur">Le pseudo '.htmlspecialchars($pseudo, ENT_QUOTES).' est trop court, vous devez en choisir un plus long (minimum 3 caractères).</span><br/>';
		$_SESSION['form_pseudo'] = '';
		$_SESSION['erreurs']++;
	}
	
	else if($pseudo_result == 'toolong')
	{
		$_SESSION['pseudo_info'] = '<span class="erreur">Le pseudo '.htmlspecialchars($pseudo, ENT_QUOTES).' est trop long, vous devez en choisir un plus court (maximum 20 caractères).</span><br/>';
		$_SESSION['form_pseudo'] = '';
		$_SESSION['erreurs']++;
	}
	
	else if($pseudo_result == 'exists')
	{
		$_SESSION['pseudo_info'] = '<span class="erreur">Le pseudo '.htmlspecialchars($pseudo, ENT_QUOTES).' est déjà pris, choisissez-en un autre.</span><br/>';
		$_SESSION['form_pseudo'] = '';
		$_SESSION['erreurs']++;
	}
		
	else if($pseudo_result == 'ok')
	{
		$_SESSION['pseudo_info'] = '';
		$_SESSION['form_pseudo'] = $pseudo;
	}
	
	else if($pseudo_result == 'empty')
	{
		$_SESSION['pseudo_info'] = '<span class="erreur">Vous n\'avez pas entré de pseudo.</span><br/>';
		$_SESSION['form_pseudo'] = '';
		$_SESSION['erreurs']++;	
	}
}

else
{
	header('Location: ../index.php');
	exit();
}

//mail
if(isset($_POST['mail']))
{
	$mail = trim($_POST['mail']);
	$mail_result = checkmail($mail);
	if($mail_result == 'isnt')
	{
		$_SESSION['mail_info'] = '<span class="erreur">Le mail '.htmlspecialchars($mail, ENT_QUOTES).' n\'est pas valide.</span><br/>';
		$_SESSION['form_mail'] = '';
		$_SESSION['erreurs']++;
	}
	
	else if($mail_result == 'exists')
	{
		$_SESSION['mail_info'] = '<span class="erreur">Le mail '.htmlspecialchars($mail, ENT_QUOTES).' est déjà pris, <a href="../contact.php">contactez-nous</a> si vous pensez à une erreur.</span><br/>';
		$_SESSION['form_mail'] = '';
		$_SESSION['erreurs']++;
	}
		
	else if($mail_result == 'ok')
	{
		$_SESSION['mail_info'] = '';
		$_SESSION['form_mail'] = $mail;
	}
	
	else if($mail_result == 'empty')
	{
		$_SESSION['mail_info'] = '<span class="erreur">Vous n\'avez pas entré de mail.</span><br/>';
		$_SESSION['form_mail'] = '';
		$_SESSION['erreurs']++;	
	}
}

else
{
	header('Location: ../index.php');
	exit();
}

//Mot de passe
if(isset($_POST['mdp']))
{
	$mdp = trim($_POST['mdp']);
	$mdp_result = checkmdp($mdp, '');
	if($mdp_result == 'tooshort')
	{
		$_SESSION['mdp_info'] = '<span class="erreur">Le mot de passe entré est trop court, changez-en pour un plus long (minimum 4 caractères).</span><br/>';
		$_SESSION['form_mdp'] = '';
		$_SESSION['erreurs']++;
	}
	
	else if($mdp_result == 'toolong')
	{
		$_SESSION['mdp_info'] = '<span class="erreur">Le mot de passe entré est trop long, changez-en pour un plus court. (maximum 50 caractères)</span><br/>';
		$_SESSION['form_mdp'] = '';
		$_SESSION['erreurs']++;
	}
	
	else if($mdp_result == 'nofigure')
	{
		$_SESSION['mdp_info'] = '<span class="erreur">Votre mot de passe doit contenir au moins un chiffre.</span><br/>';
		$_SESSION['form_mdp'] = '';
		$_SESSION['erreurs']++;
	}
		
	else if($mdp_result == 'noupcap')
	{
		$_SESSION['mdp_info'] = '<span class="erreur">Votre mot de passe doit contenir au moins une majuscule.</span><br/>';
		$_SESSION['form_mdp'] = '';
		$_SESSION['erreurs']++;
	}
		
	else if($mdp_result == 'ok')
	{
		$_SESSION['mdp_info'] = '';
		$_SESSION['form_mdp'] = $mdp;
	}
	
	else if($mdp_result == 'empty')
	{
		$_SESSION['mdp_info'] = '<span class="erreur">Vous n\'avez pas entré de mot de passe.</span><br/>';
		$_SESSION['form_mdp'] = '';
		$_SESSION['erreurs']++;

	}
}

else
{
	header('Location: ../index.php');
	exit();
}

//Mot de passe suite
if(isset($_POST['mdp_verif']))
{
	$mdp_verif = trim($_POST['mdp_verif']);
	$mdp_verif_result = checkmdpS($mdp_verif, $mdp);
	if($mdp_verif_result == 'different')
	{
		$_SESSION['mdp_verif_info'] = '<span class="erreur">Le mot de passe de vérification diffère du mot de passe.</span><br/>';
		$_SESSION['form_mdp_verif'] = '';
		$_SESSION['erreurs']++;
		if(isset($_SESSION['form_mdp'])) unset($_SESSION['form_mdp']);
	}
	
	else
	{
		if($mdp_verif_result == 'ok')
		{
			$_SESSION['form_mdp_verif'] = $mdp_verif;
			$_SESSION['mdp_verif_info'] = '';
		}
		
		else
		{
			$_SESSION['mdp_verif_info'] = str_replace('passe', 'passe de vérification', $_SESSION['mdp_info']);
			$_SESSION['form_mdp_verif'] = '';
			$_SESSION['erreurs']++;
		}
	}
}

else
{
	header('Location: ../index.php');
	exit();
}



//Sexe
if(isset($_POST['sexe']))
{
	$sexe = trim($_POST['sexe']);
	$sexe_result = checkSexe($sexe);
    
    if($sexe_result == 'ok')
		{
			$_SESSION['sexe_info'] = '';
			$_SESSION['form_sexe'] = $sexe;
		}
	
}
else
	{
		$_SESSION['sexe_info'] = '<span class="erreur">Vous n\'avez pas coché le sexe.</span><br/>';
		$_SESSION['form_sexe'] = '';
		$_SESSION['erreurs']++;	
	}


//date de naissance
/*if(isset($_POST['date_naissance']))
{
	$date_naissance = trim($_POST['date_naissance']);
	$date_naissance_result = birthdate($date_naissance);
	if($date_naissance_result == 'format')
	{
		$_SESSION['date_naissance_info'] = '<span class="erreur">Date de naissance au mauvais format ou invalide.</span><br/>';
		$_SESSION['form_date_naissance'] = '';
		$_SESSION['erreurs']++;
	}
	
	else if($date_naissance_result == 'tooyoung')
	{
		$_SESSION['date_naissance_info'] = '<span class="erreur">Agagagougougou areuh ? (Vous êtes trop jeune pour vous inscrire ici.)</span><br/>';
		$_SESSION['form_date_naissance'] = '';
		$_SESSION['erreurs']++;
	}
		
	else if($date_naissance_result == 'tooold')
	{
		$_SESSION['date_naissance_info'] = '<span class="erreur">Plus de 135 ans ? Mouais...</span><br/>';
		$_SESSION['form_date_naissance'] = '';
		$_SESSION['erreurs']++;
	}
		
	else if($date_naissance_result == 'invalid')
	{
		$_SESSION['date_naissance_info'] = '<span class="erreur">Le '.htmlspecialchars($date_naissance, ENT_QUOTES).' n\'existe pas.</span><br/>';
		$_SESSION['form_date_naissance'] = '';
		$_SESSION['erreurs']++;
	}
		
	else if($date_naissance_result == 'ok')
	{
		$_SESSION['date_naissance_info'] = '';
		$_SESSION['form_date_naissance'] = $date_naissance;
	}
	
	else if($date_naissance_result == 'empty')
	{
		$_SESSION['date_naissance_info'] = '<span class="erreur">Vous n\'avez pas entré de date de naissance.</span><br/>';
		$_SESSION['form_date_naissance'] = '';
		$_SESSION['erreurs']++;
	}
}

else
{
	header('Location: ../index.php');
	exit();
}*/


//Club
if(isset($_POST['club']))
{
	$club = trim($_POST['club']);
	$club_result = checkClub($club);
    
    if($club_result == 'ok')
		{
			$_SESSION['club_info'] = '';
			$_SESSION['form_club'] = $club;
		}
	else if($club_result == 'empty')
	{
		$_SESSION['club_info'] = '<span class="erreur">Vous n\'avez pas selectionné de club.</span><br/>';
		$_SESSION['form_club'] = '';
		$_SESSION['erreurs']++;	
	}
}

//Clef
if(isset($_POST['clef']))
{
	$clef = trim($_POST['clef']);
	$clef_result = checkclef($clef);

    if($clef_result == 'different')
		{
		$_SESSION['clef_info'] = '<span class="erreur">La clef n\' est pas correcte.</span><br/>';
		$_SESSION['form_clef'] = '';
		$_SESSION['erreurs']++;	
		}    
    
    
    else if($clef_result == 'ok')
		{
			$_SESSION['clef_info'] = '';
                if ($_POST['clef'] == '*********') { $groupe='QG'; }
		}
    
	else if($clef_result == 'empty')
	   {
		$_SESSION['clef_info'] = '<span class="erreur">La clef n\est pas renseignée.</span><br/>';
		$_SESSION['form_clef'] = '';
		$_SESSION['erreurs']++;	
	   }
}

//Avatar

    //Vérification de l'avatar :
    if (!empty($_FILES['avatar']['size']))
    {
        //On définit les variables :
        $extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png', 'bmp' ); //Liste des extensions valides
        
        if ($_FILES['avatar']['error'] > 0)
        {
               $_SESSION['avatar_info'] = "Erreur lors du transfert de l'avatar : ";
		       $_SESSION['erreurs']++;
        }
        
        $extension_upload = strtolower(substr(  strrchr($_FILES['avatar']['name'], '.')  ,1));
        if (!in_array($extension_upload,$extensions_valides) )
        {
                $_SESSION['avatar_info'] = "Extension de l'avatar incorrecte";
                $_SESSION['erreurs']++;
        }
    }



unset($_SESSION['club']);
unset($_SESSION['clef']);




//captcha
/*if($_POST['captcha'] == $_SESSION['captcha'] && isset($_POST['captcha']) && isset($_SESSION['captcha']))
{
	$_SESSION['captcha_info'] = '';
}

else
{
	$_SESSION['captcha_info'] = '<span class="erreur">Vous n\'avez pas recopié correctement le contenu de l\'image.</span><br/>';
	$_SESSION['erreurs']++;
}

unset($_SESSION['reponse1'], $_SESSION['reponse2'], $_SESSION['reponse3']);
unset($_SESSION['captcha']);*/

/*************Fin étude******************/
?>

<?php
include('.././includes/haut.php'); //contient le doctype, et head.
include('.././includes/col_gauche.php');

?>

		
    <div class= "main">
            <div class="container">
            
<!--Test des erreurs et envoi-->

<?php
                          $sqlbug = '';
			if($_SESSION['erreurs'] == 0)
			{  
                
                $nomavatar=(!empty($_FILES['avatar']['size']))?move_avatar($_FILES['avatar']):''; // On déplace le fichier de l'avatar
                
				$insertion = $bdd->prepare( 'INSERT INTO  membres (membre_pseudo, membre_mdp, membre_mail,membre_inscription, membre_sexe, membre_club, membre_avatar, membre_banni, membre_statut, membre_groupe)   VALUES(:membre_pseudo, :membre_mdp, :membre_mail, :membre_inscription, :membre_sexe, :membre_club, :membre_avatar, :membre_banni, :membre_statut, :membre_groupe)');

$insertion->execute(array(
	'membre_pseudo' => $pseudo,
	'membre_mdp' => md5($mdp),
	'membre_mail' => $mail,
	'membre_inscription' => time(),
	'membre_sexe' => $sexe,
	'membre_club' => $club,
    'membre_avatar' => $nomavatar,
    'membre_banni' => '0' ,
    'membre_statut' => 'MEMBRE' ,
    'membre_groupe' => $groupe
	));
				
				if($insertion)
				{
					$queries++;
					vidersession();
					$_SESSION['inscrit'] = $pseudo;
					/*informe qu'il s'est déjà inscrit s'il actualise, si son navigateur
					bugue avant l'affichage de la page et qu'il recharge la page, etc.*/
				?>
			<h1>Inscription validée !</h1>
			<p>Nous vous remercions de vous être inscrit sur notre site, votre inscription a été validée !<br/>
			Vous pouvez vous connecter avec vos identifiants <a href="<?php echo ROOTPATH; ?>/index.php">ici</a>.
			</p><br/>
            <img class= "hidden-xs" src="<?php echo ROOTPATH ;?>/img/Euro_2016/Mascotte/genou.jpg" style="height:500px;" >    
				<?php
				}
				
				else
				{
					if(stripos(mysqli_error($bdd), $_SESSION['form_pseudo']) !== FALSE) // recherche du pseudo
					{
						unset($_SESSION['form_pseudo']);
						$_SESSION['pseudo_info'] = '<span class="erreur">Le pseudo '.htmlspecialchars($pseudo, ENT_QUOTES).' est déjà pris, choisissez-en un autre.</span><br/>';
						$_SESSION['erreurs']++;
					}
					
					if(stripos(mysqli_error($bdd), $_SESSION['form_mail']) !== FALSE) //recherche du mail
					{
						unset($_SESSION['form_mail']);
						unset($_SESSION['form_mail_verif']);
						$_SESSION['mail_info'] = '<span class="erreur">Le mail '.htmlspecialchars($mail, ENT_QUOTES).' est déjà pris, <a href="../contact.php">contactez-nous</a> si vous pensez à une erreur.</span><br/>';
						$_SESSION['mail_verif_info'] = str_replace('mail', 'mail de vérification', $_SESSION['mail_info']);
						$_SESSION['erreurs']++;
						$_SESSION['erreurs']++;
					}
					
					if($_SESSION['erreurs'] == 0)
					{
						$sqlbug = true; //plantage SQL.
						$_SESSION['erreurs']++;
					}
				}
		   }
	
?>
            
<?php
			if(isset ($_SESSION['erreurs']) && $_SESSION['erreurs'] > 0)
			{
				if($_SESSION['erreurs'] == 1) $_SESSION['nb_erreurs'] = '<span class="erreur">Il y a eu 1 erreur.</span><br/>';
				else $_SESSION['nb_erreurs'] = '<span class="erreur">Il y a eu '.$_SESSION['erreurs'].' erreurs.</span><br/>';
			?>
			<h1>Inscription non validée.</h1>
			<p>Vous avez rempli le formulaire d'inscription du site et nous vous en remercions, cependant, nous n'avons
			pas pu valider votre inscription, en voici les raisons :<br/>
			<?php
				echo $_SESSION['nb_erreurs'];
                echo $_SESSION['pseudo_info'];
				echo $_SESSION['mdp_info'];
				echo $_SESSION['mdp_verif_info'];
				echo $_SESSION['mail_info'];
		        echo $_SESSION['sexe_info'];
				/*echo $_SESSION['date_naissance_info'];*/
				echo $_SESSION['club_info'];
				echo $_SESSION['clef_info'];
				
				if($sqlbug !== true)
				{
			?>
			Nous vous proposons donc de revenir à la page précédente pour corriger les erreurs.</p>
			<div class="center"><a href="inscription.php">Revenir à la page d'inscription</a></div>
			<?php
				}
				
				else
				{
			?>
			Une erreur est survenue dans la base de données, votre formulaire semble ne pas contenir d'erreurs, donc
			il est possible que le problème vienne de notre côté, réessayez de vous inscrire ou contactez-nous.</p>
			
			<div class="center"><a href="inscription.php">Retenter une inscription</a> - <a href="../contact.php">Contactez-nous</a></div>
			<?php
				}
			}
			?>
		</div>
</div>


		<?php
		include('../includes/bas.php');
		?>

