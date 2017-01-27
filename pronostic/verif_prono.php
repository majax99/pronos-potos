<?php
/*
Permet de vérifier que les pronostics sont valides(phase finale)

*/

session_start();
header('Content-type: text/html; charset=utf-8');
include('.././includes/config.php');

/********Actualisation de la session...**********/

include('.././includes/fonctions.php');
actualiser_session();


/* Si le membre n'est pas connecté, on le renvoie sur l'index */
if(!isset($_SESSION['membre_id']))
{
	header('Location: '.ROOTPATH.'/index.php');
	exit();
}

/********Fin actualisation de session...**********/

/********Entête et titre de page*********/

$titre = 'Vérification des pronostics (poules)';

include('.././includes/haut.php'); //contient le doctype, et head.

/**********Fin entête et titre***********/
?>

<?php
include('.././includes/col_gauche.php');
?>


        
<div class="main">

        <!-- Corps de page
    ================================================== -->
  <div class="container-fluid">

<?php  
  
$_SESSION['erreur_prono']=0;
$variables = $_POST;

// Boucle qui va insérer les données dans la table pronostic_euro   
      while (list($cle,$valeur) = each($variables))
        {

$$cle=$valeur;       
$match_nom=substr($cle,0,strlen($cle)-2);
$match_nom = str_replace('_', ' ', $match_nom);
          
$pos = strpos($match_nom,'-');
$equipe1 = substr($match_nom,0,$pos - 1); // Nom de l'équipe 1
$equipe2 = substr($match_nom,$pos+2,strlen($match_nom)-($pos+1)); // Nom de l'équipe 2
          
$match_groupe= substr($cle,strlen($cle)-1,1);
$chiffre=substr($cle,strlen($cle)-2,1);

/** CHECK DES ERREURS DANS LES PRONOSTICS **/  
$prono = $$cle;
$prono_result = checkProno($prono);

$cle = str_replace('_', ' ', $cle);
$form_prono = $cle;
$equipe= ($chiffre==1) ? (htmlspecialchars($equipe1, ENT_QUOTES)) : (htmlspecialchars($equipe2, ENT_QUOTES) );
$prono_info = ''.$cle.'info';
          
    if($prono_result == 'KOchiffre')
		{
		$_SESSION[$prono_info] = '<span class="erreur">La valeur renseignée pour l\'équipe <strong>'.$equipe.' </strong>le match <strong> '.htmlspecialchars($match_nom, ENT_QUOTES). '</strong> n\'est pas un chiffre.</span><br/>';
		$_SESSION[$form_prono] = '';
		$_SESSION['erreur_prono']++;	
		}
    
    else if($prono_result == 'empty')
		{
		$_SESSION[$prono_info] = '<span class="erreur">Pas de score renseigné pour l\'équipe <strong>'.$equipe.' </strong> pour le match <strong> '.htmlspecialchars($match_nom, ENT_QUOTES). '</strong>.<br/>';
		$_SESSION[$form_prono] = '';
		$_SESSION['erreur_prono']++;	
		}
    
    else if($prono_result == 'ok')
		{
		$_SESSION[$prono_info] = '';
		$_SESSION[$form_prono] = $prono;
		}
 
        
          
          
if(isset ($_SESSION['erreur_prono']) && $_SESSION['erreur_prono'] > 0) 
    {
        if ($_SESSION['erreur_prono'] == 1 && $_SESSION[$form_prono] == '' ){echo '<h1> Pronostics non validés. </h1><br><h3> Les erreurs sont les suivantes : </h3>' ; }
            echo $_SESSION[$prono_info];
    }
            
       }
if(isset ($_SESSION['erreur_prono']) && $_SESSION['erreur_prono'] > 0) {
?>
     <br><br>
			<div class="text-center"><a href="pronostic.php" data-original-title="Revenir aux pronostics"  type="button" class="btn btn-danger">Revenir aux pronostics</a></div><br>

 <?php
}
if($_SESSION['erreur_prono'] == 0)
          {
              $variables = $_POST;
      while (list($cle,$valeur) = each($variables))
            {

          $$cle=$valeur;       
          $match_nom=substr($cle,0,strlen($cle)-2);
          $match_nom = str_replace('_', ' ', $match_nom);
          $match_groupe= substr($cle,strlen($cle)-1,1);
          $chiffre=substr($cle,strlen($cle)-2,1);
          $phase = 'poules';
            
//On modifie la table
            if ($chiffre == 1)

            {
                
                $insertion = $bdd->prepare( 'INSERT INTO pronostic_euro (prono_membre_id, prono_match_name, prono_groupe,prono_resultat1,prono_phase)  
                VALUES(:id, :match, :groupe, :resultat,:phase)');

                $insertion->execute(array(
	                   'id' => $_SESSION['membre_id'],
	                   'match' => $match_nom,
	                   'groupe' => $match_groupe,
                       'phase' => $phase,
	                   'resultat' => $$cle));
                $insertion->closeCursor();
            } 
            else if ($chiffre == 2)
            {
                $req = $bdd->prepare('UPDATE pronostic_euro SET 
                prono_resultat2=  :resultat , resultat_prono= ( 
                        CASE 
                        WHEN prono_resultat1 > prono_resultat2   THEN "1"
                        WHEN prono_resultat1 = prono_resultat2 THEN "N"
                        WHEN prono_resultat1 < prono_resultat2 THEN "2"
                        ELSE ""
                        END)   WHERE prono_match_name = :match  AND prono_membre_id= :id');
                $req->execute(array('resultat' => $$cle,
                                    'match' => $match_nom,
                                    'id' => $_SESSION['membre_id']));
                $req->closeCursor();

            }
           }
    echo '<h2>les pronostics sont validés</h2>
    <h5> Cliquez <a href = "'.ROOTPATH.'/accueil.php" >ici</a> pour revenir à l\'accueil</h5><br>
    <img class="hidden-xs" src="'.ROOTPATH.'/img/Euro_2016/Mascotte/tableau.png" >';
        }   
      ?>
        </div>
      </div>


    <!-- END # MODAL LOGIN -->
		
<?php
include('../includes/bas.php');
?>
