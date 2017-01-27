<?php
/*
Règlement du site (pronostics, classement,...)
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

$titre = 'Statistiques';

include('.././includes/haut.php'); //contient le doctype, et head.

/**********Fin entête et titre***********/
?>

<?php
include('.././includes/col_gauche.php');
?>


        
<div class="main">

        <!-- Corps de page
    ================================================== -->
<div class="container">

<h1 class="text-center"> Le coin des statistiques</h1>

    
    
  <!--------------------------------------------------------------------------------------->
  <!------------------------          PROCHAIN MATCHS        ------------------------------>
  <!--------------------------------------------------------------------------------------->
       <div class="row" ><br>
        <div class="col-xs-12 col-md-5  toppad" >
         <div class="panel panel-info" >
            <div class="panel-heading">
              <h1 class="panel-title text-center">Statistiques globales</h1>
            </div>
            <div class="panel-body">

                    
<h5><strong> Plus grand nombre de points sur une journée</strong> </h5>
 <?php
    // PLUS GRAND NOMBRE DE POINT EN 1 JOURNEE 
    
 $point_journee = $bdd->query('SELECT round(sum(coalesce(points,0))) as somme,membre_pseudo,matchl1_journee
      FROM   v_classement_l1
      INNER JOIN membres ON pronol1_membre_id=membre_id     
       group by      membre_pseudo,matchl1_journee
           having somme = (
SELECT  max(somme) AS MAX_points 
FROM (SELECT round(sum(coalesce(points,0))) as somme,membre_pseudo,matchl1_journee
      FROM   v_classement_l1
      INNER JOIN membres ON pronol1_membre_id=membre_id 
      GROUP  BY matchl1_journee, membre_pseudo) T)
                                                 ');

    


                while ($donnees = $point_journee->fetch())
                { 
                    echo 'J'.$donnees["matchl1_journee"].' : '.$donnees["membre_pseudo"].' avec '.$donnees["somme"].' points <br>';
                }
    
$point_journee->closeCursor();
    ?>

                </div>
            </div>
          </div>   
           
           
                <div class="col-xs-12 col-md-5  toppad" >
         <div class="panel panel-info" >
            <div class="panel-heading">
              <h1 class="panel-title text-center">Mes statistiques</h1>
            </div>
            <div class="panel-body">

                    
<h5><strong> Plus grand nombre de points sur une journée</strong> </h5>
 <?php
    // PLUS GRAND NOMBRE DE POINT EN 1 JOURNEE 
    
 $point_journee = $bdd->query('SELECT round(sum(coalesce(points,0))) as somme,membre_pseudo,matchl1_journee
      FROM   v_classement_l1
      INNER JOIN membres ON pronol1_membre_id=membre_id     
       group by      membre_pseudo,matchl1_journee
           having somme = (
SELECT  max(somme) AS MAX_points 
FROM (SELECT round(sum(coalesce(points,0))) as somme,membre_pseudo,matchl1_journee
      FROM   v_classement_l1
      INNER JOIN membres ON pronol1_membre_id=membre_id 
      WHERE membre_id = '.$_SESSION['membre_id'].'
      GROUP  BY matchl1_journee, membre_pseudo) T)
                                                 ');

    


                while ($donnees = $point_journee->fetch())
                { 
                    echo 'J'.$donnees["matchl1_journee"].' avec '.$donnees["somme"].' points <br>';
                }
    
$point_journee->closeCursor();
    ?>

                </div>
            </div>
          </div>    
           
           
    </div> <!-- fin div du row -->
    
</div>
</div>

    <!-- END # MODAL LOGIN -->
		
<?php
include('../includes/bas.php');
?>
  