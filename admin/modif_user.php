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
        <h3>Tableau des membres</h3><br>
        <div class="table-responsive">
            
                
              <table id="mytable" class="table table-bordred table-striped">
                 
                    <?php
                $classementh_query = $bdd->query('SELECT * FROM membres ORDER BY membre_inscription  DESC ');
                $i = 0;
                ?>                  
                   <thead>
                       
                     <th>Id</th>
                     <th>Pseudo</th>
                     <th>Avatar</th>
                     <th>Email</th>
                     <th>Sexe</th>
                     <th>Groupe</th> 
                     <th>Club</th>
                     <th>Date_inscription</th>
                     <th>Statut</th>
                     <th>Edition</th>
                   </thead>
    <tbody>
   
                    <?php
        
                while ($donnees = $classementh_query->fetch())
                {
            $avatar = ($donnees['membre_avatar'] != '') ? '<img src="'.ROOTPATH.'/img/avatars/'.htmlspecialchars($donnees['membre_avatar'], ENT_QUOTES).'" style ="height : 50px; " /></a>' : '';
    ?>    
        
    <tr>
    <td><?php echo $donnees['membre_id'] ; ?></td>    
    <td><?php echo $donnees['membre_pseudo'] ; ?></td>
    <td><?php echo $avatar ; ?></td>
    <td><?php echo $donnees['membre_mail'] ; ?></td>
    <td><?php echo $donnees['membre_sexe'] ; ?></td>
    <td><?php echo $donnees['membre_groupe'] ; ?></td>
    <td><?php echo $donnees['membre_club'] ; ?></td>
    <td><?php echo date('d/m/Y',$donnees['membre_inscription']) ; ?></td>
    <td><?php echo $donnees['membre_statut'] ; ?></td>
    <td><a class="btn btn-success" href="update.php?id=<?php echo $donnees['membre_id'] ; ?>">Update</a></td>     
<!--    <td><p data-placement="top" data-toggle="tooltip" title="Edit"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" ><span class="glyphicon glyphicon-pencil"></span></button></p></td>-->
   <td><a class="btn btn-danger " href="delete.php?id=<?php echo $donnees['membre_id'] ; ?>">Delete</a></td>     
  <!--  <td><p data-placement="top" data-toggle="tooltip" title="Delete"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" ><span class="glyphicon glyphicon-trash"></span></button></p></td> -->
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