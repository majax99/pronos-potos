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



$titre = 'pronostic ligue 1';



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



<script>

function showUser(str) {

    if (str == "") {

        document.getElementById("resultat_L1").innerHTML = "";

        return;

    } else { 

        if (window.XMLHttpRequest) {

            // code for IE7+, Firefox, Chrome, Opera, Safari

            xmlhttp = new XMLHttpRequest();

        } else {

            // code for IE6, IE5

            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

        }

        xmlhttp.onreadystatechange = function() {

            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                document.getElementById("prono_l1").innerHTML = xmlhttp.responseText;

            }

        };

        xmlhttp.open("GET","resultat_L1.php?j="+str,true);

        xmlhttp.send();

        document.getElementById("selectbasic").removeChild(document.getElementById("selectjour"));

    }

}     

    

</script>     
  





 <?php 

/*************************************************************************************/

/******************* VERIFICATION DES PRONOSTICS *************************************/

/*************************************************************************************/   

      

 // on récupère les journées pour lesuquelles il y a des matchs renseignés en base   



$journee_query = $bdd->query('SELECT DISTINCT matchl1_journee FROM match_ligue1 
WHERE (matchl1_journee <> "" AND matchl1_resultat = "") OR (matchl1_resultat <> "" AND matchl1_date_MAJ > (NOW() - INTERVAL 3 DAY)) LIMIT 7');    



?>



  

    <div class="row">

     <h3 class="text-center"> Indiquez le score des matchs et <br> cliquez sur "Valider les résultats"</h3>



   <form>     

       <div class="col-md-offset-4 col-md-4">

    <select id="selectbasic" name="selectbasic" class="form-control" onchange="showUser(this.value)">

    <option id= "selectjour" >Choisissez une journée </option>    

        

<?php

            while ($journee_query2 = $journee_query->fetch())

            { 

$journee = $journee_query2['matchl1_journee'];

             

// on indique la bonne écriture en fonction de la journée

if ($journee == 1) {$journee_ecriture = $journee.'ère journée'; }

else if ($journee == 2) {$journee_ecriture =  $journee.'nd journée'; }

else  {$journee_ecriture =  $journee.'ème journée';}

                echo '<option value="'.$journee.'">'.$journee_ecriture.'</option>' ;          

            }

?>

        

    </select>

  </div>

</form>

        <br><br><br>

        <div id="prono_l1"></div>



        </div>





<!-------------------------------------------------------------------->     

<!-------------------- MAJ DES RESULTATS EN BASE ---------------------->  

<!-------------------------------------------------------------------->     

    

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

          $pos = strpos($cle,'!');

          $match_nom=substr($cle,0,$pos);

          $match_nom = str_replace('_', ' ', $match_nom);

          $match_journee= substr($cle,$pos + 1,strlen($cle)-$pos); 



          $bonus = 1; 

          $req = $bdd->prepare('UPDATE match_ligue1 SET matchl1_resultat=  :resultat , matchl1_date_MAJ= NOW()   WHERE matchl1_name = :match ');

                $req->execute(array('resultat' => $$cle,

                                    'match' => $match_nom ));

                $req->closeCursor();

                

            }

          

      ?>



        </div>

      </div>



    <!-- END # MODAL LOGIN -->

		

<?php

include('../includes/bas.php');

?>

