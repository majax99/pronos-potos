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

$titre = 'Règlement';

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
    
<h1 class="text-center"> Règlement du site </h1><br>
<div class="alert alert-success">
<h3> Pronostics </h3><br>   
<ul>
<li>Choisissez une journée et indiquez les résultats de chaque match</li>
<li>Indiquez sur quel match vous mettez le bonus de la journée </li>
<li>Les pronostics peuvent être fait jusqu'au commencement du match et peuvent être modifiés plusieurs fois</li>
<li>Le classement sera mis à jour après chaque match.</li>
<li>Le match bonus doit être indiqué avant le début du premier match de la journée</li>
</ul><br>
</div>  

<div class="alert alert-info">
<h3> Attribution des points </h3><br>
<ul>
  <li>Les points sont attribués en fonction de la côte</li>
  <li>Pour un bon résultat, vous gagnez 10 X la côte en nombre de points</li>
  <li>Si vous avez indiquez le bonus pour le match, vous gagnez 50 X la côte au lieu de 10 </strong></li>
</ul><br>

</div>   
    
<div class="alert alert-warning">   
<h3> Profil </h3><br>  
<ul>
  <li> Le pseudo ne peut être modifié, il faut demander à l'administrateur si vous souhaitez le faire.</li>
</ul><br>
</div>
    
</div>
</div>

    <!-- END # MODAL LOGIN -->
		
<?php
include('../includes/bas.php');
?>
  