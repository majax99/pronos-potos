<?php

/*



Classement gÃ©nÃ©rale des pronostiqueurs



*/



session_start();

header('Content-type: text/html; charset=utf-8');

include('.././includes/config.php');



/********Actualisation de la session...**********/



include('.././includes/fonctions.php');

actualiser_session();



/* Si le membre n'est pas connectÃ©, on le renvoie sur l'index */

if(!isset($_SESSION['membre_id']))

{

	header('Location: '.ROOTPATH.'/index.php');

	exit();

}

/********Fin actualisation de session...**********/



/********EntÃªte et titre de page*********/



$titre = 'Classement';



include('.././includes/haut.php'); //contient le doctype, et head.



/**********Fin entÃªte et titre***********/

?>



<?php

include('.././includes/col_gauche.php');

?>





        

<div class="main" style= "background-image : url(../img/Euro_2016/maillot_france.jpg) ;background-repeat : no-repeat; background-size: cover;">



        <!-- Corps de page

    ================================================== -->

<div class="container">

    <h3 class="text-center" style = "color : white">Classement des pronostics pour la saison 2016-2017 <i style="font-size:0.7em">(hors bonus)</i> </h3>

    <hr>

    <div class="row">

        <?php 

        // CrÃ©ation de la date de MAJ du classement

            $date_MAJ= $bdd->query('SELECT MAX(matchl1_date_MAJ) as date_MAJ FROM match_ligue1 LIMIT 1');

            $date_MAJ = $date_MAJ->fetch(PDO::FETCH_ASSOC);

        

            $date = new DateTime($date_MAJ['date_MAJ']);

        

        $date_MAJ_class = '<em style="font-size:0.8em;">Mis Ã  jour le '.$date->format('d/m/Y Ã  H\hi').' </em> ' ;

        

        // CrÃ©ation du nombre de matchs jouÃ©s

            $nb_match= $bdd->query('SELECT count(*) as nb_match FROM match_ligue1 WHERE matchl1_resultat <> "" ');

            $nb_match = $nb_match->fetch(PDO::FETCH_ASSOC);

    

        $nb_match_class = $nb_match['nb_match'] ;        

   /************************************************************************************************/
   /* ON COMPARE LE CLASSEMENT GENERAL ACTUEL AVEC LE CLASSEMENT DE LA JOURNEE PRECEDENTE **********/
    /************************************************************************************************/
 
/* on veut la journée qui précède celle pour laquelle un score a été indiqué */
$journeepassee = $bdd->query('SELECT max(matchl1_journee) as matchl1_journee FROM match_ligue1 where matchl1_resultat <> "" ');
$journeepassee = $journeepassee->fetch(PDO::FETCH_ASSOC);
$lastJournee = $journeepassee['matchl1_journee'];         
       
