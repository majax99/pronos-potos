<?php
/*

Table des pronostics pour les phases de poules

*/

session_start();
header('Content-type: text/html; charset=utf-8');
include('.././includes/config.php');

/********Actualisation de la session...**********/

include('.././includes/fonctions.php');
actualiser_session();


/********Fin actualisation de session...**********/



if(!isset($_SESSION['membre_id']))
{
	header('Location: '.ROOTPATH.'/index.php');
	exit();
}


/********Entête et titre de page*********/

$titre = 'pronostic phase de poule';

include('.././includes/haut.php'); //contient le doctype, et head.

/**********Fin entête et titre***********/
?>

<?php
include('.././includes/col_gauche.php');
?>


        
    <div class= "main" style ="background-image: linear-gradient(rgb(255, 255, 255), rgb(12, 33, 97));">
        

        <!-- Corps de page
    ================================================== -->
  <div class="container-fluid">

      
  
<?php 
        //On vérifie que les pronostics ont bien été réalisés
$verif_prono = $bdd->prepare('SELECT prono_groupe FROM pronostic_euro WHERE prono_membre_id = :id AND prono_phase = "POULES" LIMIT 1');
$verif_prono->execute(array('id' => $_SESSION['membre_id'] ));
$verif_prono = $verif_prono->fetch(PDO::FETCH_ASSOC);
      
$verif_bonus = $bdd->prepare('SELECT bonusP_France FROM bonusp_euro WHERE bonusP_membre_id = :id LIMIT 1');
$verif_bonus->execute(array('id' => $_SESSION['membre_id'] ));
$verif_bonus = $verif_bonus->fetch(PDO::FETCH_ASSOC);
      
$match_8eme = $bdd->query('SELECT euro_match_name as euro_match,euro_phase  FROM match_euro WHERE euro_phase = "8eme" and euro_match_name <> "" ');
 
/*************************************************************************************/
/******************* VERIFICATION DES PRONOSTICS *************************************/
/*************************************************************************************/   
      
      
if (!$verif_bonus) 
{
    
     $arrayparcours= array('Poules' , 'Huitièmes de finales' , 'Quarts de finales', 'Demis finales' , 'Finale');
     $arrayequipe = array('Albanie', 'Allemagne', 'Angleterre' , 'Autriche' ,'Belgique', 'Croatie', 'Espagne' , 'France' ,'Hongrie', 'Irlande', 'Irlande du Nord', 'Islande' , 'Italie' ,'Galles', 'Pologne', 'Portugal'  ,'Rép Tchèque', 'Roumanie', 'Russie' , 'Slovaquie' ,'Suède', 'Suisse', 'Turquie' , 'Ukraine');
 
?>
<!--- PRONOSTICS BONUS  -->    
<h3 class="text-center"> Indiquez vos choix pour les bonus</h3>
<p class="text-center"><em>Une fois la validation effectuée, vous ne pourrez plus modifier vos choix</em></p><br>

  <form class="form-horizontal" action="verif_bonus.php" method="post"  >    
    <div class="row">      
      
<div class="form-group">
  <label class="col-md-4 control-label" >Jusqu'où ira l'équipe de France</label>
  <div class="col-md-4">        
<?php 
       echo "<select name='parcours' class='form-control' >
 
     <option>Sélectionnez</option>"  ;
  
     foreach( $arrayparcours as $parcours ) {
          echo "<option value='$parcours'>$parcours</option>"  ;
          }
         // echo "</select>"  ;
  
?>
    </select>
  </div>
</div>
      
        
<div class="form-group">
  <label class="col-md-4 control-label" >Qui gagnera l'euro 2016</label>
  <div class="col-md-4">        
<?php 
       echo "<select name='vainqueur' class='form-control' >
 
     <option>Sélectionnez</option>"  ;
  
     foreach( $arrayequipe as $vainqueur ) {
          echo "<option value='$vainqueur'>$vainqueur</option>"  ;
          }

  
?>
    </select>
  </div>
</div>
      

<div class="form-group">
  <label class="col-md-4 control-label">Qui finira en deuxième position</label>
  <div class="col-md-4">        
<?php 
       echo "<select name='finaliste' class='form-control' >
 
     <option>Sélectionnez</option>"  ;
  
     foreach( $arrayequipe as $finaliste ) {
          echo "<option value='$finaliste'>$finaliste</option>"  ;
          }

?>
    </select>
  </div>
</div>
<!-- Button submit -->
         
  <div class="text-center" style="padding:20px;"><input class=" btn btn-primary btn-lg" type="submit" value="Valider" /></div>
         </div>   
</form>
        
    

<?php
}
    
