<?php

/*



Classement g√©n√©rale des pronostiqueurs



*/



session_start();

header('Content-type: text/html; charset=utf-8');

include('.././includes/config.php');



/********Actualisation de la session...**********/



include('.././includes/fonctions.php');

actualiser_session();



/* Si le membre n'est pas connect√©, on le renvoie sur l'index */

if(!isset($_SESSION['membre_id']))

{

	header('Location: '.ROOTPATH.'/index.php');

	exit();

}

/********Fin actualisation de session...**********/



/**********Fin ent√™te et titre***********/



// on r√©cup√®re le num√©ro de la journ√©e dans une variable

    $j = intval($_GET['j']);



// on indique la bonne √©criture en fonction de la journ√©e

if ($j == 1) {$journee_ecriture = $j.'√®re journ√©e'; }

else  {$journee_ecriture =  $j.'√®me journ√©e';}

/* on veut la journÈe qui prÈcËde celle pour laquelle un score a ÈtÈ indiquÈ */
$journeepassee = $bdd->query('SELECT max(matchl1_journee) as matchl1_journee FROM match_ligue1 where matchl1_resultat <> "" ');
$journeepassee = $journeepassee->fetch(PDO::FETCH_ASSOC);
$lastJournee = $journeepassee['matchl1_journee'];   

