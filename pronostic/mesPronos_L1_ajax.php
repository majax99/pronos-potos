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





/********Ent√™te et titre de page*********/



$titre = 'pronostic ligue 1';



include('.././includes/haut.php'); //contient le doctype, et head.



/**********Fin ent√™te et titre***********/

?>



<?php

include('.././includes/col_gauche.php');

?>





        

    <div class= "main" style ="background-image: linear-gradient(rgb(255, 255, 255), rgb(12, 33, 97));">

        



        <!-- Corps de page

    ================================================== -->

  <div class="container-fluid">



<script>
/*
function showUser(str) {

    if (str == "") {

        document.getElementById("prono_l1").innerHTML = "";

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

        xmlhttp.open("GET","mesPronos_L1.php?j="+str,true);

        xmlhttp.send();

        document.getElementById("selectbasic").removeChild(document.getElementById("selectjour"));

    }

}  
 */  

$(document).ready(function(){   
j=$("#journeeEnCours").html();
$.get('mesPronos_L1.php?j='+j, function( data ) {
    $('#prono_l1').html( data );
});
     
$("#selectbasic").change(function () {
    $("#selectjour").remove();
j = $("#selectbasic option:selected").val();
$.get('mesPronos_L1.php?j='+j, function( data ) {
    $('#prono_l1').html( data );
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
      

 // on r√©cup√®re les journ√©es pour lesuquelles il y a des matchs renseign√©s en base   



$journee_query = $bdd->query('SELECT DISTINCT matchl1_journee  FROM match_ligue1  WHERE matchl1_journee <> "" ');    



?>



  

    <div class="row">


    <h2 class="text-center"> Mes r√©sultats </h2>
     <h4 class="text-center"><em> S√©lectionnez une journ√©e <br> pour voir vos pronostics</em></h4>


   <form>     

       <div class="col-md-offset-4 col-md-4">

    <select id="selectbasic" name="selectbasic" class="form-control" >  

    <option id= "selectjour" >Choisissez une journ√©e </option>    

        

<?php

            while ($journee_query2 = $journee_query->fetch())

            { 

$journee = $journee_query2['matchl1_journee'];

             

// on indique la bonne √©criture en fonction de la journ√©e

if ($journee == 1) {$journee_ecriture = $journee.'√®re journ√©e'; }

else if ($journee == 2) {$journee_ecriture =  $journee.'nd journ√©e'; }

else  {$journee_ecriture =  $journee.'√®me journ√©e';}

                echo '<option value="'.$journee.'">'.$journee_ecriture.'</option>' ;          

            }

?>

        

    </select>

  </div>

</form>

        <br><br><br>

        <div id="prono_l1"></div>



        </div>



        </div>

      </div>

<!-- DIV qui va nous permettre de rÈcupÈrer la valeur de la derniËre journÈe avec un match fini -->
<div id="journeeEnCours" style="display:none;">
           <?php echo $journeeEnCours; ?>
</div>



    <!-- END # MODAL LOGIN -->

		

<?php

include('../includes/bas.php');

?>