else if (!$verif_prono) 
{
?>
<!--- PRONOSTICS PHASES DE POULES  -->    
  <form class="form-horizontal" action="verif_prono.php" method="post"  >    
    <div class="row">
     <h3 class="text-center"> Indiquez vos pronostics et <br> cliquez sur "Valider les pronostics"</h3>
     <p class="text-center"><em>Une fois la validation effectuée, vous ne pourrez plus modifier vos choix</em></p><br>
  
         <?php    
        $groupe = array ('A', 'B', 'C', 'D', 'E', 'F'); // on boucle sur chaque groupe 
          foreach($groupe as $element)
          {
              $ecart= ($element == 'A' || $element == 'C' || $element == 'E' ) ? 'col-lg-offset-1' : '';
        ?>
     <div class="col-xs-12 <?php echo $ecart ; ?> col-lg-5   toppad" >
          <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title text-center">GROUPE <?php echo $element; ?></h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    
                  <table class="table">

                <?php
                $match_query2 = $bdd->prepare('SELECT euro_match_name as euro_match,euro_groupe  FROM match_euro where euro_groupe = :groupe ');
                $match_query2->execute(array('groupe' => $element));
                $i = 0;
                ?>
                      
                                        <thead>
                                                <th class= "text-center">Match </th>
                                                <th class= "text-center"> Score </th>
                                        </thead>
                                        <tbody>
            <?php
            while ($match_query = $match_query2->fetch())
            {
                $match = htmlspecialchars($match_query['euro_match'], ENT_QUOTES);
                 // On veut récupérer les noms d'équipe
                $pos = strpos($match,'-');
                 $equipe1 = substr($match,0,$pos - 1); // Nom de l'équipe 1
                 $equipe2 = substr($match,$pos+2,strlen($match)-($pos+1)); // Nom de l'équipe 2
                 $groupe = htmlspecialchars($match_query['euro_groupe'], ENT_QUOTES);  // Nom du groupe
                 $prono_info1 = $match.'1'.$groupe.'info';
                 $prono_info2 = $match.'2'.$groupe.'info';
                $form_prono1 = $match.'1'.$groupe ;
                $form_prono2 = $match.'2'.$groupe ;
                    
                $valeur1= (isset($_SESSION[$prono_info1]) &&  $_SESSION[$prono_info1] == '') ? htmlspecialchars($_SESSION[$form_prono1], ENT_QUOTES) : '';
                $valeur2= (isset($_SESSION[$prono_info2]) &&  $_SESSION[$prono_info2] == '') ? htmlspecialchars($_SESSION[$form_prono2], ENT_QUOTES) : '';
     
                   echo ' 
                        <td class= "text-center"><img src = "../img/icone_pays/'.$equipe1.'.png" style="vertical-align : -3px;">   '.$match.'  <img src = "../img/icone_pays/'.$equipe2.'.png" style="vertical-align : -3px;"></td>
                        <td class= "text-center"><input type="text" name="'.$match.'1'.$groupe.'"  maxlength="1" size="1" value="'.$valeur1.'" >&nbsp;&nbsp;  -  &nbsp;&nbsp;
                        <input type="text" name="'.$match.'2'.$groupe.'"  maxlength="1" size="1" value="'.$valeur2.'">
                        </td>
                        
                        </tr>
                        ';
                        $i++;
                }
                
                if($i == 0) echo '<tr><td colspan="3">Pas de match trouvé.</td></tr>';
                ?>                      
                        
                                        </tbody>
                 </table>
                </div>
              </div>
            </div>
          </div>
     <?php } ?>
      
        
        </div>
<!-- Button submit -->
         
  <div class="text-center" style="padding:20px;"><input class=" btn btn-primary btn-lg" type="submit" value="Valider les pronostics" /></div>
</form>

<?php  

}
    
else 
{
?> 
    <h1> Vous avez déjà fait vos pronostics</h1>
    <h5> Cliquez sur l'un des boutons ci-dessous pour les voir ou comparer les pronostics des autres membres</h5><br>
<div class="row">    
                   
    <a type="button" class=" col-xs-offset-2 col-xs-8 col-md-offset-3 col-md-6 btn btn-primary btn-lg" href= "<?php echo ROOTPATH; ?>/pronostic/voir_prono.php">Voir mes pronostics</a>

</div>  

<div class="row" style="display:none;">    
                   
    <a type="button" class="col-xs-offset-2 col-xs-8 col-md-offset-3 col-md-6 btn btn-default btn-lg" href= "<?php echo ROOTPATH; ?>/pronostic/compar_prono.php">Voir les pronostics des autres membres</a>

</div> 
<br><br>

<div class="row">    
                   
    <a type="button" class="col-xs-offset-2 col-xs-8 col-md-offset-3 col-md-6 btn btn-default btn-lg" href= "<?php echo ROOTPATH; ?>/pronostic/compar_pronoMatch.php">Voir les pronostics sur un match</a>

</div> <br><br>

<?php 
if ($match_8eme->fetch()) 
 {
?>
<div class="row">    
                   
    <a type="button" class="col-xs-offset-2 col-xs-8 col-md-offset-3 col-md-6 btn btn-danger btn-lg" href= "<?php echo ROOTPATH; ?>/pronostic/pronostic_phaseFinale.php">Faire les pronostics des phases finales</a>

</div> <br><br>
<?php
 }
 ?>

<?php 
}
      ?>
        </div>
      </div>


    <!-- END # MODAL LOGIN -->
		
<?php
include('../includes/bas.php');
?>
