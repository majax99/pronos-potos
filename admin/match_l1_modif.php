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
if(!isset($_SESSION['membre_id']) || $_SESSION['membre_statut'] != 'ADMINISTRATEUR')
{
	header('Location: '.ROOTPATH.'/index.php');
	exit();
}

/********Entête et titre de page*********/

/*$titre = 'Inscription';*/



/**********Fin entête et titre***********/

// on récupère le numéro de la journée dans une variable
$j = intval($_GET['j']);

// on indique la bonne écriture en fonction de la journée
if ($j == 1) {$journee_ecriture = $j.'ère journée'; }
else if ($j == 2) {$journee_ecriture =  $j.'nd journée'; }
else  {$journee_ecriture =  $j.'ème journée';}

?>

		
        
        <div class="col-md-12">
        <h3>Tableau des matchs</h3><br>
        <div class="table-responsive">
            
          <div   class= "table-responsive" >      
              <table id="mytable" class="table  table-striped">
                 
                    <?php
                $match_query = $bdd->query('SELECT * FROM match_ligue1 WHERE matchl1_journee = "'.$j.'" ');
                $i = 0;
                ?>                  
                   <thead>
                       
                     <th> Journée</th>
                     <th> Match</th>
                     <th> Date et heure</th>
                     <th> Cote 1</th>
                     <th> Cote N </th>
                     <th> Cote 2 </th>
                   </thead>
    <tbody>
   
                    <?php
        
                while ($donnees = $match_query->fetch())
                {
    ?>    
        
    <tr>
    <td><?php echo $donnees['matchl1_journee'] ; ?></td>    
    <td><?php echo $donnees['matchl1_name'] ; ?></td>
    <td><?php echo $donnees['matchl1_date'] ; ?></td>
    <td><?php echo $donnees['matchl1_cote1'] ; ?></td>
    <td><?php echo $donnees['matchl1_coteN'] ; ?></td>
    <td><?php echo $donnees['matchl1_cote2'] ; ?></td>
    <td><a class="btn btn-success" href="update_match.php?id=<?php echo $donnees['matchl1_id'] ; ?>">Update</a></td>     
<!--    <td><p data-placement="top" data-toggle="tooltip" title="Edit"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" ><span class="glyphicon glyphicon-pencil"></span></button></p></td>-->
    </tr>

                    <?php
                        $i++;
                }
                
                if($i == 0) echo '<tr><td colspan="3">Pas de matchs trouvés.</td></tr>';
                ?>    

    </tbody>
        
</table>
      </div>          
            </div>
            
        </div>
	</div>
    
    
    

    
    <!-- END # MODAL -->


