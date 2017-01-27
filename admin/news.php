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
		
        <h1 class="text-center">Gestionnaire des news du site</h1><br><br>
   
            <div class="row">
    <a href="<?php echo ROOTPATH; ?>/admin/news_add.php"> <button class="col-md-offset-3 col-md-6 col-xs-12 btn btn-primary">Ajouter une news</button></a> 
            </div> 
            
            <br><br>

        </div>
  
    <h4 class="text-center">Modifier ou supprimer une news existante</h4>
    <br>
    
              <table id="mytable" class="table table-bordred table-striped">
                 
                    <?php
                $news_query = $bdd->query('SELECT * FROM news_site ORDER BY news_date_MAJ DESC LIMIT 20 ');
                $i = 0;
                ?>                  
                   <thead>
                       
                     <th>Id</th>
                     <th>texte</th>
                     <th>type</th>
                     <th>date ajout</th>
                     <th>date MAJ</th>
                     <th>Edition</th>
                   </thead>
    <tbody>
   
                    <?php
        
                while ($donnees = $news_query->fetch())
                {
                    
                $date = new DateTime($donnees['news_date']);
                $date_MAJ = new DateTime($donnees['news_date_MAJ']);
                $texte = substr($donnees['news_texte'],0,100);
    ?>    
        
    <tr>
    <td><?php echo $donnees['news_id'] ; ?></td>    
    <td><?php echo  $texte; ?></td>
    <td><?php echo $donnees['news_type'] ; ?></td>
    <td><?php echo $date->format('d/m/Y') ; ?></td>
    <td><?php echo $date_MAJ->format('d/m/Y') ; ?></td>
    <td><a class="btn btn-success" href="news_update.php?id=<?php echo $donnees['news_id'] ; ?>">Update</a></td>     
<!--    <td><p data-placement="top" data-toggle="tooltip" title="Edit"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" ><span class="glyphicon glyphicon-pencil"></span></button></p></td>-->
   <td><a class="btn btn-danger " href="news_delete.php?id=<?php echo $donnees['news_id'] ; ?>">Delete</a></td>     
  <!--  <td><p data-placement="top" data-toggle="tooltip" title="Delete"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" ><span class="glyphicon glyphicon-trash"></span></button></p></td> -->
    </tr>

                    <?php
                        $i++;
                }
                
                if($i == 0) echo '<tr><td colspan="3">Pas de news trouvé.</td></tr>';
                ?>    

    </tbody>
        
</table>    
    
    
    
	</div>
    
    
    

    
    <!-- END # MODAL -->
 </div>
</div>

<?php
include('../includes/bas.php');
?>