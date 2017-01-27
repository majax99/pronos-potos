<?php
/*

Suppression des individus (réserver à l'administrateur)

*/

session_start();
header('Content-type: text/html; charset=utf-8');
include('.././includes/config.php');

/********Actualisation de la session...**********/

include('.././includes/fonctions.php');
actualiser_session();


/********Fin actualisation de session...**********/


/* Si le membre n'est pas administrateur, on le renvoie sur l'index */
if(!isset($_SESSION['membre_id']) || $_SESSION['membre_statut'] != 'ADMINISTRATEUR')
{
	header('Location: '.ROOTPATH.'/index.php');
	exit();
}


/********Entête et titre de page*********/

/*$titre = 'Inscription';*/

include('.././includes/haut.php'); //contient le doctype, et head.

/**********Fin entête et titre***********/
?>

<?php
include('.././includes/col_gauche.php');
?>

<?php
$id=0;

if(!empty($_GET['id'])){
    $id=$_REQUEST['id'];
    }


if(!empty($_POST)){
        $id= $_POST['id'];
    
                    $req = $bdd->prepare('DELETE FROM membres  WHERE membre_id = :id ');
                    $req->execute(array('id' => $id));
                    $req->closeCursor();
    
echo '
<div class="main">
<h2> L\'individu a bien été supprimé </h2><br>
<p>Cliquez <a href= "'.ROOTPATH.'/index.php">ici</a> pour revenir à la page d\'accueil ou <a href= "'.ROOTPATH.'/admin/modif_user.php">ici</a> pour revenir à la modification des profils</p>
</div>';

}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
else {
?>

<div class="main">
    
<div class="container">
     

<div class="span10 offset1">

<div class="row">

<h3>Supprimer un membre</h3>

</div>

                     
                    <form class="form-horizontal" action="delete.php" method="post">
                      <input type="hidden" name="id" value="<?php echo $id;?>"/>
Etes-vous sûr de vouloir supprimer cet individu (id=<?php echo $id;?>) ? 

<div class= "row"><br/>
                          <button type="submit" class="btn btn-success col-xs-1">Oui</button>
                          <a class="btn btn-danger col-xs-offset-1 col-xs-1" href="<?php echo ROOTPATH ; ?>/admin/modif_user.php"></span>Non</a>
</div>

                    </form>
</div>

                 
</div>
<!-- /container -->    
    
</div>

<?php
     }
      
include('../includes/bas.php');
?>