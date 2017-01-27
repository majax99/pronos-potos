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



/* Si le membre n'est pas administrateur, on le renvoie sur l'index */

if(!isset($_SESSION['membre_id']) || ($_SESSION['membre_statut'] != 'ADMINISTRATEUR' && $_SESSION['membre_statut'] != 'ADMIN_SCORE'))

{

	header('Location: '.ROOTPATH.'/index.php');

	exit();

}



/********Entête et titre de page*********/



$titre = 'MAJ des résultats';



/**********Fin entête et titre***********/

?>



<?php 

 

/*************************************************************************************/

/******************* VERIFICATION DES PRONOSTICS *************************************/

/*************************************************************************************/   



// on récupère le numéro de la journée dans une variable

$j = intval($_GET['j']);



// on indique la bonne écriture en fonction de la journée

if ($j == 1) {$journee_ecriture = $j.'ère journée'; }

else  {$journee_ecriture =  $j.'ème journée';}





?>

<!--- PRONOSTICS -->    

     

  <form class="form-horizontal" action="resultat_L1_ajax.php" method="post"  >       

     <div class="col-xs-12  col-lg-offset-2 col-lg-8   toppad" >

          <div class="panel panel-info">

            <div class="panel-heading">

              <h3 class="panel-title text-center"> <?php echo $journee_ecriture;?> </h3>

            </div>

            <div class="panel-body">

                <div class="row">

                 <div   class= "table-responsive" >

                  <table class="table">



                <?php





                // requête pour récupérer les matchs d'une journée

                $match_query2 = $bdd->query('SELECT matchl1_name as l1_match,matchl1_journee, matchl1_cote1, matchl1_coteN, matchl1_cote2  FROM match_ligue1  WHERE matchl1_journee = "'.$j.'" ORDER BY matchl1_date ');

                $i = 0;



                ?>

                      

                                        <thead>

                                                <th class= "text-center">Match </th>

                                            <th class= "text-center">1 </th>

                                            <th class= "text-center">N </th>

                                            <th class= "text-center">2 </th>

                                            <th class= "text-center"> Résultat </th>

                                        </thead>

                                        <tbody>

            <?php

            while ($match_query = $match_query2->fetch())

            {                



                 $match = htmlspecialchars($match_query['l1_match'], ENT_QUOTES);

                 // On veut récupérer les noms d'équipe

                 $pos = strpos($match,'-');

                 $equipe1 = strtolower(substr($match,0,$pos - 1)); // Nom de l'équipe 1

                 $equipe2 = strtolower(substr($match,$pos+2,strlen($match)-($pos+1))); // Nom de l'équipe 2

                 $journee = htmlspecialchars($match_query['matchl1_journee'], ENT_QUOTES);

                 $cote1 = htmlspecialchars($match_query['matchl1_cote1'], ENT_QUOTES);

                 $coteN = htmlspecialchars($match_query['matchl1_coteN'], ENT_QUOTES);

                 $cote2 = htmlspecialchars($match_query['matchl1_cote2'], ENT_QUOTES);  

                 // tableau avec les différents types de résultats

                  $arrayresultat = array('1', 'N', '2');     

                

     // requête pour vérifier si les résultats ont déjà été entrés par le membre          

       $verif_resultat = $bdd->prepare('SELECT matchl1_resultat FROM match_ligue1 WHERE  matchl1_journee = :journee AND matchl1_name = :match LIMIT 1');

              $verif_resultat->execute(array('match' => $match,

                                             'journee' => $journee));

              $verif_resultat = $verif_resultat->fetch(PDO::FETCH_ASSOC);   

                

                $resultat = $verif_resultat['matchl1_resultat']; // resultat

      

                   echo ' 

                       <td style = "width:50%;vertical-align:middle;" class= "text-center"><img src = "../img/ligue1/'.$equipe1.'.png" "vertical-align :middle;">   '.$match. ' <img src = "../img/ligue1/'.$equipe2.'.png" style="vertical-align :middle;"> </td>

                        <td style = "vertical-align:middle;" class= "text-center">  '.$cote1.'  </td>

                        <td style = "vertical-align:middle;" class= "text-center">  '.$coteN.'  </td>

                        <td style = "vertical-align:middle;" class= "text-center">  '.$cote2.'  </td>

                        <td style = "vertical-align:middle;" class= "text-center">

                        <select  id="resultat_match" name="'.$match.'!'.$j.'" >



                        <option > </option>';

  // pour le bouton select, on boucle pour vérifier si le membre n'a pas déjà indiqué le résultat 

     foreach( $arrayresultat as $res ) {

         

          if ($res == $resultat) {

              echo "<option value='$res' selected>$res</option>"  ;

          }

         

         else {

          echo "<option value='$res'>$res</option>"  ;

          }

                                        }   

              echo ' </select></td></tr>';     

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

          </div>



      



<!-- Button submit -->

<div class="text-center" style="padding:20px;"><input class=" btn btn-primary btn-lg" type="submit" value="Valider les résultats" /></div>  



</form>







    <!-- END # MODAL LOGIN -->

