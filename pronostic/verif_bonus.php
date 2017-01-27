<?php
/*
Permet de vérifier que les bonus sont valides.

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

$titre = 'Vérification des bonus';

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
  
$_SESSION['erreur_bonus']=0;
$_SESSION['bonus_info'] = '';
// Boucle qui va insérer les données dans la table pronostic_euro   

          
    if($_POST['parcours'] == 'Sélectionnez' || $_POST['vainqueur'] == 'Sélectionnez' || $_POST['finaliste'] == 'Sélectionnez')
		{
		$_SESSION['bonus_info'] = '<span class="erreur">Un bonus n\'a pas été sélectionné</span><br/>';
		$_SESSION['erreur_bonus']++;	

		}
 
      
          
if(isset ($_SESSION['erreur_bonus']) && $_SESSION['erreur_bonus'] > 0) 
    {
        if ($_SESSION['erreur_bonus'] == 1  ){echo '<h1> Bonus non validés. </h1><br><h3> Les erreurs sont les suivantes : </h3>' ; }
            echo $_SESSION['bonus_info'];
    }
            
       
if(isset ($_SESSION['erreur_bonus']) && $_SESSION['erreur_bonus'] > 0) {
?>
     <br><br>
			<div class="text-center"><a href="pronostic.php" data-original-title="Revenir aux pronostics"  type="button" class="btn btn-danger">Revenir aux pronostics</a></div><br>

 <?php
}
if($_SESSION['erreur_bonus'] == 0)
          {
            $parcours = $_POST['parcours'];
            $vainqueur = $_POST['vainqueur'];
            $finaliste = $_POST['finaliste'];
    
            
//On modifie la table          
                
                $insertion = $bdd->prepare( 'INSERT INTO bonusp_euro (bonusP_membre_id, bonusP_pseudo, bonusP_France, bonusP_finaliste,bonusP_vainqueur, bonusCourse_France,bonusCourse_finaliste,bonusCourse_vainqueur)  
                VALUES(:id, :pseudo, :france, :finaliste, :vainqueur, :pFrance, :pFinaliste, :pVainqueur)');

                $insertion->execute(array(
	                   'id' => $_SESSION['membre_id'],
                       'pseudo' => $_SESSION['membre_pseudo'],
	                   'france' => $parcours,
	                   'finaliste' => $finaliste,
	                   'vainqueur' => $vainqueur,
                       'pFrance' => '1',
                       'pFinaliste' => '1',
                       'pVainqueur' => '1',));
                $insertion->closeCursor();

  

            
           
    echo '<h2>les bonus sont validés</h2><br/>
    <h5> Cliquez <a href = "'.ROOTPATH.'/pronostic/pronostic.php" >ici</a> pour pronostiquer les matchs.</h5><br>
    <img class="hidden-xs" src="'.ROOTPATH.'/img/Euro_2016/Mascotte/tableau.png" >';
        }   
      ?>
        </div>
      </div>


    <!-- END # MODAL LOGIN -->
		
<?php
include('../includes/bas.php');
?>
