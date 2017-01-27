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






/*
if(!isset($_SESSION['membre_id']))

{

	header('Location: '.ROOTPATH.'/index.php');

	exit();

}*/

/* On vÈrifie si la personne a dÈj‡ rÈpondu au questionnaire */
 $questio_realise = $bdd->query('SELECT count(*) as nb  FROM questionnaire  WHERE question_membre = '.$_SESSION['membre_id'].' ');
 $questio_realise = $questio_realise->fetch(PDO::FETCH_ASSOC);

$date_finale = '2017-01-13 20:45:00';
$date_finale = new DateTime( $date_finale );

$now = date('Y-m-d H:i:s'); 
$now = new DateTime( $now );

$questio = $questio_realise['nb'];


if(!isset($_SESSION['membre_id']))
{
	header('Location: '.ROOTPATH.'/index.php');
	exit();
}

else if ($questio ==0 && $now < $date_finale)
{
	header('Location: '.ROOTPATH.'/pronostic/question_MiSaison.php');
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

        xmlhttp.open("GET","ligue1.php?j="+str,true);

        xmlhttp.send();

        document.getElementById("selectbasic").removeChild(document.getElementById("selectjour"));

    }

}    */ 

     $(document).ready(function(){   

j=$("#journeeEnCours").html();
jEnCours=$("#journeeEnCours").html();
$.get('ligue1.php?j='+j+'&jEnCours='+jEnCours, function( data ) {
    $('#prono_l1').html( data );
});

$("#selectbasic").change(function () {
    $("#selectjour").remove();
    
j = $("#selectbasic option:selected").val();
jEnCours=$("#journeeEnCours").html();
$.get('ligue1.php?j='+j+'&jEnCours='+jEnCours, function( data ) {
    $('#prono_l1').html( data );
});
    })
});

</script>     

  





 <?php 

/*************************************************************************************/

/******************* VERIFICATION DES PRONOSTICS *************************************/

/*************************************************************************************/   

/* on rÈcupËre le numÈro de la journÈe en cours */
 $date_prochainMatch = $bdd->query('SELECT min(matchl1_date) as dateMin, matchl1_journee FROM (select matchl1_date, matchl1_journee from match_ligue1 where matchl1_resultat = "" order by matchl1_date LIMIT 10) l ');
 $date_prochainMatch = $date_prochainMatch->fetch(PDO::FETCH_ASSOC);
 $journeeEnCours = $date_prochainMatch['matchl1_journee'] ;   
      

 // on r√©cup√®re les journ√©es pour lesuquelles il y a des matchs renseign√©s en base   



$journee_query = $bdd->query('SELECT DISTINCT matchl1_journee  FROM match_ligue1  WHERE matchl1_journee <> "" AND matchl1_resultat = "" LIMIT 7');    



?>



  

    <div class="row">

     <h2 class="text-center"> Faire mes pronostics </h2>
     <h4 class="text-center"><em> Indiquez vos pronostics et <br> cliquez sur "Valider les pronostics"</em></h4>

   <form>     

       <div class="col-md-offset-4 col-md-4">

    <select id="selectbasic" name="selectbasic" class="form-control" >  <!-- onchange="showUser(this.value)"-->

    <option id= "selectjour" >Choisissez une journ√©e </option>    

        

<?php

            while ($journee_query2 = $journee_query->fetch())

            { 

$journee = $journee_query2['matchl1_journee'];

             

// on indique la bonne √©criture en fonction de la journ√©e

if ($journee == 1) {$journee_ecriture = $journee.'√®re journ√©e'; }

else if ($journee == 2) {$journee_ecriture =  $journee.'nd journ√©e'; }

else  {$journee_ecriture =  $journee.'√®me journ√©e';}

                    echo '<option value="'.$journee.'" >'.$journee_ecriture.'</option>' ; 
     

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

<div id="journeeEnCours" style="display:none;">
           <?php echo $journeeEnCours; ?>
</div>



    <!-- END # MODAL LOGIN -->

		

<?php

include('../includes/bas.php');

?>

