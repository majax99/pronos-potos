<?php
/*

Question de mi saison sur les clubs qui descendent et le meilleur buteur

*/

session_start();
header('Content-type: text/html; charset=utf-8');
include('.././includes/config.php');

/********Actualisation de la session...**********/

include('.././includes/fonctions.php');
actualiser_session();

/* On vérifie si la personne a déjà répondu au questionnaire */
 $questio_realise = $bdd->query('SELECT count(*) as nb  FROM questionnaire  WHERE question_membre = '.$_SESSION['membre_id'].' ');
 $questio_realise = $questio_realise->fetch(PDO::FETCH_ASSOC);
$questio = $questio_realise['nb'];

/* Si le membre n'est pas connecté, on le renvoie sur l'index */
if(!isset($_SESSION['membre_id']) || $questio > 0 )
{
	header('Location: '.ROOTPATH.'/pronostic/prono_ligue1.php');
	exit();
}
/********Fin actualisation de session...**********/

/********Entête et titre de page*********/

$titre = 'Inscription';

include('.././includes/haut.php'); //contient le doctype, et head.

/**********Fin entête et titre***********/
?>

<?php
include('.././includes/col_gauche.php');
?>

<?php 
if(!isset($_POST['equipe1']) || !isset($_POST['equipe2']) || !isset($_POST['equipe3']) || !isset($_POST['buteur']) || (isset($_POST['equipe1']) && $_POST['equipe1']== 'Sélectionnez') || (isset($_POST['equipe2']) && $_POST['equipe2']== 'Sélectionnez') || (isset($_POST['equipe3']) && $_POST['equipe3']== 'Sélectionnez') || (isset($_POST['buteur']) && $_POST['buteur']== 'Sélectionnez'))
{
?>       


        <!-- Corps de page
    ================================================== -->

    <div class= "main">
            <div class="container">
			<?php
      $arrayligue1 = array('Angers', 'Bastia', 'Bordeaux' , 'Caen' , 'Dijon', 'Guingamp', 'Lille' , 'Lorient' ,'Lyon', 'Marseille', 'Metz', 'Monaco' , 'Montpellier' ,'Nancy', 'Nantes', 'Nice'  ,'PSG', 'Rennes', 'Saint Etienne' , 'Toulouse');    
                
      $arraybuteur = array('Edinson Cavani','Alexandre Lacazette' ,'Radamel Falcao' ,'Bafetimbi Gomis' , 'Alasanne Plea' , 'Ivan Santini' , 'Mario Balotelli ')
			?>
  
    
 			
<form class="form-horizontal" action="question_MiSaison.php" method="post" name="question_misaison" enctype="multipart/form-data">
<fieldset>
<!-- Form Name -->
<legend><h2  class="text-center">Questionnaire à mi-saison</h2>
</legend>
<!-- Select Basic -->
<h4 class="text-center"> Qui terminera dans les 3 derniers à la fin de la saison 2016-2017 ?<i>(pas besoin de l'ordre exact)</i> </h4>
<div class="form-group">
  <label class="col-md-5 control-label">Equipe 1</label>
  <div class="col-md-5">
<?php 
       echo "<select name='equipe1' class='form-control ' style='width:auto' >
 
     <option >Sélectionnez</option>"  ;
  
     foreach( $arrayligue1 as $ligue1 ) {
         
          if ($ligue1 == $club) {
              echo "<option value='$ligue1' selected>$ligue1</option>"  ;
          }
         
         else {
          echo "<option value='$ligue1'>$ligue1</option>"  ;
          }
                                        }
?>
    </select>
  </div>
</div>
    
<div class="form-group">
  <label class="col-md-5 control-label">Equipe 2</label>
  <div class="col-md-5">
<?php 
       echo "<select name='equipe2' class='form-control ' style='width:auto' >
 
     <option >Sélectionnez</option>"  ;
  
     foreach( $arrayligue1 as $ligue1 ) {
         
          if ($ligue1 == $club) {
              echo "<option value='$ligue1' selected>$ligue1</option>"  ;
          }
         
         else {
          echo "<option value='$ligue1'>$ligue1</option>"  ;
          }
                                        }
?>
    </select>
  </div>
</div>
                
<div class="form-group">
  <label class="col-md-5 control-label">Equipe 3</label>
  <div class="col-md-5">
<?php 
       echo "<select name='equipe3' class='form-control ' style='width:auto' >
 
     <option >Sélectionnez</option>"  ;
  
     foreach( $arrayligue1 as $ligue1 ) {
         
          if ($ligue1 == $club) {
              echo "<option value='$ligue1' selected>$ligue1</option>"  ;
          }
         
         else {
          echo "<option value='$ligue1'>$ligue1</option>"  ;
          }
                                        }
?>
    </select>
  </div>
</div>
<h5 class="text-center" style="color:green;font-size:0.7em">Chaque équipe trouvée vaut 100 points et 500 points si vous trouvez les 3 équipes</h5>
<br><br>
<!-- Select Basic -->
<h4 class="text-center"> Qui terminera meilleur buteur à la fin de la saison ?</i></h4>
<div class="form-group">
  <label class="col-md-5 control-label">Meilleur buteur</label>
  <div class="col-md-5">
<?php 
       echo "<select name='buteur' class='form-control ' style='width:auto' >
 
     <option >Sélectionnez</option>"  ;
  
     foreach( $arraybuteur as $buteur ) {
          echo "<option value='$buteur'>$buteur</option>" ;
                                        }
?>
    </select>
  </div>
</div>
<h5 class="text-center" style="color:green;font-size:0.7em">200 points si vous trouvez le meilleur buteur</h5>
<br>
<h5 class="text-center" style="color:red;font-size:0.8em">ATTENTION : une fois le questionnaire validé, vous ne pourrez pas le refaire.</h5>
</fieldset>
<div class="text-center" style="padding:20px;"><input class=" btn btn-primary" type="submit" value="Valider" /></div>
</form>
    </div>
</div>

<?php 
}
else {
$equipe1 = $_POST['equipe1'];
$equipe2 = $_POST['equipe2'];
$equipe3 = $_POST['equipe3'];
$buteur = $_POST['buteur'];
$id = $_SESSION['membre_id'];

				$insertion = $bdd->prepare( 'INSERT INTO  questionnaire (question_membre, question_label, question_reponse_membre,question_date_reponse)   VALUES(:id, :question1, :reponse1, NOW()), (:id, :question1, :reponse2, NOW()),(:id, :question1, :reponse3, NOW()),(:id, :question2, :reponse4, NOW())');

$insertion->execute(array(
	'id' => $id,
	'question1' => "equipe qui descend 2016-2017",
	'question2' => "meilleur buteur 2016-2017",
    'reponse1' => $equipe1,
    'reponse2' => $equipe2,
    'reponse3' => $equipe3,
	'reponse4' => $buteur
	));
?>

			<h1>Questionnaire validé !</h1>
			<p>Nous vous remercions d'avoir remplit le questionnaire !<br/>
			Vous pouvez retourner sur la page des pronostics en cliquant <a href="<?php echo ROOTPATH; ?>/pronostic/prono_ligue1.php">ici</a>.
			</p><br/>
<?php
}
?>

		<?php
		include('.././includes/bas.php');
       // session_destroy();
		?>