<?php

/*

Règlement du site (pronostics, classement,...)

*/



session_start();

header('Content-type: text/html; charset=utf-8');

include('.././includes/config.php');



/********Actualisation de la session...**********/



include('.././includes/fonctions.php');

actualiser_session();



/* Si le membre n'est pas connecté, on le renvoie sur l'index */

if(!isset($_SESSION['membre_id']))

{

	header('Location: '.ROOTPATH.'/index.php');

	exit();

}

/********Fin actualisation de session...**********/



/********Entête et titre de page*********/



$titre = 'Statistiques';





// on récupère le numéro de la journée dans une variable

$club = $_GET['club'];

$clubMin = strtolower($club);

/**********Fin entête et titre***********/

?>



       <div class="row" ><br>

           

           

        

<!----------------STAT INDIVIDUELLE POUR CHAQUE CLUB ------------------------>

 <?php        

if ($_GET['club'] == "Inviduelle Club") {          

?>           

        <div class="col-xs-12 col-md-12  toppad" >

         <div class="panel panel-info" >

            <div class="panel-heading">

              <h3 class="panel-title text-center">Mes statistiques par club</h3>

            </div>

            <div class="panel-body">

              <div class="row">

                 <div class= "table-responsive" >       

                  <table class="table table-condensed" style= "font-size:0.8em;" >

                      

                            <thead>

                                            

                                 <th style="vertical-align:middle;" class= "text-center"> Club </th>

                                 <th></th>

                                 <th style="vertical-align:middle;" class= "text-center"> Points </th>

                                 <th class= "text-center" style="vertical-align:middle;">ratio <i style="font-size:0.7em;">(1) / (2)<i/>  </th>

                                 <th class= "text-center" style="vertical-align:middle;">Résultats <br> trouvés <i style="font-size:0.7em;">(1)</i></th>

                                 <th class= "text-center" style="vertical-align:middle;">matchs <br> pronostiqués <i style="font-size:0.7em;">(2)</i></th>

                                 <th style="vertical-align:middle;" class= "text-center">Bonus tentés </th>

                                 <th style="vertical-align:middle;" class= "text-center">Bonus réussis <br>(pts) </th>

                                 <th style="vertical-align:middle;" class= "text-center">V </th>

                                 <th style="vertical-align:middle;" class= "text-center">N </th>

                                 <th style="vertical-align:middle;" class= "text-center">D </th>

                            </thead>



                    <tbody >

                                             

                    <?php

$arrayligue1 = array('Angers', 'Bastia', 'Bordeaux' , 'Caen' , 'Dijon', 'Guingamp', 'Lille' , 'Lorient' ,'Lyon', 'Marseille', 'Metz', 'Monaco' , 'Montpellier' ,'Nancy', 'Nantes', 'Nice'  ,'ParisSG', 'Rennes', 'Saint Etienne' , 'Toulouse');      

                        

                             foreach( $arrayligue1 as $clubligue1 ) {

                        

                        

                        

    $i=0;

                  $stat_indiv = $bdd->query('SELECT "'.$clubligue1.'" as club ,  round(sum(coalesce(points,0))) as points,  sum(coalesce(bonus_trouve,0)) as bonus_trouve,  COUNT(CASE WHEN pronol1_bonus = 5 then 1 ELSE NULL END) as "bonus_tente", round(sum(coalesce(points_bonus,0))) as pts_bonus, sum(coalesce(resultat_trouve,0)) as nb_resultat, sum(coalesce(prono_realise,0)) as nb_prono,

                  COUNT(CASE WHEN (((pronol1_resultat = "1") AND (substr(A.matchl1_name,1,LOCATE( "-", A.matchl1_name ) -1) = "'.$clubligue1.'"))) OR (((pronol1_resultat = "2") AND (substr(A.matchl1_name,LOCATE( "-", A.matchl1_name ) +2, LENGTH(A.matchl1_name) - (LOCATE( "-", A.matchl1_name ) +1)) = "'.$clubligue1.'"))) then 1 ELSE NULL END) as "V" ,

                  

COUNT(CASE WHEN (((pronol1_resultat = "2") AND (substr(A.matchl1_name,1,LOCATE( "-", A.matchl1_name ) -1) = "'.$clubligue1.'"))) OR (((pronol1_resultat = "1") AND (substr(A.matchl1_name,LOCATE( "-", A.matchl1_name ) +2, LENGTH(A.matchl1_name) - (LOCATE( "-", A.matchl1_name ) +1)) = "'.$clubligue1.'"))) then 1 ELSE NULL END) as "D", 



COUNT(CASE WHEN (((pronol1_resultat = "N") AND (substr(A.matchl1_name,1,LOCATE( "-", A.matchl1_name ) -1) = "'.$clubligue1.'"))) OR (((pronol1_resultat = "N") AND (substr(A.matchl1_name,LOCATE( "-", A.matchl1_name ) +2, LENGTH(A.matchl1_name) - (LOCATE( "-", A.matchl1_name ) +1)) = "'.$clubligue1.'"))) then 1 ELSE NULL END) as "N"



FROM    match_ligue1 as A LEFT JOIN v_classement_L1 as B

                  ON A.matchl1_name = B.matchl1_name

                  WHERE A.matchl1_name LIKE "%'.$clubligue1.'%" AND A.matchl1_resultat <> "" AND (pronol1_membre_id = '.$_SESSION["membre_id"].') ');          

                        

                  while ($donnees = $stat_indiv->fetch())

                {



                      

		  $clubligue1bis = strtolower($clubligue1);

                $prono1 =  htmlspecialchars($donnees['V'], ENT_QUOTES);

                $pronoN =  htmlspecialchars($donnees['N'], ENT_QUOTES);

                $prono2 =  htmlspecialchars($donnees['D'], ENT_QUOTES);

                $bonusTempt = htmlspecialchars($donnees['bonus_tente'], ENT_QUOTES);   

                $bonusSuccess = htmlspecialchars($donnees['bonus_trouve'], ENT_QUOTES);   

                $point =  htmlspecialchars($donnees['points'], ENT_QUOTES);

                $pointBonus =  htmlspecialchars($donnees['pts_bonus'], ENT_QUOTES);

                      

                if ($donnees['nb_prono'] == 0 )   {$ratio = 0 ;} else {$ratio= number_format(($donnees['nb_resultat'] / $donnees['nb_prono'])*100,1);}

                //$ratio= number_format(($donnees['nb_resultat'] / $donnees['nb_prono'])*100,1); // ratio résutats trouvés/matchs pronostiqués

                    

                 //On définit la couleur du ratio en fonction de sa valeur

                    

                if ($ratio >= 50) { $styleRatio = "style=color:green;width:10%;vertical-align:middle;" ;}

                else if ($ratio >= 25) { $styleRatio = "style=color:orange;width:10%;vertical-align:middle;" ;}

                else if ($ratio < 25) { $styleRatio = "style=color:red;width:10%;vertical-align:middle;" ;}      

                      

                      

                   echo ' <tr>

                        <td style= "vertical-align:middle;width:10%;" class= "text-center"><strong>  '.$clubligue1.' </strong></td>

                        <td class="text-center" style=" width:2%;"><img src = "../img/ligue1/'.$clubligue1bis.'.png" "vertical-align :middle;" ></td>

                        <td style= "vertical-align:middle;width:10%;" class= "text-center">  '.$point.' </td>

                        <td  class="text-center" '.$styleRatio.'<strong>'.$ratio.'%</strong></td>

                        <td class="text-center" style="vertical-align:middle;font-size:0.8em;width:5%;">'.$donnees['nb_resultat'].'</td>

                        <td class="text-center" style="vertical-align:middle;font-size:0.8em;width:10%;" >'.$donnees['nb_prono'].'</td>

                        <td style= "vertical-align:middle;width:5%;" class= "text-center">  '.$bonusTempt.' </td>

                        <td class="text-center" style="vertical-align:middle;font-size:0.8em;width:10%;"><strong class="label label-success">'.$bonusSuccess.' </strong>(<i>'.$pointBonus.'</i>)</td>

                        <td style= "vertical-align:middle;width:3%;" class= "text-center">  '.$prono1.' </td>

                        <td style= "vertical-align:middle;width:3%;" class= "text-center">  '.$pronoN.' </td>

                        <td style= "vertical-align:middle;width:3%;" class= "text-center">  '.$prono2.' </td>



                         </tr>' ;

                    $i++;

                      

                  }

        $stat_indiv->closeCursor();

            

                        

        if($i == 0) echo '<tr><td colspan="3">Pas de match trouvé.</td></tr>';

                             }

                ?>   



                    </tbody>            

                  </table>

                             <i style="font-size:0.8em;color:red">  V=Victoire l'équipe sélectionné, N= Nul , D=défaite </i>  

                     </div>

                </div>

 

                </div>

            </div>

          </div> 

           

 <?php

}

else {

?>                     

           

           

           

           

           

           

           

 <!----------------------------------------------->

 <!------------- STAT INDIVIDUELLES -------------->

 <!----------------------------------------------->



        <div class="col-xs-12 col-md-4  toppad" >

         <div class="panel panel-info" >

            <div class="panel-heading">

              <h3 class="panel-title text-center">Statistiques individuelles</h3>

            </div>

            <div class="panel-body">

              <div class="row">

                 <div class= "table-responsive" style="height: 375px;">       

                  <table class="table table-user-information" style= "font-size:0.8em;" >

                      

                                        <thead>

                                            <th class= "text-center"> Pronostic </th>

                                            <th class= "text-center">Bonus </th>

                                            <th class= "text-center"> Points </th>

                                        </thead>



                    <tbody >

                                             

                    <?php

    $i=0;

                  $stat_indiv = $bdd->query('SELECT A.matchl1_name, A.matchl1_journee, A.matchl1_resultat, pronol1_resultat, pronol1_bonus,round(points) as points,  bonus_trouve FROM match_ligue1 as A LEFT JOIN v_classement_L1 as B

                  ON A.matchl1_name = B.matchl1_name

                  WHERE A.matchl1_name LIKE "%'.$club.'%" AND A.matchl1_resultat <> "" AND (pronol1_membre_id = '.$_SESSION["membre_id"].'

                  OR pronol1_membre_id IS NULL) order by A.matchl1_journee DESC');          

           

                while ($donnees = $stat_indiv->fetch())

                {

                $match = htmlspecialchars($donnees['matchl1_name'], ENT_QUOTES);

                 // On veut récupérer les noms d'équipe

                $pos = strpos($match,'-');

                $equipe1 = strtolower(substr($match,0,$pos - 1)); // Nom de l'équipe 1

                $equipe2 = strtolower(substr($match,$pos+2,strlen($match)-($pos+1))); // Nom de l'équipe 2

                $prono =  htmlspecialchars($donnees['pronol1_resultat'], ENT_QUOTES);

                



                if (((($clubMin == $equipe1) && ($prono=='1'))) || ((($clubMin == $equipe2) && ($prono=='2')) )) {

                    $pronoIndiv = "V" ;

                }  

                else if (((($clubMin == $equipe1) && ($prono=='2'))) || ((($clubMin == $equipe2) && ($prono=='1')) )) {

                    $pronoIndiv = "D" ;

                }  

                else if (((($clubMin == $equipe1) && ($prono=='N'))) || ((($clubMin == $equipe2) && ($prono=='N')) )) {

                    $pronoIndiv = "N" ;

                }  

                else {$pronoIndiv = ""; }

                

                

                $point =  (htmlspecialchars($donnees['points'], ENT_QUOTES) > 0)? htmlspecialchars($donnees['points'], ENT_QUOTES) : '0';

                $bonus = ($donnees['pronol1_bonus'] == 5)? '1' : '0';    

                $colorLigne = ($donnees['pronol1_resultat'] == "")? 'class= "bg-danger"' : '';   

                



                    

                   echo ' <tr '.$colorLigne.' height = "40">

                        <td style= "vertical-align:middle;" class= "text-center">  '.$pronoIndiv.' </td>

                        <td style= "vertical-align:middle;" class= "text-center">  '.$bonus.' </td>

                        <td style= "vertical-align:middle;" class= "text-center">  '.$point.' </td>



                         </tr>' ;

                    $i++;

                     

                }

        $stat_indiv->closeCursor();

            

                        

        if($i == 0) echo '<tr><td colspan="3">Pas de match trouvé.</td></tr>';

       

                ?>   



                    </tbody>            

                  </table>

                             <i style="font-size:0.8em;color:red">  V=Victoire l'équipe sélectionné, N= Nul , D=défaite </i>  

                     </div>

                </div>

 

                </div>

            </div>

          </div>    

           

           

 <!----------------------------------------------->

 <!------------- STAT MATCHS        -------------->

 <!----------------------------------------------->

           

           

        <div class="col-xs-12 col-md-4  toppad" >

         <div class="panel panel-info" >

            <div class="panel-heading">

              <h3 class="panel-title text-center">Matchs</h3>

            </div>

            <div class="panel-body">

              <div class="row">

                 <div class= "table-responsive" style="height: 375px;">       

                  <table class="table table-user-information" style= "font-size:0.8em;" >

                      

                                        <thead>

                                            <th class= "text-center">Journée </th>

                                            <th class= "text-center"> Match </th>

                                            <th class= "text-center"> Résultat </th>

                                        </thead>



                    <tbody >

                                             

                    <?php

    $i=0;

                  $matchResultat = $bdd->query('SELECT matchl1_name, matchl1_journee, matchl1_resultat

                  FROM match_ligue1 

                  WHERE matchl1_name LIKE "%'.$club.'%" AND matchl1_resultat <> ""

                  ORDER BY matchl1_journee DESC');          

           

                while ($donnees = $matchResultat->fetch())

                {

    

                $match = htmlspecialchars($donnees['matchl1_name'], ENT_QUOTES);

                $journee =  htmlspecialchars($donnees['matchl1_journee'], ENT_QUOTES);

                $resultat =  htmlspecialchars($donnees['matchl1_resultat'], ENT_QUOTES);



                 // On veut récupérer les noms d'équipe

                $pos = strpos($match,'-');

                $equipe1 = strtolower(substr($match,0,$pos - 1)); // Nom de l'équipe 1

                $equipe2 = strtolower(substr($match,$pos+2,strlen($match)-($pos+1))); // Nom de l'équipe 2

                

                                if (((($clubMin == $equipe1) && ($resultat=='1'))) || ((($clubMin == $equipe2) && ($resultat=='2')) )) {

                    $resultatMatch = "V" ;

                }  

                else if (((($clubMin == $equipe1) && ($resultat=='2'))) || ((($clubMin == $equipe2) && ($resultat=='1')) )) {

                    $resultatMatch = "D" ;

                }  

                else if (((($clubMin == $equipe1) && ($resultat=='N'))) || ((($clubMin == $equipe2) && ($resultat=='N')) )) {

                    $resultatMatch = "N" ;

                }  

                else {$resultatMatch = ""; }



                   echo ' <tr  height = "40">

                        <td style= "vertical-align:middle;" class= "text-center" >   J'.$journee.' </td>

                        <td style= "vertical-align:middle;" class= "text-center"><img src = "../img/ligue1/'.$equipe1.'.png" "vertical-align :middle;" >   '.$match.' <img src = "../img/ligue1/'.$equipe2.'.png" "vertical-align :middle;" class= "text-center" > </td>

                        <td style= "vertical-align:middle;" class= "text-center "><strong>  '.$resultatMatch.' </strong></td>



                         </tr>' ;

                    $i++;

                     

                }

        $matchResultat->closeCursor();

            

                        

        if($i == 0) echo '<tr><td colspan="3">Pas de match trouvé.</td></tr>';

       

                ?>   



                    </tbody>

                  </table>

                     </div>

                </div>

                </div>

            </div>

          </div>           

           

           

           

           

 <!----------------------------------------------->

 <!------------- STAT GLOBALES  -------------->

 <!----------------------------------------------->

           

           

        <div class="col-xs-12 col-md-4  toppad" >

         <div class="panel panel-info" >

            <div class="panel-heading">

              <h3 class="panel-title text-center">Statistiques globales</h3>

            </div>

            <div class="panel-body">

              <div class="row">

                 <div class= "table-responsive" style="height: 375px;">       

                  <table class="table table-user-information" style= "font-size:0.8em;" >

                      

                                        <thead>

                                            <th class= "text-center"> V </th>

                                            <th class= "text-center"> N </th>

                                            <th class= "text-center"> D </th>

                                            <th class= "text-center">Bonus tentés </th>

                                            <th class= "text-center">Bonus réussis </th>

                                            <th class= "text-center"> Points total </th>

                                        </thead>



                    <tbody >

                                             

                    <?php

    $i=0;

                  $stat_indiv = $bdd->query('SELECT matchl1_name, matchl1_journee, matchl1_resultat, pronol1_resultat, pronol1_bonus,round(sum(points)) as points,  sum(bonus_trouve) as bonus_trouve , 

                      COUNT(CASE WHEN pronol1_resultat = "1" then 1 ELSE NULL END) as "1",

                       COUNT(CASE WHEN pronol1_resultat = "N" then 1 ELSE NULL END) as "N",

                        COUNT(CASE WHEN pronol1_resultat = "2" then 1 ELSE NULL END) as "2",

                         COUNT(CASE WHEN pronol1_bonus = 5 then 1 ELSE NULL END) as "bonus_tente",



COUNT(CASE WHEN (((pronol1_resultat = "1") AND (substr(matchl1_name,1,LOCATE( "-", matchl1_name ) -1) = "'.$club.'"))) OR (((pronol1_resultat = "2") AND (substr(matchl1_name,LOCATE( "-", matchl1_name ) +2, LENGTH(matchl1_name) - (LOCATE( "-", matchl1_name ) +1)) = "'.$club.'"))) then 1 ELSE NULL END) as "V" ,

COUNT(CASE WHEN (((pronol1_resultat = "2") AND (substr(matchl1_name,1,LOCATE( "-", matchl1_name ) -1) = "'.$club.'"))) OR (((pronol1_resultat = "1") AND (substr(matchl1_name,LOCATE( "-", matchl1_name ) +2, LENGTH(matchl1_name) - (LOCATE( "-", matchl1_name ) +1)) = "'.$club.'"))) then 1 ELSE NULL END) as "D", 



COUNT(CASE WHEN (((pronol1_resultat = "N") AND (substr(matchl1_name,1,LOCATE( "-", matchl1_name ) -1) = "'.$club.'"))) OR (((pronol1_resultat = "N") AND (substr(matchl1_name,LOCATE( "-", matchl1_name ) +2, LENGTH(matchl1_name) - (LOCATE( "-", matchl1_name ) +1)) = "'.$club.'"))) then 1 ELSE NULL END) as "N"

      

                  FROM v_classement_L1 WHERE matchl1_name LIKE "%'.$club.'%" AND matchl1_resultat <> ""

                  GROUP BY matchl1_name order by matchl1_journee DESC');          

           

                while ($donnees = $stat_indiv->fetch())

                {

                $prono1 =  htmlspecialchars($donnees['V'], ENT_QUOTES);

                $pronoN =  htmlspecialchars($donnees['N'], ENT_QUOTES);

                $prono2 =  htmlspecialchars($donnees['D'], ENT_QUOTES);

                $point =  htmlspecialchars($donnees['points'], ENT_QUOTES);

                $bonusTempt = htmlspecialchars($donnees['bonus_tente'], ENT_QUOTES);   

                $bonusSuccess = htmlspecialchars($donnees['bonus_trouve'], ENT_QUOTES);   



                   echo ' <tr height = "40">

                        <td style= "vertical-align:middle;" class= "text-center">  '.$prono1.' </td>

                        <td style= "vertical-align:middle;" class= "text-center">  '.$pronoN.' </td>

                        <td style= "vertical-align:middle;" class= "text-center">  '.$prono2.' </td>

                        <td style= "vertical-align:middle;" class= "text-center">  '.$bonusTempt.' </td>

                        <td style= "vertical-align:middle;" class= "text-center">  '.$bonusSuccess.' </td>

                        <td style= "vertical-align:middle;" class= "text-center">  '.$point.' </td>



                         </tr>' ;

                    $i++;

                     

                }

        $stat_indiv->closeCursor();

            

                        

        if($i == 0) echo '<tr><td colspan="3">Pas de match trouvé.</td></tr>';

       

                ?>   



                    </tbody>

                  </table>

                     </div>

                </div>

                </div>

            </div>

          </div>        

           

           

<?php 

}

?>

           

           

    </div> 





    <!-- END # MODAL LOGIN -->

