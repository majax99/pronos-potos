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



$titre = 'mes résultats ligue 1';



/**********Fin entête et titre***********/

?>



<?php 

 

/*************************************************************************************/

/******************* VOIR MES PRONOSTICS *************************************/

/*************************************************************************************/   



// on récupère le numéro de la journée dans une variable

$j = intval($_GET['j']);



// on indique la bonne écriture en fonction de la journée

if ($j == 1) {$journee_ecriture = $j.'ère journée'; }

else if ($j == 2) {$journee_ecriture =  $j.'nd journée'; }

else  {$journee_ecriture =  $j.'ème journée';}



// création de la date du jour en datetime                      

$now = date('Y-m-d H:i:s'); 

$now = new DateTime( $now );



?>

<!--- PRONOSTICS -->    

         

     <div class="col-xs-12  col-lg-offset-1 col-lg-10   toppad" >

          <div class="panel panel-info">

            <div class="panel-heading">

              <h3 class="panel-title text-center"> <?php echo $journee_ecriture;?> </h3>

            </div>

            <div class="panel-body">

                <div class="row">

                    <div   class= "table-responsive" >
		
                  <table class="table table-condensed">



                <?php





                // requête pour récupérer les matchs d'une journée

                $match_query2 = $bdd->query('SELECT matchl1_name as l1_match,matchl1_journee, matchl1_cote1, matchl1_coteN, matchl1_cote2,matchl1_date  FROM match_ligue1  WHERE matchl1_journee = "'.$j.'" ORDER BY matchl1_date  ');

                $i = 0;



                ?>

                      

                                        <thead>

                                                <th class= "text-center">Match </th>

                                            <th class= "text-center hidden-xs" >1 </th>

                                            <th class= "text-center hidden-xs">N </th>

                                            <th class= "text-center hidden-xs">2 </th>

                                            <th class= "text-center hidden-md hidden-sm hidden-lg">cote gagnante </th>

                                            <th class= "text-center"> Pronostic </th>

                                            <th class= "text-center"> Bonus </th>

                                            <th class= "text-center"> Résultat </th>

                                            <th class= "text-center"> Points</th>

                                            

                                        </thead>

                                        <tbody>

            <?php

            while ($match_query = $match_query2->fetch())

            {                

                $date_match= new DateTime($match_query['matchl1_date']);



                $match = htmlspecialchars($match_query['l1_match'], ENT_QUOTES);

                 // On veut récupérer les noms d'équipe

                $pos = strpos($match,'-');

                 $equipe1 = strtolower(substr($match,0,$pos - 1)); // Nom de l'équipe 1

                 $equipe2 = strtolower(substr($match,$pos+2,strlen($match)-($pos+1))); // Nom de l'équipe 2

                 $journee = htmlspecialchars($match_query['matchl1_journee'], ENT_QUOTES);

                 $cote1 = htmlspecialchars($match_query['matchl1_cote1'], ENT_QUOTES);

                 $coteN = htmlspecialchars($match_query['matchl1_coteN'], ENT_QUOTES);

                 $cote2 = htmlspecialchars($match_query['matchl1_cote2'], ENT_QUOTES); 

 

                

     // requête pour récupérer les pronostics du membre         

       $verif_resultat = $bdd->prepare('SELECT A.pronol1_resultat , A.pronol1_bonus, B.matchl1_resultat, round(sum(coalesce(B.points,0))) as points,

       case when points > 0 then round(points) 

       when B.matchl1_resultat IS NULL then "" 

       ELSE "0" end as points2 , C.matchl1_id, C.matchl1_date ,

       case when B.matchl1_resultat = "1" then '.$cote1.'

            when B.matchl1_resultat = "N" then '.$coteN.'

            WHEN B.matchl1_resultat = "2" then '.$cote2.'

            ELSE ""

            END AS COTE_VAINQUEUR

       FROM prono_ligue1 as A 

       LEFT JOIN v_classement_L1 as B ON A.pronol1_match = B.matchl1_name AND  A.pronol1_membre_id = B.pronol1_membre_id 

       LEFT JOIN match_ligue1 as C ON A.pronol1_match = C.matchl1_name

       WHERE A.pronol1_membre_id = :id AND A.pronol1_journee = :journee AND A.pronol1_match = :match LIMIT 1');

              $verif_resultat->execute(array('id' => $_SESSION['membre_id'],

                                                   'match' => $match,

                                                   'journee' => $journee));

              $verif_resultat = $verif_resultat->fetch(PDO::FETCH_ASSOC); 

                     

                

                $pronostic = ($verif_resultat['pronol1_resultat'] <> "" )? $verif_resultat['pronol1_resultat'] :'X'; // pronostic du membre

                $bonus = $verif_resultat['pronol1_bonus']; // bonus

                $resultat = $verif_resultat['matchl1_resultat']; // résultat officiel du match

                $point = $verif_resultat['points2']; // points gagnés

                $cote_vainqueur = $verif_resultat['COTE_VAINQUEUR'];

                

      

                   echo ' 

                       <tr><td style = "width:30%;vertical-align:middle;" class= "text-center"><img src = "../img/ligue1/'.$equipe1.'.png" "vertical-align :middle;" class="hidden-xs">   '.$match. ' <img src = "../img/ligue1/'.$equipe2.'.png" style="vertical-align :middle;" class="hidden-xs"> </td>

                        <td style = "vertical-align:middle;" class= "text-center hidden-xs">  '.$cote1.'  </td>

                        <td style = "vertical-align:middle;" class= "text-center hidden-xs">  '.$coteN.'  </td>

                        <td style = "vertical-align:middle;" class= "text-center hidden-xs">  '.$cote2.'  </td>

                        <td style = "vertical-align:middle;" class= "text-center hidden-sm hidden-md hidden-lg" >  '.$cote_vainqueur.'  </td>

                        <td style = "vertical-align:middle;" class= "text-center"> ' .$pronostic.'</td> 

    <td style = "vertical-align:middle;" class= "text-center">

    

    <label class="checkbox-inline" for="checkboxes-0">';  

                

  // on vérifie si le membre a indiqué son bonus              

    if ($bonus == 5) {

    

     echo ' <input  type="radio" name="checkboxes" id="checkboxes" value="5" checked disabled>' ;

        }  

                

    else {

        echo ' <input  type="radio" name="checkboxes" id="checkboxes" value="5" disabled>' ;

    }

 /*****************************************************************/                  

echo '    </label>

</td>

                    <td style = "vertical-align:middle;" class= "text-center"> ' .$resultat.'</td> 

                    <td style = "vertical-align:middle;" class= "text-center"> ' .$point.'</td> ';

                                            

 // On indique le bouton que si le match est commencé

$verifPronoMatch = ($date_match < $now )? '<td><a class="btn btn-primary" href="ComparPronoMatch_Ligue1.php?match='.$verif_resultat["matchl1_id"].'" > <i class="glyphicon glyphicon-search"></i> </a> </td> ' : ""; 

                                            

  

echo $verifPronoMatch; ?>                

      

        </tr>

                

<?php               $i++;

                

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









    <!-- END # MODAL LOGIN -->

