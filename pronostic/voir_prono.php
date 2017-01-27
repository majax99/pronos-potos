<?php
/*
Permet de voir les pronostics des autres utilisateurs (phase de poule)

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

$titre = 'voir_pronostic_poules';

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
        $verif_prono = $bdd->prepare('SELECT prono_groupe FROM pronostic_euro WHERE prono_membre_id = :id LIMIT 1');
        $verif_prono->execute(array('id' => $_SESSION['membre_id'] ));
        $verif_prono = $verif_prono->fetch(PDO::FETCH_ASSOC);
      if ($verif_prono) 
      {


?>

    <div class="row">
     <h1 class="text-center"> Mes pronostics </h1>

 <!-- PHASE FINALE -->       

      <?php    
        $phase = array ('8eme', 'quar', 'demi', 'fina'); // on boucle sur chaque groupe 
          foreach($phase as $element)
          {
        ?>
        
        <div class="col-xs-12 col-lg-offset-3 col-lg-6">
          <div class="panel panel-danger">
            <div class="panel-heading">
              <h3 class="panel-title text-center">PHASE : <?php echo $element; ?>  </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    
                  <table class="table">

                    <?php
          
        $match_query2 = $bdd->prepare('SELECT prono_match_name, prono_resultat1, prono_resultat2, prono_groupe
                                    FROM pronostic_euro WHERE prono_membre_id = :id AND prono_phase = :phase ORDER BY prono_id');
        $match_query2->execute(array('id' => $_SESSION['membre_id'],
                                     'phase' => $element));
        
                $i = 0;
                ?> 
                                        <thead>
                                                <th >Match </th>
                                                <th > Score </th>
                                        </thead>
                                        <tbody>
            <?php
            
            while ($match_query = $match_query2->fetch())
                {
                $match = htmlspecialchars($match_query['prono_match_name'], ENT_QUOTES);
                 // On veut récupérer les noms d'équipe
                $pos = strpos($match,'-');
                $equipe1 = substr($match,0,$pos - 1); // Nom de l'équipe 1
                $equipe2 = substr($match,$pos+2,strlen($match)-($pos+1)); // Nom de l'équipe 2
                $groupe = htmlspecialchars($match_query['prono_groupe'], ENT_QUOTES);  // Nom du groupe

                    
                $valeur1= htmlspecialchars($match_query['prono_resultat1'], ENT_QUOTES);
                $valeur2= htmlspecialchars($match_query['prono_resultat2'], ENT_QUOTES);
     
                   echo ' 
                        <td><img src = "../img/icone_pays/'.$equipe1.'.png" style="vertical-align : -3px;">   '.$match.'  <img src =    "../img/icone_pays/'.$equipe2.'.png" style="vertical-align : -3px;"></td>
                        <td width= 20%>
                        '.$valeur1.' &nbsp;  -  &nbsp; '.$valeur2.'
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
        
        
<!--- MATCH DU GROUPE A --->  
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
          
        $match_query2 = $bdd->prepare('SELECT prono_match_name, prono_resultat1, prono_resultat2, prono_groupe
                                    FROM pronostic_euro WHERE prono_membre_id = :id AND prono_groupe = :groupe ORDER BY prono_id');
        $match_query2->execute(array('id' => $_SESSION['membre_id'],
                                     'groupe' => $element));
        
                $i = 0;
                ?> 
                                        <thead>
                                                <th >Match </th>
                                                <th > Score </th>
                                        </thead>
                                        <tbody>
            <?php
            
            while ($match_query = $match_query2->fetch())
                {
                $match = htmlspecialchars($match_query['prono_match_name'], ENT_QUOTES);
                 // On veut récupérer les noms d'équipe
                $pos = strpos($match,'-');
                $equipe1 = substr($match,0,$pos - 1); // Nom de l'équipe 1
                $equipe2 = substr($match,$pos+2,strlen($match)-($pos+1)); // Nom de l'équipe 2
                $groupe = htmlspecialchars($match_query['prono_groupe'], ENT_QUOTES);  // Nom du groupe

                    
                $valeur1= htmlspecialchars($match_query['prono_resultat1'], ENT_QUOTES);
                $valeur2= htmlspecialchars($match_query['prono_resultat2'], ENT_QUOTES);
     
                   echo ' 
                        <td><img src = "../img/icone_pays/'.$equipe1.'.png" style="vertical-align : -3px;">   '.$match.'  <img src =    "../img/icone_pays/'.$equipe2.'.png" style="vertical-align : -3px;"></td>
                        <td width= 20%>
                        '.$valeur1.' &nbsp;  -  &nbsp; '.$valeur2.'
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
         


<?php  
      }
      else 
{?> 
    <h1> Vous n'avez pas fait vos pronostics</h1>
    <h3> Cliquez sur le bouton ci-dessous pour les faire</h3><br>
      
  <div class="text-center">                    
    <a type="button" class="btn btn-sm btn-success" href= "<?php echo ROOTPATH; ?>/pronostic/pronostic.php">Voir mes pronostics</a>
  </div>
      
<?php 
}
      ?>
        </div>
      </div> <!-- FIN DU MAIN -->


    <!-- END # MODAL LOGIN -->
		
<?php
include('../includes/bas.php');
?>
