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

$titre = 'Verification des pronostics (phase finale)';

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
$id= 124;
$id_membre = 2;
$match_nom = "France - Islande";
$match_phase = "quar"    ;
$resultat1=  "1";  
$resultat2=  "1"; 
$res_prono = "N";   


                $req = $bdd->prepare('UPDATE pronostic_euro SET 
                prono_membre_id = :id_membre, prono_match_name= :match ,prono_resultat1= :resultat1 ,prono_resultat2= :resultat2, prono_phase = :phase, resultat_prono = :res WHERE prono_id = :id');
                $req->execute(array(
	                   'id' => $id,
                       'id_membre' => $id,
	                   'match' => $match_nom,
                       'phase' => $match_phase,
	                   'resultat1' => $resultat1,
                       'resultat2' => $resultat2,
	                   'res' => $res_prono));
                    
                $req->closeCursor();

            
           
    echo '<h2>les pronostics sont validés</h2>
    <h5> Cliquez <a href = "'.ROOTPATH.'/accueil.php" >ici</a> pour revenir à l\'accueil</h5><br>
            
    <img class="hidden-xs" src="'.ROOTPATH.'/img/Euro_2016/Mascotte/tableau.png" >';
           
      ?>
        </div>
      </div> <!-- FIN DU MAIN -->


    <!-- END # MODAL LOGIN -->
		
<?php
include('../includes/bas.php');
?>
