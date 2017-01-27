<?php

/*



Table des pronostics pour les phases de poules



*/



session_start();

header('Content-type: text/html; charset=utf-8');

include('../includes/config.php');



/********Actualisation de la session...**********/



include('../includes/fonctions.php');

actualiser_session();





/********Fin actualisation de session...**********/







if(!isset($_SESSION['membre_id']))

{

	header('Location: '.ROOTPATH.'/index.php');

	exit();

}





/********Entête et titre de page*********/



$titre = 'pronostic ligue 1';



include('../includes/haut.php'); //contient le doctype, et head.



/**********Fin entête et titre***********/

?>



<?php

include('../includes/col_gauche.php');

?>





        

    <div class= "main" style ="background-image: linear-gradient(rgb(255, 255, 255), rgb(12, 33, 97));">

        



        <!-- Corps de page

    ================================================== -->

  <div class="container-fluid">



<script>

/*function showUser(str) {
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
        xmlhttp.open("GET","statistiques_club.php?club="+str,true);
        xmlhttp.send();
        document.getElementById("selectbasic").removeChild(document.getElementById("selectjour"));
    }
}     */
    
$(document).ready(function(){   
str = "Inviduelle Club";
$.get('statistiques_club.php?club='+str, function( data ) {
    $('#prono_l1').html(data);
});
     
$("#selectbasic").change(function () {
    $("#selectjour").remove();
str = $("#selectbasic option:selected").val();
$.get('statistiques_club.php?club='+str, function( data ) {
    $('#prono_l1').html( data );
});
    })
});   
    

</script>     

  





 <?php 

/*************************************************************************************/

/******************* VERIFICATION DES PRONOSTICS *************************************/

/*************************************************************************************/   

      

 // on récupère les journées pour lesuquelles il y a des matchs renseignés en base   



$arrayligue1 = array('Angers', 'Bastia', 'Bordeaux' , 'Caen' , 'Dijon', 'Guingamp', 'Lille' , 'Lorient' ,'Lyon', 'Marseille', 'Metz', 'Monaco' , 'Montpellier' ,'Nancy', 'Nantes', 'Nice'  ,'ParisSG', 'Rennes', 'Saint Etienne' , 'Toulouse');      

?>



    <div class="row">

    <h2 class="text-center"> Statistiques par club </h2>

     <h4 class="text-center"><em> Sélectionnez un club <br> pour voir ses statistiques</em></h4>



   <form>     

       <div class="col-md-offset-4 col-md-4">

    <select id="selectbasic" name="selectbasic" class="form-control" )">

    <option id= "selectjour" >Choisissez un club </option>    

        

<?php

     foreach( $arrayligue1 as $ligue1 ) {

          echo "<option value='$ligue1'>$ligue1</option>"  ;

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





    <!-- END # MODAL LOGIN -->

		

<?php

include('../includes/bas.php');

?>

