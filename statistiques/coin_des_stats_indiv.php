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



include('.././includes/haut.php'); //contient le doctype, et head.



/**********Fin entête et titre***********/

?>



<?php

include('.././includes/col_gauche.php');

?>





        

    <div class= "main" style ="background-image: linear-gradient(rgb(255, 255, 255), rgb(12, 33, 97));">



        <!-- Corps de page

    ================================================== -->

<div class="container">



<h1 class="text-center"> Mes statistiques </h1>





       <div class="row" ><br>

        <div class="col-xs-12 col-md-offset-2 col-md-8  toppad" >

         <div class="panel panel-info" >

            <div class="panel-heading">

              <h1 class="panel-title text-center">Statistiques sur les pronostics</h1>

            </div>

            <div class="panel-body">



                    

<h5><strong> TOP 3 du plus grand nombre de points sur une journée</strong> </h5>

 <?php

    // PLUS GRAND NOMBRE DE POINT EN 1 JOURNEE 



 $point_journee = $bdd->query('SELECT membre_pseudo , round(sum(coalesce(points,0))) as points , matchl1_journee

FROM membres  INNER JOIN v_classement_L1 as d ON membre_id = pronol1_membre_id WHERE membre_id = '.$_SESSION['membre_id'].'

GROUP BY membre_pseudo, matchl1_journee ORDER BY points DESC LIMIT 3   ');

                

$i=1;

                    while ($donnees = $point_journee->fetch())

                {  

                    if ($i==1) {$label='<strong class="label label-primary"';}

                    else if ($i==2) {$label='<strong class="label label-success"';}

                    else if ($i==3) {$label='<strong class="label label-danger"';}

                    echo $label.'>'.$donnees["membre_pseudo"].'</strong> : '.$donnees["points"].' pts sur la <em>J'.$donnees["matchl1_journee"].'</em><br>';

                    $i++;

                }

    

$point_journee->closeCursor();

    ?>

 <br>     

                

<h5><strong> TOP 3 du plus grand nombre de points sur une journée <i> (hors bonus)</i></strong> </h5>

 <?php

/****************** PLUS GRAND NOMBRE DE POINT EN 1 JOURNEE (hors bonus) ***************************************************/



 $point_journee = $bdd->query('SELECT membre_pseudo , round(sum(coalesce(points,0))) - round(sum(coalesce(points_bonus,0))) as points , matchl1_journee

FROM membres  INNER JOIN v_classement_L1 as d ON membre_id = pronol1_membre_id  WHERE membre_id = '.$_SESSION['membre_id'].'

GROUP BY membre_pseudo, matchl1_journee ORDER BY points DESC LIMIT 3   ');

                

$i=1;

                    while ($donnees = $point_journee->fetch())

                {  

                    if ($i==1) {$label='<strong class="label label-primary"';}

                    else if ($i==2) {$label='<strong class="label label-success"';}

                    else if ($i==3) {$label='<strong class="label label-danger"';}

                    echo $label.'>'.$donnees["membre_pseudo"].'</strong> : '.$donnees["points"].' pts sur la <em>J'.$donnees["matchl1_journee"].'</em><br>';

                    $i++;

                }

    

$point_journee->closeCursor();

    ?>

 <br> 

       

                <h5><strong> Plus grand nombre de résultats trouvés sur une journée <i style="font-size:0.8em">(pour 10 matchs pronostiqués)</i></strong> </h5>

 <?php

/********************** MEILLEUR RATIO SUR 1 JOURNEE  *****************************/     

    
 $ratio_journee = $bdd->query('SELECT membre_pseudo, matchl1_journee, trouve, realise FROM (

SELECT membre_pseudo,sum(resultat_trouve) as trouve , matchl1_journee, sum(prono_realise) as realise

FROM `v_classement_L1` INNER JOIN membres ON pronol1_membre_id = membre_id

WHERE membre_id = '.$_SESSION['membre_id'].'

group by matchl1_journee , pronol1_membre_id

HAVING realise = 10

ORDER BY trouve DESC ) T 

LIMIT 3 ');

$i=0;

                    while ($donnees = $ratio_journee->fetch())

                {  

                    echo '<strong class="label label-success">'.$donnees["membre_pseudo"].'</strong> : '.$donnees["trouve"].' résultats trouvés sur la <em>J'.$donnees["matchl1_journee"].'</em><br>';

                    $i++;

                

                

                }

 if ($i==0) {echo 'Les statistiques ne sont pas encore disponibles';}   

                

$ratio_journee->closeCursor();

    ?>

 <br> 

                

                <h5><strong> TOP 3 du plus grand nombre de points obtenu sur un match</strong> </h5>

 <?php

/********************** PLUS GRAND NOMBRE DE POINTS SUR 1 MATCH  *****************************/     

          

                

 $pointMatch_journee = $bdd->query('

SELECT round(points) as points , membre_pseudo, matchl1_name, matchl1_journee, coteVainqueur, CASE WHEN @prev_value = points THEN @rank_count WHEN @prev_value := points THEN @rank_count := @rank_count2 END AS rank , @rank_count2 := @rank_count2 + 1 



FROM (select @prev_value := NULL,@rank_count := 1,@rank_count2 := 1 ) A, ( SELECT round(points) as points , membre_pseudo, matchl1_name, matchl1_journee, coteVainqueur FROM `v_classement_L1` INNER JOIN membres ON pronol1_membre_id = membre_id WHERE membre_id = '.$_SESSION["membre_id"].' ORDER BY points DESC) T HAVING rank < 4');

$i=1;

                    while ($donnees = $pointMatch_journee->fetch())

                {  

                    if ($i==1) {$label='<strong class="label label-primary"';}

                    else if ($i==2) {$label='<strong class="label label-success"';}

                    else if ($i==3) {$label='<strong class="label label-danger"';}

                    echo $label.'>'.$donnees["membre_pseudo"].'</strong> : '.$donnees["points"].' pts sur la <em>J'.$donnees["matchl1_journee"].'</em> sur le match <strong>'.$donnees["matchl1_name"].'</strong><i style = "font-size:0.8em"> (cote : '.$donnees["coteVainqueur"].')</i><br>';

                    $i++;

                }

 if ($i==0) {echo 'Les statistiques ne sont pas encore disponibles';}   

                

$pointMatch_journee->closeCursor();

    ?>

 <br> 

                

                <h5><strong> Plus grand nombre de fois 1er au classement de la journée </strong> </h5>

 <?php

/********************** PLUS GRAND NOMBRE DE FOIS PREMIER  *****************************/     





// requête pour définir le mebre le plus de fois premier

$i=0;                

  $class_Premier_journee = $bdd->query('

select class_membre_pseudo,  nb

FROM (

SELECT class_membre_pseudo, count(class_membre_pseudo) as nb

from classement_journee

where class_rang_journee= 1 AND class_membre_id = '.$_SESSION["membre_id"].'

group by class_membre_pseudo) AS T

WHERE nb = (

select max(nb)

FROM (

SELECT class_membre_pseudo, count(class_membre_pseudo) as nb

from classement_journee 

where class_rang_journee= 1 AND class_membre_id = '.$_SESSION["membre_id"].'

group by class_membre_pseudo) AS U )');

                    while ($donnees = $class_Premier_journee->fetch())

                {  

                    echo '<strong class="label label-success">'.$donnees["class_membre_pseudo"].'</strong> : '.$donnees["nb"].' fois <br>';

                    $i++;

                }

 if ($i==0) {echo 'Les statistiques ne sont pas encore disponibles';}   

                

$class_Premier_journee->closeCursor();

    ?>               



 <br> 

                

                <h5><strong> Plus grand nombre de fois dans le TOP 3 au classement de la journée </strong> </h5>

 <?php

/********************** PLUS GRAND NOMBRE DE FOIS dans le TOP 3 au classement journée  *****************************/    

// requête pour définir le mebre le plus de fois premier

$i=0;                

  $class_Premier_journee = $bdd->query('

select class_membre_pseudo,  nb

FROM (

SELECT class_membre_pseudo, count(class_membre_pseudo) as nb

from classement_journee

where class_rang_journee<4 AND class_membre_id = '.$_SESSION["membre_id"].'

group by class_membre_pseudo) AS T

WHERE nb = (

select max(nb)

FROM (

SELECT class_membre_pseudo, count(class_membre_pseudo) as nb

from classement_journee

where class_rang_journee<4 AND class_membre_id = '.$_SESSION["membre_id"].'

group by class_membre_pseudo) AS U )');

                    while ($donnees = $class_Premier_journee->fetch())

                {  

                    echo '<strong class="label label-success">'.$donnees["class_membre_pseudo"].'</strong> : '.$donnees["nb"].' fois <br>';

                    $i++;

                }

 if ($i==0) {echo 'Les statistiques ne sont pas encore disponibles';}   

                

$class_Premier_journee->closeCursor();

    ?>                               



 <br> 

                

                <h5><strong> Plus grand nombre de fois dans le TOP 5 au classement de la journée </strong> </h5>

 <?php

/********************** PLUS GRAND NOMBRE DE FOIS dans le TOP 5 au classement journée  *****************************/    

// requête pour définir le mebre le plus de fois premier

$i=0;                

  $class_Premier_journee = $bdd->query('

select class_membre_pseudo,  nb

FROM (

SELECT class_membre_pseudo, count(class_membre_pseudo) as nb

from classement_journee

where class_rang_journee<6 AND class_membre_id = '.$_SESSION["membre_id"].'

group by class_membre_pseudo) AS T

WHERE nb = (

select max(nb)

FROM (

SELECT class_membre_pseudo, count(class_membre_pseudo) as nb

from classement_journee

where class_rang_journee<6 AND class_membre_id = '.$_SESSION["membre_id"].'

group by class_membre_pseudo) AS U )');

                    while ($donnees = $class_Premier_journee->fetch())

                {  

                    echo '<strong class="label label-success">'.$donnees["class_membre_pseudo"].'</strong> : '.$donnees["nb"].' fois <br>';

                    $i++;

                }

 if ($i==0) {echo 'Les statistiques ne sont pas encore disponibles';}   

                

$class_Premier_journee->closeCursor();

    ?>    

                

                

                

                </div>

            </div>

          </div>   

             

           

           

    </div> <!-- fin div du row -->

    

</div>

</div>



    <!-- END # MODAL LOGIN -->

		

<?php

include('../includes/bas.php');

?>

  