$classementJ1= $bdd->query ('SELECT membre_pseudo , round(sum(coalesce(points,0))) - round(sum(coalesce(points_bonus,0))) as points
FROM membres  INNER JOIN v_classement_L1 as d ON membre_id = pronol1_membre_id 
WHERE matchl1_journee < '.$lastJournee.'
GROUP BY membre_pseudo 
ORDER BY points DESC , bonus_trouve DESC');
    
    
$rang=0;
$i=1;
$point=0;
            while ($classementJ1_2 = $classementJ1->fetch())
            { 
                if ($classementJ1_2['points'] != $point) { $rang=$i; } //en cas d'égalité entre 2 équipes, elles ont le même rang
                
                $membre= $classementJ1_2['membre_pseudo'];
                $classementG_J1[$membre]['rang']=$rang;   // on enregistre les données dans un tableau pour créer le classement
                $classementG_J1[$membre]['point']=$classementJ1_2['points'] ; // on enregistre les données dans un tableau pour connaitre ls points au dernier classement général
                $i++;
                $point=$classementJ1_2['points'];
            }
unset($i);
unset($point);
unset($rang);
//print_r($classementG_J1);
//echo $classementG_J1['PYB']['point'];
//echo $journeeEnCours;    

    /************************************************************************************************/
    /****************************** CREATION DU CLASSEMENT GENERAL **********************************/
    /************************************************************************************************/
        ?>

        <div class = "col-lg-offset-1 col-lg-10 col-xs-12" >

        <div class="panel panel-primary filterable ">

            <div class="panel-heading">

                <h3 class="panel-title">Classement gÃ©nÃ©ral aprÃ¨s <?php echo $nb_match_class ; ?> match(s) <span class= "pull-right hidden-xs"><?php echo $date_MAJ_class ; ?>  </span></h3>

            </div>

       <div class="table-responsive">



            <table class="table">

                

                    <?php

             /* $classement_query = $bdd->query('SELECT membre_pseudo ,membre_avatar ,membre_club, round(sum(coalesce(points,0))) as points,
                                                    sum(coalesce(bonus_trouve,0))   as bonus_trouve, count(membre_pseudo) as nb_resultat
                                                  FROM membres
                                                  INNER JOIN v_classement_L1 ON membre_id = pronol1_membre_id
                                                  WHERE points <> 0 
                                                  GROUP BY membre_pseudo
                                                  ORDER BY points  DESC , bonus_trouve DESC');*/

 $classement_query= $bdd->query ('SELECT membre_pseudo ,membre_avatar ,membre_club, round(sum(coalesce(points,0))) - round(sum(coalesce(points_bonus,0)))  as points, sum(coalesce(resultat_trouve,0)) as nb_resultat, sum(coalesce(prono_realise,0)) as nb_prono 
FROM membres  INNER JOIN v_classement_L1 as d ON membre_id = pronol1_membre_id 
GROUP BY membre_pseudo 
ORDER BY points DESC , bonus_trouve DESC');  

$rang=0;
$i=1;
$point=0;
                ?>
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th class= "text-center" style="vertical-align:middle;">Nom</th>
                        <th class= "text-center"></th>
                        <th class= "text-center" style="vertical-align:middle;">Points</th>
                        <th class= "text-center" style="vertical-align:middle;">ratio <i style="font-size:0.7em;">(1) / (2)<i/>  </th>
                        <th class= "text-center" style="vertical-align:middle;">RÃ©sultats <br> trouvÃ©s <i style="font-size:0.7em;">(1)</i></th>
                        <th class= "text-center" style="vertical-align:middle;">matchs <br> pronostiquÃ©s <i style="font-size:0.7em;">(2)</i></th>   
                    </tr>
                </thead>
                <tbody>
                    

                <?php
                
                while ($donnees = $classement_query->fetch())
                {
 
 //On définit le rang dans le classement général 
if ($donnees['points'] != $point) { $rang=$i; } //en cas d'égalité entre 2 équipes, elles ont le même rang
$membre2= $donnees['membre_pseudo'];
$point=$donnees['points'];   
    
  //On définit les icones de coupe
$icone = ($rang== 1 ||$rang == 2 || $rang == 3) ? '<img src = "'.ROOTPATH.'/img/icone_classement/'.$rang.'.png" style="vertical-align : -3px;"  alt="Image_classement">' : '';   

  
$avatar = '<a class="glyphicon glyphicon-user" href = "'.ROOTPATH.'/membres/voir_profil.php?pseudo='. $donnees['membre_pseudo'].'" ></a>';
                    
$avatar2 = ($donnees['membre_club'] != '') ? '<a href = "'.ROOTPATH.'/membres/voir_profil.php?pseudo='. $donnees['membre_pseudo'].'"><img src="'.ROOTPATH.'/img/ligue1/'.htmlspecialchars(strtolower($donnees['membre_club']), ENT_QUOTES).'.png"  alt="Image_classement"/></a>' : '<a class="glyphicon glyphicon-user" href = "'.ROOTPATH.'/membres/voir_profil.php?pseudo='. $donnees['membre_pseudo'].'" ></a>';
                
$ratio= number_format(($donnees['nb_resultat'] / $donnees['nb_prono'])*100,1); // ratio rÃ©sutats trouvÃ©s/matchs pronostiquÃ©s
                    
//On dÃ©finit la couleur du ratio en fonction de sa valeur
                    
if ($ratio >= 50) { $styleRatio = "style=color:green;" ;}
else if ($ratio >= 25) { $styleRatio = "style=color:orange;" ;}
else if ($ratio < 25) { $styleRatio = "style=color:red;" ;}

// On définit l'évolution au classement (rang)
$classDifRang = (isset($classementG_J1[$membre2]['rang']))? $classementG_J1[$membre2]['rang'] - $rang  : 0;

if ($classDifRang > 0) { $difRang= '<i style="font-size:0.7em;">(</i><i style="font-size:0.6em;color:green" class= "glyphicon glyphicon-arrow-up"></i><i style="font-size:0.7em;">+'.$classDifRang.')</i>' ;}
else if ($classDifRang < 0) { $difRang= '<i style="font-size:0.7em;">(</i><i style="font-size:0.6em;color:red" class= "glyphicon glyphicon-arrow-down"></i><i style="font-size:0.7em;">'.$classDifRang.')</i>' ;}
else if ($classDifRang == 0) { $difRang= '<i style="font-size:0.7em;">(</i><i style="font-size:0.7em;color:orange">=</i><i style="font-size:0.7em;">)</i>' ;}                    
else {$difRang='';}  
// On définit l'évolution au classement (points)
$classDifPoint = (isset($classementG_J1[$membre2]['point']))?  $point - $classementG_J1[$membre2]['point'] : $point;
                  
                    ?>
                  
                        <tr>
                        <td class= "text-center" style="width:5%;"  ><?php echo $icone ; ?></td>
                        <td class= "text-left"   style="width:8%;font-size:1.1em;"><strong><?php echo $rang ; ?></strong><?php echo $difRang; ?></td>
                        <td class="text-center" ><?php echo $donnees['membre_pseudo'] ; ?></td>
                        <td class="text-center" style=" width:10px;"><?php echo $avatar2 ; ?></td>
                        <td class="text-center"  ><strong><?php echo $donnees['points']; ?></strong><i style="font-size:0.6em;">(+<?php echo $classDifPoint; ?>)</i> </td>
                        <td class="text-center" <?php echo $styleRatio; ?> > <strong><?php echo $ratio.'%' ; ?></strong></td>
                        <td class="text-center" style="font-size:0.8em;width:10%;"><?php echo $donnees['nb_resultat'] ; ?></td>
                        <td class="text-center" style="font-size:0.8em;width:10%;" ><?php echo $donnees['nb_prono'] ; ?></td>

                        </tr>

                    <?php

                        $i++;

                }

                

                if($i == 0) echo '<tr><td colspan="3">Pas de membre trouvÃ©.</td></tr>';

                ?>

                </tbody>

            </table>

           </div>

        </div>

       </div>

    </div>

</div>

</div>



    <!-- END # MODAL LOGIN -->

		

<?php

include('../includes/bas.php');

?>

