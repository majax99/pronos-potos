<?php
/*

MAJ des bonus (réserver à l'administrateur)

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


        
<div class="main">

        <!-- Corps de page
    ================================================== -->
  <div class="container-fluid">
<h2 class= "text-center"> Mettre à jour les bonus</h2><br> 

     
   <!-- TABLEAU DEs BONUS POUR CHAQUE MEMBRE -->   
      
<div class="text-center"><button class= "btn btn-lg btn-danger"data-toggle="collapse" data-target="#bonusMembre">Bonus des membres</button></div><br><br> 

<div id="bonusMembre" class="collapse">      
<form class="form-horizontal" action="bonus.php" method="post"  >  
	<div class="row">
        
        <div class="col-md-12">
        <h3>Tableau des Bonus</h3><br>
        <div class="table-responsive">
            
                
              <table id="mytable" class="table table-striped">
                 
                    <?php
                $bonus_query = $bdd->query('SELECT *  FROM bonusp_euro  ORDER BY bonusP_membre_id  DESC ');
                $i = 0;
                ?>                  
                   <thead>
                       
                     <th class="text-center">Id</th>
                     <th class="text-center">Pseudo</th>
                     <th class="text-center">Bonus_France</th>
                     <th class="text-center">Bonus_Finaliste</th>
                     <th class="text-center">Bonus_Vainqueur</th>
                     <th class="text-center">Parcours France</th>
                     <th class="text-center">Bon finaliste</th>
                     <th class="text-center">Bon vainqueur</th>

                   </thead>
    <tbody>
   
                    <?php
        
                while ($donnees = $bonus_query->fetch())
                {
                    $id = $donnees['bonusP_membre_id'] ;
    ?>    
        
    <tr>
    <td class="text-center"><?php echo $id ; ?></td>
    <td class="text-center"><?php echo $donnees['bonusP_pseudo'] ; ?></td>
    <td class="text-center"><?php echo $donnees['bonusP_France'] ; ?></td>
    <td class="text-center"><?php echo $donnees['bonusP_finaliste'] ; ?></td>
    <td class="text-center"><?php echo $donnees['bonusP_vainqueur'] ; ?></td>
    <td class="text-center"><input type="text" name="fra_parcours<?php echo $id ; ?>"  size = 1 value="<?php echo $donnees['bonusCourse_France']; ?>" /></td>
    <td class="text-center"><input type="text" name="fin_parcours<?php echo $id ; ?>"  size = 1 value="<?php echo $donnees['bonusCourse_finaliste']; ?>" /></td>
    <td class="text-center"><input type="text" name="vqr_parcours<?php echo $id ; ?>"  size = 1 value="<?php echo $donnees['bonusCourse_vainqueur']; ?>" /></td>
    </tr>

                    <?php
                        $i++;
                }
                
                if($i == 0) echo '<tr><td colspan="3">Pas de membre trouvé.</td></tr>';
                ?>    

    </tbody>
        
</table>
                
            </div>
            
        </div>
	</div>
         <input type="hidden" name="sent"  value="1" />
         <div class="text-center" style="padding:20px;"><input class=" btn btn-primary" type="submit" value="Valider" /></div>
</form> 
    
 <?php 
if (isset($_POST['sent'])) {
$variables = $_POST;
       while (list($cle,$valeur) = each($variables))
       { 
        $$cle=$valeur;
           if (substr($cle,0,3) == 'fra') 
           $france= (substr($cle,0,3) == 'fra') ? $$cle : '' ;
           $finaliste= (substr($cle,0,3) == 'fin') ? $$cle : '' ;
           $vainqueur= (substr($cle,0,3) == 'vqr') ? $$cle : '' ;
           
           $id_post= substr($cle,12,strlen($cle) - 12);
           $nom = substr($cle,0,12);

           //On modifie la table
           
           if ($nom == 'fra_parcours')
           {
                    $req3 = $bdd->prepare('UPDATE bonusp_euro SET bonusCourse_France=  :france_parc WHERE bonusP_membre_id = :id');
                    $req3->execute(array('france_parc' => $france,
                                        'id' => $id_post));
                    $req3->closeCursor();
           }
           
           else if ($nom == 'fin_parcours')
           {
                    $req3 = $bdd->prepare('UPDATE bonusp_euro SET  bonusCourse_finaliste = :finaliste_parc WHERE bonusP_membre_id = :id');
                    $req3->execute(array('finaliste_parc' => $finaliste,
                                        'id' => $id_post));
                    $req3->closeCursor();
           }
           
           else if ($nom == 'vqr_parcours')
           {
                    $req3 = $bdd->prepare('UPDATE bonusp_euro SET bonusCourse_vainqueur = :vainqueur_parc WHERE bonusP_membre_id = :id');
                    $req3->execute(array('vainqueur_parc' => $vainqueur,
                                        'id' => $id_post));
                    $req3->closeCursor();
           }           
       }
}
 ?>    
    
 </div> 
      
 
<!-- TABLEAU DE MAJ DES BONUS -->  
      
      <div class="text-center"><button class= "btn btn-lg btn-success"data-toggle="collapse" data-target="#bonusMAJ">Mettre à jour les bonus</button></div><br><br>

<div id="bonusMAJ" class="collapse">

   <div class="row">
     <h3 class="text-center"> Indiquez les bonus dans le tableau et <br> cliquez sur "Valider"</h3>

<?php 
$arrayparcours= array('Poules' , 'Huitièmes de finales' , 'Quarts de finales', 'Demis finales' , 'Finale');
$bonus = $bdd->query('SELECT * FROM bonusp_euro LIMIT 1');
$bonus = $bonus->fetch(PDO::FETCH_ASSOC);
       
       $France =  htmlspecialchars($bonus['bonusOK_France'], ENT_QUOTES);
       $finaliste =  htmlspecialchars($bonus['bonusOK_finaliste'], ENT_QUOTES);
       $vainqueur =  htmlspecialchars($bonus['bonusOK_vainqueur'], ENT_QUOTES);
?>
       
<form class="form-horizontal" action="bonus.php" method="post"  >  
             <table id="mytable" class="table table-bordred table-striped">
                     <thead>
                     <th>Bonus_France</th>
                     <th>Bonus_Finaliste</th>
                     <th>Bonus_Vainqueur</th>
                     </thead> 
              <tbody>
                <tr>
                    <td><input type="text" name="France"  value="<?php echo $France; ?>" ></td>
                    <td><input type="text" name="finaliste"  value="<?php echo $finaliste; ?>" ></td>
                    <td><input type="text" name="vainqueur"  value="<?php echo $vainqueur; ?>" ></td>
                    <td><input type="hidden" name="validate"  value="1" ></td>
                </tr>
              </tbody>
             </table>
    
 </div>
     <div class="text-center" style="padding:20px;"><input class=" btn btn-primary" type="submit" value="Valider" /></div>
</form> 
       
 <?php 
   if (isset($_POST['validate']))
   {
//On modifie la table
     
                    $req2 = $bdd->prepare('UPDATE bonusp_euro SET bonusOK_France=  :france , bonusOK_finaliste = :finaliste ,
                    bonusOK_vainqueur = :vainqueur, bonus_pts_France=
                    ( CASE 
                      WHEN bonusOK_France = bonusP_France THEN "5"
                      ELSE "0"
    END) ,
    bonus_pts_finaliste=
                    ( CASE 
                      WHEN (bonusOK_finaliste = bonusP_finaliste) or (bonusOK_vainqueur = bonusP_finaliste)  THEN "7"
                      ELSE "0"
    END),
    bonus_pts_vainqueur=
                    ( CASE 
                      WHEN bonusOK_vainqueur = bonusP_vainqueur THEN "10"
                      ELSE "0"
    END)');
                    $req2->execute(array('france' => $_POST['France'],
                                        'finaliste' => $_POST['finaliste'],
                                        'vainqueur' => $_POST['vainqueur']));
                    $req2->closeCursor();
          unset($_POST); 
 }

 ?>       
      
 </div>            

        </div>
      </div>


    <!-- END # MODAL LOGIN -->
		
<?php
include('../includes/bas.php');
?>
