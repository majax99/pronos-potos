

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



/*************************************************************************************************/

/*********************** ON VIDE LA TABLE CLASSEMENT_JOURNEE ET ON LA RECREE *********************/

/*************************************************************************************************/

                

$req = $bdd->prepare('TRUNCATE TABLE classement_journee');

$req->execute();

$req->closeCursor();                  



// On récupère le numéro de la journée en cours               

  $date_prochainMatch = $bdd->query('SELECT matchl1_journee FROM (select max(matchl1_journee)  as matchl1_journee  from match_ligue1 where matchl1_resultat <> "" order by matchl1_date DESC LIMIT 1) l ');

 $date_prochainMatch = $date_prochainMatch->fetch(PDO::FETCH_ASSOC);

$journeeEnCours = $date_prochainMatch['matchl1_journee'] ;                



// On boucle pour avoir le classement sur chaque journee    

for ($journee = 1; $journee <= $journeeEnCours; $journee++)

{                 

            

 $class_journee = $bdd->query('

    SELECT round(points) as points,membre_pseudo,  pronol1_membre_id , matchl1_journee, pts_bonus,

    CASE WHEN (@points = points) THEN @rank

    WHEN @points:=points THEN  @rank :=@rank2 

    ELSE @rank :=@rank2  END AS rn,

@rank2 := @rank2 + 1 

from   (SELECT @rank:= 1,@rank2 := 1,@points := 0) s,

  (select sum(points) as points, sum(points_bonus) as pts_bonus, pronol1_membre_id , matchl1_journee,membre_pseudo

from v_classement_L1 INNER JOIN membres on membre_id = pronol1_membre_id

WHERE matchl1_journee='.$journee.'

group by pronol1_membre_id, matchl1_journee

ORDER BY matchl1_journee , points DESC ) AS P');

$i=0;

                    while ($donnees = $class_journee->fetch())

                {  



                $membre= $donnees['membre_pseudo'];

                $rang = $donnees['rn'];

                $points = $donnees['points'];

                $bonus = $donnees['pts_bonus'];

                $id = $donnees['pronol1_membre_id'];

                        

                // on enregistre les données dans un tableau pour créer le classement

                $class_j[$i]['rang']=$rang;   

                $class_j[$i]['points']=$points;  

                $class_j[$i]['pseudo']=$membre;   

                $class_j[$i]['id']=$id;   

                $class_j[$i]['bonus']=$bonus;

                

                // on fait le cumul de points         

                if (!isset($pts)) {$pts=[];}        

                if (!array_key_exists($membre, $pts)) {$pts[$membre] = $points ;}    

                else {$pts[$membre]= $pts[$membre] + $points;}    

                $i++;

                }

     

$class_journee->closeCursor();

//$comptage_tableau= count($class_j[$journee]);

//echo $i;

//echo     $class_j[$journee][1]['rang'] ;    



// On insère des données que s'il y en a à insérer

     if ($i>0) {   

for ($lignes = 0; $lignes < $i; $lignes++)

                { 

$pseudo = $class_j[$lignes]['pseudo'];

$insertion_class = $bdd->prepare( 'INSERT INTO  classement_journee (class_membre_id, class_membre_pseudo, class_pts_journee, class_ptsbonus_journee,class_pts_cumul, class_rang_journee, class_journee)  

VALUES(:id, :pseudo, :ptsJournee, :pts_bonus,:cumul, :rang, :journee)');



$insertion_class->execute(array(

	'id' => $class_j[$lignes]['id'],

	'pseudo' => $pseudo,

	'ptsJournee' => $class_j[$lignes]['points'],

	'pts_bonus' => $class_j[$lignes]['bonus'],

	'cumul' => $pts[$pseudo],

	'rang' => $class_j[$lignes]['rang'],

    'journee' => $journee

	));         

                }

            }

unset($class_j);

//unset($pts);

}





/********************************************************************************************************/

/*********************** ON VIDE LA TABLE CLASSEMENTGENERAL_JOURNEE ET ON LA RECREE *********************/

/********************************************************************************************************/





$req = $bdd->prepare('TRUNCATE TABLE classementgeneral_journee');

$req->execute();

$req->closeCursor();  

// On boucle pour avoir le classement sur chaque journee    

for ($journee = 1; $journee <= $journeeEnCours; $journee++)

{     

    

    $cumulPts_journee = $bdd->query('

SELECT membre_id, membre_pseudo,matchl1_journee , class_ptsbonus_journee, class_pts_cumul,class_pts_journee

FROM v_membres_journee  LEFT JOIN classement_journee 

ON membre_id = class_membre_id AND matchl1_journee = class_journee

WHERE matchl1_journee ='.$journee.'

ORDER BY matchl1_journee , class_pts_cumul DESC');

    

        $i=0;

       $tab_pseudo = array();

                while ($cumulPts_journee2 = $cumulPts_journee->fetch())

            {

                

                //$journee=$cumulPts_journee2['matchl1_journee'];

                $id = $cumulPts_journee2['membre_id'];

                $pts_cumul=$cumulPts_journee2['class_pts_cumul'];

                $pseudo = $cumulPts_journee2['membre_pseudo'];

                $pts_journee = $cumulPts_journee2['class_pts_journee'];

     

 if ($pts_journee == ""){$pts_journee=0;}                    

 if ($journee==1 && $pts_cumul == ""){$pts_cumul=0;} 

 if($journee > 1 && $pts_cumul == "") { $pts_cumul = $classG_j[$pseudo][$journee-1]['point_cumul'] + $pts_journee;  }



 $classG_j[$pseudo][$journee]['point_cumul']=$pts_cumul;

 $classG_j[$pseudo][$journee]['point']=$pts_journee;

 $classG_j[$pseudo][$journee]['pseudo']=$pseudo;

 $classG_j[$pseudo][$journee]['id']=$id; 

 $tab_pseudo[] = $pseudo;

                    

 $i++;  

            }

$cumulPts_journee->closeCursor();

                

                

// On insère des données que s'il y en a à insérer

    if ($i>0) {   

foreach ($tab_pseudo as $value){

                { 

$insertion_class = $bdd->prepare( 'INSERT INTO  classementgeneral_journee (classgen_membre_id, classgen_membre_pseudo, classgen_ptscumul, classgen_ptsjournee, classgen_journee)  

VALUES(:id, :pseudo, :cumul,:pts,:journee)');



$insertion_class->execute(array(

	'id' =>  $classG_j[$value][$journee]['id'],

	'pseudo' => $classG_j[$value][$journee]['pseudo'],

	'cumul' => $classG_j[$value][$journee]['point_cumul'],

	'pts' =>  $classG_j[$value][$journee]['point'],

    'journee' => $journee

	));        

                }

            }              

//unset($classG_j); 

unset($tab_pseudo);

      }

}

    ?>

			<h1 class="text-center">Les données ont été mises &agrave; jour !</h1>
			<p class="text-center">	Vous pouvez retourner sur l'accueil en cliquant <a href="<?php echo ROOTPATH; ?>/accueil.php">ici</a>.
			</p><br/>

<?php

include('../includes/bas.php');

?>