<?php

/*



Comparaison des pronostics avec les autres membres



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



$titre = 'Comparaison des pronostics sur un match';



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

      

// création de la date du jour en datetime                      

$now = date('Y-m-d H:i:s'); 

$now = new DateTime( $now );

      

$idMatch = $_GET['match'];



// On récupère la date du match, le nom du match, les cotes et le résultat

 $date_Match = $bdd->query('SELECT matchl1_date, matchl1_name, matchl1_cote1, matchl1_cote2, matchl1_coteN,matchl1_resultat  FROM match_ligue1  WHERE matchl1_id = "'.$idMatch.'" LIMIT 1');

 $date_Match = $date_Match->fetch(PDO::FETCH_ASSOC);

 $dateDuMatch = new DateTime( $date_Match['matchl1_date'] ); //date du match

 $nomMatch = $date_Match['matchl1_name']; // nom du match

 $resultatMatch = $date_Match['matchl1_resultat']; // resultat du match

      

// cote vainqueur du match

if ($resultatMatch == "1") {

    $cote= $date_Match['matchl1_cote1'];

}

else if ($resultatMatch == "N") {

    $cote= $date_Match['matchl1_coteN'];

}

else if ($resultatMatch == "2") {

    $cote= $date_Match['matchl1_cote2'];

}

else $cote="";

      

$texte = ($resultatMatch !== "")? '(résultat : '.$resultatMatch.' pour une cote à '.$cote.' )':"(pas de score pour ce match)";      

     

if ($dateDuMatch < $now )

    {

?>



<div class="row">        

    

<?php



//on récupère le nombre de pronostiqueurs sur le match

 $Nb_pronostiqueurs = $bdd->query('SELECT count(pronol1_resultat) as nb  FROM prono_ligue1  WHERE pronol1_match = "'.$nomMatch.'" ');

 $Nb_pronostiqueurs = $Nb_pronostiqueurs->fetch(PDO::FETCH_ASSOC);

 $NbPronostiqueurs = $Nb_pronostiqueurs['nb'];  



?>



<h3 class="text-center"> <?php echo $NbPronostiqueurs ; ?> pronostiqueurs sur le match  </h3><br>     

    

	<!---------------------------------------------------->

	<!-- Partie statistique par résultat  ->

	<!---------------------------------------------------->

	

		           <?php

        // Requête pour récupérer tous les pronostics du match sélectionné 

        $stat_query2 = $bdd->prepare('SELECT "1" as Equipe , count(pronol1_match) as Nombre

                                       FROM prono_ligue1 

                                       WHERE pronol1_match = :match AND pronol1_resultat = "1"

									   GROUP BY pronol1_match 

									   UNION

									   SELECT "N" as Equipe , count(pronol1_match) as Nombre

                                       FROM prono_ligue1 

                                       WHERE pronol1_match = :match AND pronol1_resultat = "N"

									   GROUP BY pronol1_match

									   UNION

									   SELECT "2" as Equipe  , count(pronol1_match) as Nombre

                                       FROM prono_ligue1 

                                       WHERE pronol1_match = :match AND pronol1_resultat = "2"

									   GROUP BY pronol1_match

									   

									   ');

        $stat_query2->execute(array('match' => $nomMatch));

	        ?>



     <div class="col-xs-12  col-lg-3  toppad" >

          <div class="panel panel-info">

            <div class="panel-heading text-center">

        <h3 class="panel-title text-center"><strong>Statistiques par pronostics</strong></h3>

            </div>

            <div class="panel-body">

                <div class="row">

                 <div class="table-responsive" > 

                  <table class="table">



                    <?php

                $i = 0;

                ?> 

                                        <thead>

                                                <th class="text-center"> Résultat </th>

                                                <th class="text-center">  Nombre de pronostics </th>

                                        </thead>

                                        <tbody>

            <?php

            

            while ($stat_query3 = $stat_query2->fetch())

                {

                $pseudo = htmlspecialchars($stat_query3['Equipe'], ENT_QUOTES); 

                $valeur1= htmlspecialchars($stat_query3['Nombre'], ENT_QUOTES);

     

                   echo '<tr> 

                        <td class="text-center">'.$pseudo.'</td>

                        <td class="text-center" >

                        '.$valeur1.'

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

          </div>

   <?php

    $stat_query2->closeCursor();

           ?>    

  

   

 <!----------------------------------------------------------->   

 <!--- PRONOSTIC DES MEMBRES POUR LE MATCH SELECTIONNE     --->  

 <!-----------------------------------------------------------> 

 

     <?php    

        // Requête pour récupérer tous les pronostics du match sélectionné 

        $match_query2 = $bdd->prepare('SELECT membre_pseudo , pronol1_resultat , pronol1_bonus, case when "'.$resultatMatch.'" <> "" AND pronol1_resultat = "'.$resultatMatch.'" then round("'.$cote.'" * pronol1_bonus * 10)  else 0 end as points

                                       FROM prono_ligue1 

                                       INNER JOIN match_ligue1 ON pronol1_match = matchl1_name

                                       INNER JOIN membres ON pronol1_membre_id = membre_id

                                       WHERE matchl1_name = :match ORDER BY pronol1_resultat ');	

        $match_query2->execute(array('match' => $nomMatch));



        ?>

        

     <div class="col-xs-12  col-lg-offset-1 col-lg-4  toppad" >

          <div class="panel panel-info">

            <div class="panel-heading text-center">

        <h3 class="panel-title text-center"><strong><?php echo $nomMatch; ?></strong></h3> <?php echo $texte; ?>

            </div>

            <div class="panel-body">

                <div class="row">

                  <div class="table-responsive" > 

                  <table class="table">



                    <?php

                $i = 0;

                ?> 

                                        <thead>

                                                <th class="text-center">Pseudo </th>

                                                <th class="text-center">Pronostic </th>

                                                <th class="text-center"> Bonus </th>

                                                <th class="text-center"> Points </th>

                                        </thead>

                                        <tbody>

            <?php

            

            while ($match_query3 = $match_query2->fetch())

                {

                $pseudo = htmlspecialchars($match_query3['membre_pseudo'], ENT_QUOTES);

                $valeur1= htmlspecialchars($match_query3['pronol1_resultat'], ENT_QUOTES);

                $bonus= (htmlspecialchars($match_query3['pronol1_bonus'], ENT_QUOTES) == 5)? '<i class="glyphicon glyphicon-ok"></i>': '<i class="glyphicon glyphicon-remove"></i>';

                $point= htmlspecialchars($match_query3['points'], ENT_QUOTES);

     

                   echo '<tr> 

                        <td class="text-center">'.$pseudo.'</td>

                        <td class="text-center">'.$valeur1.'</td>

                        <td class="text-center">'.$bonus.'</td>

                        <td class="text-center">'.$point.'</td>

                        </tr>

                        ';

                        $i++;

                }

                

                if($i == 0) echo '<tr><td colspan="3">Pas de pronostic trouvé.</td></tr>';

                ?>                      

                        

                                        </tbody>

                 </table>

		  </div>

                </div>

              </div>

            </div>

          </div>

   <?php

    $match_query2->closeCursor();

           ?>

    

    

    

	<!---------------------------------------------------->

	<!-- Partie statistique par bonus  ->

	<!---------------------------------------------------->

	

		           <?php

        // Requête pour récupérer tous les bonus du match sélectionné 

        $bonus_query2 = $bdd->prepare('SELECT "Bonus tenté(s)" as Equipe , count(pronol1_bonus) as Nombre

                                       FROM prono_ligue1 

                                       WHERE pronol1_match = :match AND pronol1_bonus = "5"

									   GROUP BY pronol1_match 

									   UNION

									   SELECT "Bonus bien placé(s)" as Equipe , count(pronol1_match) as Nombre

                                       FROM prono_ligue1 

                                       INNER JOIN match_ligue1 ON matchl1_name = pronol1_match

                                       WHERE pronol1_match = :match AND pronol1_bonus = "5" AND pronol1_resultat = matchl1_resultat

									   GROUP BY pronol1_match

									   UNION

									   SELECT "Bonus manqué(s)" as Equipe  , count(pronol1_match) as Nombre

                                       FROM prono_ligue1 

                                       INNER JOIN match_ligue1 ON matchl1_name = pronol1_match

                                       WHERE pronol1_match = :match AND pronol1_bonus = "5" AND pronol1_resultat <> matchl1_resultat

									   GROUP BY pronol1_match

									   

									   ');

        $bonus_query2->execute(array('match' => $nomMatch));

	        ?>



     <div class="col-xs-12  col-lg-offset-1 col-lg-3  toppad" >

          <div class="panel panel-info">

            <div class="panel-heading text-center">

        <h3 class="panel-title text-center"><strong>Statistiques par bonus</strong></h3>

            </div>

            <div class="panel-body">

                <div class="row">

                 <div class="table-responsive" > 

                  <table class="table">



                    <?php

                $i = 0;

                ?> 

                                        <thead>

                                                <th class="text-center"> Résultat </th>

                                                <th class="text-center">  Nombre de pronostics </th>

                                        </thead>

                                        <tbody>

            <?php

            

            while ($bonus_query3 = $bonus_query2->fetch())

                {

                $pseudo = htmlspecialchars($bonus_query3['Equipe'], ENT_QUOTES); 

                $valeur1= htmlspecialchars($bonus_query3['Nombre'], ENT_QUOTES);

     

                   echo '<tr> 

                        <td class="text-center">'.$pseudo.'</td>

                        <td class="text-center" >

                        '.$valeur1.'

                        </td>

                        

                        </tr>

                        ';

                        $i++;

                }

                

                if($i == 0) echo '<tr><td colspan="3">Pas de bonus tenté pour ce match.</td></tr>';

                ?>                      

                        

                                        </tbody>

                 </table>

		   </div>

                </div>

              </div>

            </div>

          </div>

   <?php

    $bonus_query2->closeCursor();

           ?>        

    



        </div>



<?php  

        

    }

      else 

        {?> 

    <h1> Vous ne pouvez pas encore voir les résultats des autres</h1>

    <h3> Si vous n'avez pas fait vos pronostics, Cliquez sur le bouton ci-dessous pour les faire</h3><br>

      

  <div class="text-center">                    

    <a type="button" class="btn btn-sm btn-success" href= "<?php echo ROOTPATH; ?>/pronostic/prono_ligue1.php">Faire mes pronostics</a>

  </div>

      

<?php 

        }

      ?>

        </div>

      </div>





    <!-- END # MODAL LOGIN -->

		

<?php

include('../includes/bas.php');

?>

