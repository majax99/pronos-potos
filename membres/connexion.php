<?php

/*

Neoterranos & LkY

Page connexion.php



Permet de se connecter au site.



Quelques indications : (Utiliser l'outil de recherche et rechercher les mentions données)



Liste des fonctions :

--------------------------

Aucune fonction

--------------------------





Liste des informations/erreurs :

--------------------------

Membre qui essaie de se connecter alors qu'il l'est déjà

Vous êtes bien connecté

Erreur de mot de passe

Erreur de pseudo doublon (normalement impossible)

Pseudo inconnu

--------------------------

*/



session_start();

header('Content-type: text/html; charset=utf-8');

include('.././includes/config.php');



/********Actualisation de la session...**********/



include('.././includes/fonctions.php');

//actualiser_session();



/********Fin actualisation de session...**********/



if(isset($_SESSION['membre_id']))

{

	$informations = Array(/*Membre qui essaie de se connecter alors qu'il l'est déjà*/

					true,

					'Vous êtes déjà connecté',

					'Vous êtes déjà connecté avec le pseudo <span class="pseudo">'.htmlspecialchars($_SESSION['membre_pseudo'], ENT_QUOTES).'</span>.',

					' - <a href="'.ROOTPATH.'/membres/deconnexion.php">Se déconnecter</a>',

					ROOTPATH.'/accueil.php',

					5

					);

	

	require_once('../information.php');

	exit();

}





/********Entête et titre de page*********/



$titre = 'Connexion';



include('.././includes/haut.php'); //contient le doctype, et head.



/**********Fin entête et titre***********/

?>		



		<?php



if(!isset ($_POST['validate']) )

{

		?>



		

    <div class= "main">

            <div class="container">





			

			<form name="connexion" id="connexion" method="post" action="membres/connexion.php">

				<fieldset><legend>Connexion</legend>

					<label for="pseudo" class="float">Pseudo :</label> <input type="text" name="pseudo" id="pseudo" value="<?php if(isset($_SESSION['connexion_pseudo'])) echo $_SESSION['connexion_pseudo']; ?>"/><br/><br/>

					<label for="mdp" class="float">Password :</label> <input type="password" name="mdp" id="mdp"/><br/><br/>

					<input type="hidden" name="validate" id="validate" value="ok"/>

					<input type="checkbox" name="cookie" id="cookie"/> <label for="cookie">Me connecter automatiquement à mon prochain passage.</label><br/>

					<div class="center "><input type="submit" value="Connexion" /></div>

				</fieldset>

			</form>

			

			<h1>Options</h1>

			<p><a href="<?php echo ROOTPATH ?>/membres/inscription.php">Je ne suis pas inscrit !</a><br/>

			<a href="#">J'ai oublié mon mot de passe !</a>

			</p>

			<?php

}

			

			else

			{

                

            $result = $bdd->prepare('SELECT COUNT(membre_id) AS nbr, membre_id, membre_pseudo, membre_mdp FROM membres WHERE

				membre_pseudo = :pseudo GROUP BY membre_id');

            $result->execute(array('pseudo' => $_POST['pseudo'] ));

            $result = $result->fetch(PDO::FETCH_ASSOC);

				

				if($result['nbr'] == 1)

				{

					if(md5($_POST['mdp']) == $result['membre_mdp'])

					{  

						$_SESSION['membre_id'] = $result['membre_id'];

						$_SESSION['membre_pseudo'] = $result['membre_pseudo'];

						$_SESSION['membre_mdp'] = $result['membre_mdp'];

						

						

						if(isset($_POST['cookie']) && $_POST['cookie'] == '1')

						{

							setcookie('membre_id', $result['membre_id'], time()+365*24*3600,'/'); // on met le cookie à la racine

							setcookie('membre_mdp', $result['membre_mdp'], time()+365*24*3600,'/'); // on met le cookie à la racine

                            setcookie('membre_pseudo', $result['membre_pseudo'], time()+365*24*3600,'/'); // on met le cookie à la racine

						}

						

						/*$informations = Array(

										false,

										'Connexion réussie',

										'Vous êtes désormais connecté avec le pseudo <span class="pseudo">'.htmlspecialchars($_SESSION['membre_pseudo'], ENT_QUOTES).'</span>.',

										'',

										ROOTPATH.'/accueil.php',

										3

										);

						require_once('../information.php');

						exit();*/

                        

                           header('Location: '.ROOTPATH.'/accueil.php');

	                       exit();

					}

					

					else

					{

						$_SESSION['connexion_pseudo'] = $_POST['pseudo'];

						$informations = Array(/*Erreur de mot de passe*/

										true,

										'Mauvais mot de passe',

										'Vous avez fourni un mot de passe incorrect.',

										' - <a href="'.ROOTPATH.'/index.php">Index</a>',

										ROOTPATH.'/index.php',

										3

										);

						require_once('../information.php');

						exit();

					}

				}

				

				else if($result['nbr'] > 1)

				{

					$informations = Array(/*Erreur de pseudo doublon (normalement impossible)*/

									true,

									'Doublon',

									'Deux membres ou plus ont le même pseudo, contactez un administrateur pour régler le problème.',

									' - <a href="'.ROOTPATH.'/index.php">Index</a>',

									ROOTPATH.'/contact.php',

									3

									);

					require_once('../information.php');

					exit();

				}

				

				else

				{

					$informations = Array(/*Pseudo inconnu*/

									true,

									'Pseudo inconnu',

									'Le pseudo <span class="pseudo">'.htmlspecialchars($_POST['pseudo'], ENT_QUOTES).'</span> n\'existe pas dans notre base de données. Vous avez probablement fait une erreur.',

									' - <a href="'.ROOTPATH.'/index.php">Index</a>',

									ROOTPATH.'/index.php',

									5

									);

					require_once('../information.php');

					exit();

				}

			}

			?>			

		</div>

</div>

		<?php

		include('.././includes/bas.php');

		?>

