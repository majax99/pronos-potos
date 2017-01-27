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

include('.././includes/haut.php'); //contient le doctype, et head.

/**********Fin entête et titre***********/
?>

<?php
include('.././includes/col_gauche.php');
?>


        
<div class="main">

<div class="container">
	<div class="row">
		
        
        <div class="col-md-12">
        <h3>Tableau des matchs</h3><br>
        <div class="table-responsive">
            
                
              <table id="mytable" class="table table-bordred table-striped">
                 
                    <?php
                $match_query = $bdd->query('SELECT * FROM pronostic_euro where  prono_phase = "quar" ');
                $i = 0;
                ?>                  
                   <thead>
                     <th>id</th>  
                     <th>Phase</th>
                     <th>Match</th>
                     <th>membre_id</th>
                     <th>resultat1</th>
                     <th>resultat2</th> 
                   </thead>
    <tbody>
   
                    <?php
        
                while ($donnees = $match_query->fetch())
                {
    ?>    
        
    <tr>
    <td><?php echo $donnees['prono_id'] ; ?></td>      
    <td><?php echo $donnees['prono_phase'] ; ?></td>    
    <td><?php echo $donnees['prono_match_name'] ; ?></td>
    <td><?php echo $donnees['prono_membre_id'] ; ?></td>
    <td><?php echo $donnees['prono_resultat1'] ; ?></td>
    <td><?php echo $donnees['prono_resultat2'] ; ?></td>
  
<!--    <td><p data-placement="top" data-toggle="tooltip" title="Edit"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" ><span class="glyphicon glyphicon-pencil"></span></button></p></td>-->
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
    
    
    

    
    <!-- END # MODAL -->
 </div>
</div>

<?php
include('../includes/bas.php');
?>