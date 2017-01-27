<?php
/*

Profil des membres du sites ayant fait des pronostics

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

$titre = 'Visualisation des profils';

include('.././includes/haut.php'); //contient le doctype, et head.

/**********Fin entête et titre***********/
?>

		<?php
		include('.././includes/col_gauche.php');

//requête pour récupérer les infos du membre
$profil = $bdd->prepare('SELECT * FROM membres WHERE membre_pseudo = :pseudo ');
$profil->execute(array('pseudo' => $_GET['pseudo']));
$profil = $profil->fetch(PDO::FETCH_ASSOC);
$photo_profil = (!empty($profil['membre_avatar']))? '<img alt="Pas d\'avatar" src="'.ROOTPATH.'/img/avatars/'.$profil['membre_avatar'].'" class="img-circle img-responsive">' : '';

if(!isset($profil['membre_id']))
{
	header('Location: '.ROOTPATH.'/index.php');
	exit();
}

		?>


        <!-- Corps de page
    ================================================== -->

    <div class= "main" style ="background-image: linear-gradient(rgb(255, 255, 255), rgb(12, 33, 97));height : 100%;">

<div class="row">
      <div class="col-md-5  toppad  pull-right col-md-offset-3 ">
      </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad" >
   
   
          <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title"><?php echo $profil['membre_pseudo']?></h3>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-3 col-lg-3 " align="center"> <?php echo $photo_profil ; ?> </div>
                

                <div class=" col-md-9 col-lg-9 "> 
                  <table class="table table-user-information">
                    <tbody>
                      <tr>
                        <td>Date d'inscription:</td>
                        <td><?php echo date('d/m/Y',$profil['membre_inscription']); ?></td>
                      </tr>
        
                         <tr>
                             <tr>
                        <td>Sexe</td>
                        <td><?php echo $profil['membre_sexe']; ?></td>
                      </tr>
                        <tr>
                        <td>Club de coeur</td>
                        <td><?php echo $profil['membre_club']; ?></td>
                      </tr>
                      <tr>
                        <td>Statut</td>
                        <td><?php echo $profil['membre_statut']; ?></td>
                      </tr>
         
                      </tr>
                     
                    </tbody>
                  </table>
   
                </div>
              </div>
            </div>
                 <div class="panel-footer">
                        <a data-original-title="Envoyer un message" data-toggle="tooltip" type="button" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-envelope"></i></a>
                    </div>
            
          </div>
        </div>
      </div>



<?php
include('../includes/bas.php');
?>