/* classement gÈnÈral avant la journÈe en cours */
$classementG= $bdd->query ('SELECT membre_pseudo , round(sum(coalesce(points,0))) as points
FROM membres  INNER JOIN v_classement_L1 as d ON membre_id = pronol1_membre_id 
WHERE matchl1_journee < '.$lastJournee.'
GROUP BY membre_pseudo 
ORDER BY points DESC , bonus_trouve DESC');

$rang=0;
$i=1;
$point=0;
 //On dÈfinit le rang dans le classement gÈnÈral 
 while ($classementG2 = $classementG->fetch())   
            { 
 if ($classementG2['points'] != $point) { $rang=$i; } //en cas d'ÈgalitÈ entre 2 Èquipes, elles ont le mÍme rang
                
 $membre= $classementG2['membre_pseudo'];
 $rangGeneral[$membre]=$rang;   // on enregistre les donnÈes dans un tableau pour crÈer le classement
                
 $i++;
 $point=$classementG2['points'];
            }
unset($i);
unset($rang);
unset($point);
//print_r($rangGeneral)
?>







        <!-- Corps de page

    ================================================== -->



    <div class="row">


        <?php 
        // CrÈation du nombre de matchs jouÈs
            $nb_match= $bdd->query('SELECT count(*) as nb_match FROM match_ligue1 WHERE matchl1_resultat <> "" AND matchl1_journee = "'.$j.'"  ');
            $nb_match = $nb_match->fetch(PDO::FETCH_ASSOC);
    
        $nb_match_class = $nb_match['nb_match'] ;  
        ?>

        <div class = "col-lg-offset-1 col-lg-10 col-xs-12" >

        <div class="panel panel-primary filterable ">

            <div class="panel-heading">

                <h3 class="panel-title">Classement sur la <?php echo $journee_ecriture ?> apr√®s <?php echo $nb_match_class ; ?> match(s) </h3>

            </div>

       <div class="table-responsive">



            <table class="table">

                

                    <?php

               /* $classementh_query = $bdd->query('SELECT membre_pseudo ,membre_avatar ,membre_club, round(sum(coalesce(points,0))) as points,

                                                    sum(coalesce(bonus_trouve,0))   as bonus_trouve, count(membre_pseudo) as nb_resultat

                                                  FROM membres

                                                  INNER JOIN v_classement_L1 ON membre_id = pronol1_membre_id

                                                  WHERE points <> 0 and matchl1_journee = "'.$j.'" 

                                                  GROUP BY membre_pseudo

                                                  ORDER BY points  DESC , bonus_trouve DESC');*/

                 $classement_query= $bdd->query ('SELECT membre_pseudo ,membre_avatar ,membre_club, round(sum(coalesce(points,0))) as points, sum(coalesce(bonus_trouve,0)) as bonus_trouve, sum(coalesce(resultat_trouve,0)) as nb_resultat, sum(coalesce(prono_realise,0)) as nb_prono , round(sum(coalesce(points_bonus,0))) as pts_bonus
FROM membres  INNER JOIN v_classement_L1 as d ON membre_id = pronol1_membre_id 
WHERE matchl1_journee = "'.$j.'"
GROUP BY membre_pseudo 
ORDER BY points DESC , bonus_trouve DESC'); 



                $i = 0;

                ?>

                <thead>

                    <tr>

                        <th></th>

                        <th></th>

                        <th class= "text-center" style="vertical-align:middle;">Nom</th>
                        <th class= "text-center"></th>
                        <th class= "text-center" style="vertical-align:middle;">Points</th>
                        <th class= "text-center" style="vertical-align:middle;">ratio <i style="font-size:0.7em;">(1) / (2)<i/>  </th>
                        <th class= "text-center" style="vertical-align:middle;">R√©sultats <br> trouv√©s <i style="font-size:0.7em;">(1)</i></th>
                        <th class= "text-center" style="vertical-align:middle;">matchs <br> pronostiqu√©s <i style="font-size:0.7em;">(2)</i></th>
                        <th class= "text-center" style="vertical-align:middle;">Bonus <br> trouv√©s (pts)</th>  

                    </tr>

                </thead>

                <tbody>

                    



                <?php

                

                while ($donnees = $classement_query->fetch())

                {

                

                    $avatar = '<a class="glyphicon glyphicon-user" href = "'.ROOTPATH.'/membres/voir_profil.php?pseudo='. $donnees['membre_pseudo'].'" ></a>';

                    $icone = ($i== 0 ||$i == 1 || $i == 2) ? '<img src = "'.ROOTPATH.'/img/icone_classement/'.($i + 1).'.png" style="vertical-align : -3px;"  alt="Image_classement">' : '';  

		      $avatar2 = ($donnees['membre_club'] != '') ? '<a href = "'.ROOTPATH.'/membres/voir_profil.php?pseudo='. $donnees['membre_pseudo'].'"><img src="'.ROOTPATH.'/img/ligue1/'.htmlspecialchars(strtolower($donnees['membre_club']), ENT_QUOTES).'.png"  
			alt="Image_classement"/></a>' : '<a class="glyphicon glyphicon-user" href = "'.ROOTPATH.'/membres/voir_profil.php?pseudo='. $donnees['membre_pseudo'].'" ></a>';
                                 
                    $ratio= number_format(($donnees['nb_resultat'] / $donnees['nb_prono'])*100,1); // ratio r√©sutats trouv√©s/matchs pronostiqu√©s
		      $pts_bonus = $donnees['pts_bonus'];
                    
                    //On d√©finit la couleur du ratio en fonction de sa valeur
                    
                    if ($ratio >= 50) { $styleRatio = "style=color:green;" ;}
                    else if ($ratio >= 25) { $styleRatio = "style=color:orange;" ;}
                    else if ($ratio < 25) { $styleRatio = "style=color:red;" ;}

                    // On dÈfinit le classement gÈnÈral de la personne
                    $rang = (isset($rangGeneral[$donnees['membre_pseudo']])) ? '<i style="font-size:0.7em"> ('.$rangGeneral[$donnees['membre_pseudo']].')</i>' : '';
                    
                    ?>
                  
                        <tr>
                        <td class= "text-center"  ><?php echo $icone ; ?></td>
                        <td class= "text-left"   ><strong><?php echo ($i + 1) ; ?></strong></td>
                        <td class="text-center" ><?php echo $donnees['membre_pseudo'] ; echo $rang;?></td>
                        <td class="text-center" style=" width:10px;"><?php echo $avatar2 ; ?></td>
                        <td class="text-center"  ><?php echo $donnees['points']; ?></td>
                        <td class="text-center" <?php echo $styleRatio; ?> > <strong><?php echo $ratio.'%' ; ?></strong></td>
                        <td class="text-center" style="font-size:0.8em;width:10%;"><?php echo $donnees['nb_resultat'] ; ?></td>
                        <td class="text-center" style="font-size:0.8em;width:10%;" ><?php echo $donnees['nb_prono'] ; ?></td>
                        <td class="text-center" style="font-size:0.8em;width:15%;"><?php echo '<strong class="label label-default">'.$donnees['bonus_trouve'].' </strong>(<i>'.$pts_bonus.'</i>)' ; ?></td>

                        </tr>

                    <?php

                        $i++;

                }

                

                if($i == 0) echo '<tr><td colspan="3">Pas de membre trouv√©.</td></tr>';

                ?>

                </tbody>

            </table>

           </div>

        </div>

       </div>

    </div>



    <!-- END # MODAL LOGIN -->

