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


/********Entête et titre de page*********/

$titre = 'pronostic ligue 1';

include('.././includes/haut.php'); //contient le doctype, et head.

/**********Fin entête et titre***********/
?>

<?php
include('.././includes/col_gauche.php');
?>


        
<div class="main" >
        

        <!-- Corps de page
    ================================================== -->
  <div class="container-fluid">

<?php
  
$id = $_SESSION['membre_id'];
      
?>
      
<script>
/*function showUser(str) {
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
}     */
    
 $(document).ready(function(){   
id=<?php echo $id ?>;
$.get('stats_graphique.php?id='+id, function( data ) {
    $('#graphique').html( data );
});
     
$("#selectbasic").change(function () {
    $("#selectid").remove();
id= $("#selectbasic option:selected").val();
$.get('stats_graphique.php?id='+id, function( data ) {
    $('#graphique').html( data );
});
    })

});    
    
</script>     
  
<h1 class="text-center"> Les statistiques graphiques </h1><br>   

 <?php 
/*************************************************************************************/
/******************* VERIFICATION DES PRONOSTICS *************************************onchange="showUser(this.value)"/
/*************************************************************************************/   

/* on récupère les membres du site */
$membre_site = $bdd->query('SELECT membre_id, membre_pseudo FROM membres  ORDER BY membre_pseudo');
       
?>

  
    <div class="row">
     <h5 class="text-center" > <strong>Sélectionnez un pseudo pour visualiser ses graphiques </strong></h5>


   <form>     
       <div class="col-md-offset-4 col-md-4">
    <select id="selectbasic" name="selectbasic" class="form-control" >
    <option id= "selectid" >Pseudo </option>    
        
<?php
            while ($membre_site2 = $membre_site->fetch())
            { 
$id_membre = $membre_site2['membre_id'];
$pseudo_membre = $membre_site2['membre_pseudo'];
                
echo '<option  value = '.$id_membre.'>'.$pseudo_membre.' </option>';              
                
            }

?>
        
    </select>
  </div>
</form>
<br> <br><br>
        <div id="graphique"></div>

        </div>

        </div>
      </div>


    <!-- END # MODAL LOGIN -->
		
<?php
include('../includes/bas.php');
?>
