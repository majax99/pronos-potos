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

if(!isset($_SESSION['membre_id']))

{

	header('Location: '.ROOTPATH.'/index.php');

	exit();

}





/********Ent√™te et titre de page*********/



$titre = 'pronostic ligue 1';



include('.././includes/haut.php'); //contient le doctype, et head.



/**********Fin ent√™te et titre***********/

?>



<?php

include('.././includes/col_gauche.php');

?>





        

<div class="main" style= "background-image : url(../img/Euro_2016/maillot_france.jpg) ;background-repeat : no-repeat; background-size: cover;">

        



        <!-- Corps de page

    ================================================== -->

  <div class="container-fluid">



<script>

/*
function showUser(str) {

    if (str == "") {

        document.getElementById("classement_l1_journee").innerHTML = "";

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

                document.getElementById("classement_l1_journee").innerHTML = xmlhttp.responseText;

            }

        };

        xmlhttp.open("GET","classement_journee.php?j="+str,true);

        xmlhttp.send();

        document.getElementById("selectbasic").removeChild(document.getElementById("selectjour"));

    }

}     
*/
   

 $(document).ready(function(){   
j=$("#journeeEnCours").html();
$.get('classement_journee.php?j='+j, function( data ) {
    $('#classement_l1_journee').html( data );
});
     
$("#selectbasic").change(function () {
    $("#selectjour").remove();
j = $("#selectbasic option:selected").val();
$.get('classement_journee.php?j='+j, function( data ) {
    $('#classement_l1_journee').html( data );
});
    })

});   

</script>     

  





 <?php 

/*************************************************************************************/

/******************* VERIFICATION DES PRONOSTICS *************************************/

/*************************************************************************************/   

/* on rÈcupËre la derniËre journÈe o˘ un score a ÈtÈ indiquÈ */
  $date_prochainMatch = $bdd->query('SELECT matchl1_journee FROM (select matchl1_journee from match_ligue1 where matchl1_resultat <> "" order by matchl1_date DESC LIMIT 1) l ');
 $date_prochainMatch = $date_prochainMatch->fetch(PDO::FETCH_ASSOC);
$journeeEnCours = $date_prochainMatch['matchl1_journee'] ;   
      

 // on r√©cup√®re les journ√©es pour lesuquelles il y a des matchs avec des scores  



$journee_query = $bdd->query('SELECT DISTINCT matchl1_journee  FROM match_ligue1 WHERE matchl1_resultat <> "" ORDER BY matchl1_journee DESC   ');    



?>



  

    <div class="row">

     <h3 class="text-center" style = "color:white"> S√©lectionnez une journ√©e </h3>





   <form>     

       <div class="col-md-offset-4 col-md-4">

    <select id="selectbasic" name="selectbasic" class="form-control">

    <option id= "selectjour" >Choisissez une journ√©e </option>    

        

<?php

            while ($journee_query2 = $journee_query->fetch())

            { 

$journee = $journee_query2['matchl1_journee'];

             

// on indique la bonne √©criture en fonction de la journ√©e

if ($journee == 1) {$journee_ecriture = $journee.'√®re journ√©e'; }

else  {$journee_ecriture =  $journee.'√®me journ√©e';}

                echo '<option value="'.$journee.'">'.$journee_ecriture.'</option>' ;          

            }

?>

        

    </select>

  </div>

</form>

<br> <br><br>

        <div id="classement_l1_journee"></div>



        </div>



        </div>

      </div>

<div id="journeeEnCours" style="display:none;">
           <?php echo $journeeEnCours; ?>
</div>




    <!-- END # MODAL LOGIN -->

		

<?php

include('../includes/bas.php');

?>

