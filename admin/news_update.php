<?php
/*

Modification des profils des utilisateurs ou suppression des utilisateurs (réserver à l'administrateur)

*/

session_start();
header('Content-type: text/html; charset=utf-8');
include('.././includes/config.php');

/********Actualisation de la session...**********/

include('.././includes/fonctions.php');
actualiser_session();


/********Fin actualisation de session...**********/


/* Si le membre n'est pas administrateur, on le renvoie sur l'index */
if(!isset($_SESSION['membre_id']) || $_SESSION['membre_statut'] != 'ADMINISTRATEUR' )
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


        
<div class="main">

<div class="container">
	<div class="row">
		
        <h1 class="text-center">Modifier une news</h1><br><br>

<?php 
$arrayType = ['MAJ cote', 'Nouvelles fonctionnalités', 'Information', 'Rappel', 'Autre'];   
$id=0;
	if ( !empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}
        
if (empty($_POST)) {      
                $data = $bdd->prepare('SELECT * FROM news_site WHERE news_id = :id ');
                $data->execute(array('id' => $id ));
                $data = $data->fetch(PDO::FETCH_ASSOC);
        
                $type = $data['news_type'];
        
?>
        
        
<form class="form-horizontal" method="post" action="news_update.php?id=<?php echo $id ;?>">
<fieldset>

<!-- Form Name -->
<legend>Ajout de news</legend>

<!-- Textarea -->
<div class="form-group">
  <label class="col-md-4 control-label" for="textarea">Indiquer le texte à ajouter</label>
  <div class="col-md-4">                     
    <textarea class="form-control" id="textarea" name="textModif"><?php echo $data['news_texte'] ; ?></textarea>
  </div>
</div>

<!-- Select Basic -->
<div class="form-group">
  <label class="col-md-4 control-label" for="selectbasic">Type de news</label>
  <div class="col-md-4">

<?php
       echo "<select name='typeModif' class='form-control ' style='width:auto' >
 
     <option >Sélectionnez</option>"  ;
  
     foreach( $arrayType as $typeNews ) {
         
          if ($typeNews == $type) {
              echo "<option value='$typeNews' selected>$typeNews</option>"  ;
          }
         
         else {
          echo "<option value='$typeNews'>$typeNews</option>"  ;
          }
                                        }
?>
    </select>
  </div>
</div>
<br><br>
<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="singlebutton"></label>
  <div class="col-md-4 col-xs-12">
    <button id="singlebutton" name="formAjout" class="btn btn-primary">Valider</button>
  </div>
</div>

</fieldset>
</form>

 
<?php
    
}    
else if (isset($_POST['textModif']) && isset($_POST['typeModif']) && $_POST['typeModif'] != 'Sélectionnez') {   
    
    $req = $bdd->prepare('UPDATE news_site SET news_texte = :texte, news_type = :type, news_date_MAJ = NOW()    WHERE news_id = :id ');
        $req->execute(array('texte' => $_POST['textModif'],
                            'type' => $_POST['typeModif'],
                            'id' => $id));
        $req->closeCursor();
    
    echo '<h3> La modification a été effectuée </h3><br>';
    
    echo 'Cliquez <a href="'.ROOTPATH.'/admin/news.php"> ici </a> pour revenir à la page des news ';
} 
?>
        
        
            <br><br>

        </div>
    
    
    

    
    <!-- END # MODAL -->
 </div>
</div>

<?php
include('../includes/bas.php');
?>