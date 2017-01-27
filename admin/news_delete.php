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
    
                    $req = $bdd->prepare('DELETE FROM news_site  WHERE news_id = :id ');
                    $req->execute(array('id' => $id));
                    $req->closeCursor();
    
echo '
<div class="main">
<h2> La news a bien été supprimée </h2><br>
<p>Cliquez <a href= "'.ROOTPATH.'/index.php">ici</a> pour revenir à la page d\'accueil ou <a href= "'.ROOTPATH.'/admin/news.php">ici</a> pour revenir à la modification des news.</p>
</div>';

}


else {
?>

<div class="main">
    
<div class="container">
     

<div class="span10 offset1">

<div class="row">

<h3>Supprimer une news</h3>

</div>

                     
                    <form class="form-horizontal" action="news_delete.php" method="post">
                      <input type="hidden" name="id" value="<?php echo $id;?>"/>
Etes-vous sûr de vouloir supprimer cette news (id_news=<?php echo $id;?>) ? 

<div class= "row"><br/>
                          <button type="submit" class="btn btn-success col-xs-1">Oui</button>
                          <a class="btn btn-danger col-xs-offset-1 col-xs-1" href="<?php echo ROOTPATH ; ?>/admin/news.php"></span>Non</a>
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