<?php

/*

Permet de vérifier que les pronostics sont valides(phase finale)



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



$titre = 'Vérification des pronostics (poules)';



include('.././includes/haut.php'); //contient le doctype, et head.



/**********Fin entête et titre***********/

?>



<?php

include('.././includes/col_gauche.php');

?>





        

<div class="main">



        <!-- Corps de page

    ================================================== -->

  <div class="container-fluid">



<?php  

  

$variables = $_POST;

// création de la date du jour en datetime                      

$now = date('Y-m-d H:i:s'); 

$now = new DateTime( $now );

// Boucle qui va insérer les données dans la table prono_ligue1  





            $variables = $_POST;

      while (list($cle,$valeur) = each($variables))

            {



          $$cle=$valeur; 

          

      if ($cle != "checkboxes") {  

          $pos = strpos($cle,'!');

          $match_nom=substr($cle,0,$pos);

          $match_nom = str_replace('_', ' ', $match_nom);

          $match_journee= substr($cle,$pos + 1,strlen($cle)-$pos);} 

          



            /*echo $cle;*/

          

          /*echo $match_nom;*/

              /*      echo $match_nom;

          echo $match_journee;

          echo $$cle;*/



      

//On modifie la table

            if ($$cle != 5)

            { 

                $verif_prono = $bdd->prepare('SELECT pronol1_journee FROM prono_ligue1 WHERE pronol1_membre_id = :id AND pronol1_journee = :journee AND pronol1_match = :match LIMIT 1');

              $verif_prono->execute(array('id' => $_SESSION['membre_id'],

                                                   'match' => $match_nom,

                                                   'journee' => $match_journee));

              $verif_prono = $verif_prono->fetch(PDO::FETCH_ASSOC);

                

                if (!$verif_prono) {

           

            $bonus = 1;    

           $insertion = $bdd->prepare( 'INSERT INTO prono_ligue1 (pronol1_membre_id, pronol1_match, pronol1_journee,pronol1_resultat,pronol1_bonus, pronol1_resdateMAJ )  

                VALUES(:id, :match, :journee, :resultat, :bonus, NOW())');



                $insertion->execute(array(

	                   'id' => $_SESSION['membre_id'],

	                   'match' => $match_nom,

	                   'journee' => $match_journee,

	                   'resultat' => $$cle,

                       'bonus' => $bonus));

                $insertion->closeCursor();

            } 

             

            else {

                $bonus = 1; 

                $req = $bdd->prepare('UPDATE prono_ligue1 SET 

                pronol1_resultat=  :resultat , pronol1_bonus = :bonus, pronol1_resdateMAJ= NOW()   WHERE pronol1_match = :match  AND pronol1_membre_id= :id ');

                $req->execute(array('resultat' => $$cle,

                                    'match' => $match_nom,

                                    'id' => $_SESSION['membre_id'],

                                    'bonus' => $bonus ));

                $req->closeCursor();

                

            }

                

            }

            else if ($$cle == 5)

            {

                $bonus = 5; 

                $req2 = $bdd->prepare('UPDATE prono_ligue1 SET pronol1_bonus = :bonus,pronol1_bonusdateMAJ= NOW()   WHERE pronol1_match = :match  AND pronol1_membre_id= :id ');

                $req2->execute(array('match' => $match_nom,

                                     'id' => $_SESSION['membre_id'],

                                     'bonus' => $bonus ));

                $req2->closeCursor();



            }

           }

      

   

    $club = strtolower($_SESSION['membre_club']);



      

// Ouvre un dossier et compte le nombre de fichiers

$directory = ABSPATH."/img/clubL1/".$club."/Valid_prono/"; // dir location

if (glob($directory . "*.*") != false)

{

 $filecount = count(glob($directory . "*.*"));

}

else

{

 $filecount = 0;

}



  

 if ($filecount != 0) {   



    // Création d'un nombre aléatoire entre 1 et 2 

    $chiffre_rand = rand(1, $filecount);       

     

    echo '<h2>les pronostics sont validés</h2>

    <h5> Cliquez <a href = "'.ROOTPATH.'/accueil.php" >ici</a> pour revenir à l\'accueil</h5><br>

    <img class= "img-responsive" src="'.ROOTPATH.'/img/clubL1/'.$club.'/Valid_prono/'.$club.''.$chiffre_rand.'.jpg" >';

      }  

      

 else {

    echo '<h2>les pronostics sont validés</h2>

    <h5> Cliquez <a href = "'.ROOTPATH.'/accueil.php" >ici</a> pour revenir à l\'accueil</h5><br>';     

 }

      ?>

        </div>

      </div>





    <!-- END # MODAL LOGIN -->

		

<?php

include('../includes/bas.php');

?>

