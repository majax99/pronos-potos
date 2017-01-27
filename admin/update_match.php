<?php
/*

MAJ des profils des utilisateurs

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


include('.././includes/haut.php'); //contient le doctype, et head.

/**********Fin entête et titre***********/
?>		

		<?php
include('.././includes/col_gauche.php');
		?>


<?php
  
        
$id=0;
	if ( !empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}
     
        if ( !empty($_POST)) {

            // on initialise nos erreurs
            $matchError = null;
            $dateError = null;

            // On assigne nos valeurs
            $match = $_POST['match'];
            $date_match = $_POST['date_match'];
            $cote1_match = $_POST['cote1_match'];
            $coteN_match = $_POST['coteN_match'];
            $cote2_match = $_POST['cote2_match'];
            


            // On verifie que les champs sont remplis
            $valid = true;

            // mise à jour des donnés
                if ($valid) {           
                    
        $req = $bdd->prepare('UPDATE match_ligue1 SET matchl1_name = :name_match, matchl1_date = :date_match, matchl1_cote1 = :cote1, matchl1_coteN = :coteN , matchl1_cote2 = :cote2   WHERE matchl1_id = :id ');
        $req->execute(array('name_match' => $match,
                            'date_match' => $date_match,
                            'cote1' => $cote1_match,
                            'coteN' => $coteN_match,
                            'cote2' => $cote2_match,
                            'id' => $id));
        $req->closeCursor();  
                    

                    
            } 
           }
    else {

                $data = $bdd->prepare('SELECT * FROM match_ligue1 WHERE matchl1_id = :id ');
                $data->execute(array('id' => $id ));
                $data = $data->fetch(PDO::FETCH_ASSOC);
        
                $match = $data['matchl1_name'];
                $date_match = $data['matchl1_date'];
                $cote1_match = $data['matchl1_cote1'];
                $coteN_match = $data['matchl1_coteN'];
                $cote2_match = $data['matchl1_cote2'];
         }
        
        ?>


     
<div class= "main">
<div class="container">

<div class="row">

<h3>Modifier les caractéristiques du match</h3>

</div>

            <form method="post" action="update_match.php?id=<?php echo $id ;?>">

<!-- Match -->              
<div class="control-group">
                    <label class="control-label">Match</label>

<div class="controls">
                        <input name="match" type="text"  placeholder="match" value="<?php echo!empty($match) ? $match : ''; ?>">
</div>

</div>
<br><br>   


<!-- Date du match -->  
<div class="control-group">
                    <label class="control-label">Date du match</label>

<div class="controls">
                        <input type="datetime" name="date_match" value="<?php echo!empty($date_match) ? $date_match : ''; ?>">
</div>

</div>
<br><br>   
                
                
<!-- cote1 du match -->  
<div class="control-group">
                    <label class="control-label">Cote1</label>

<div class="controls">
                        <input type="text" name="cote1_match" value="<?php echo!empty($cote1_match) ? $cote1_match : ''; ?>">
</div>

</div>
<br><br>  
     
 
<!-- coteN du match -->  
<div class="control-group">
                    <label class="control-label">CoteN</label>

<div class="controls">
                        <input type="text" name="coteN_match" value="<?php echo!empty($coteN_match) ? $coteN_match : ''; ?>">
</div>

</div>
<br><br>  
                
<!-- cote2 du match -->  
<div class="control-group">
                    <label class="control-label">Cote2</label>

<div class="controls">
                        <input type="text" name="cote2_match" value="<?php echo!empty($cote2_match) ? $cote2_match : ''; ?>">
</div>

</div>
<br><br>  

<div class="form-actions">
                    <input type="submit" class="btn btn-success" name="submit" value="submit">
                    <a class="btn" href="<?php echo ROOTPATH; ?>/admin/modif_match_ajax.php">Retour</a>
</div>

            </form>



</div>
    
    
    
  </div>
    
		<?php
		include('.././includes/bas.php'); 
		?>
