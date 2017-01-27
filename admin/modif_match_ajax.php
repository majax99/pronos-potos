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


/********Entête et titre de page*********/

$titre = 'pronostic ligue 1';

include('.././includes/haut.php'); //contient le doctype, et head.

/**********Fin entête et titre***********/
?>

<?php
include('.././includes/col_gauche.php');
?>


        
    <div class= "main" >
        

        <!-- Corps de page
    ================================================== -->
  <div class="container-fluid">

<script>
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
        xmlhttp.open("GET","match_l1_modif.php?j="+str,true);
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

$journee_query = $bdd->query('SELECT DISTINCT matchl1_journee  FROM match_ligue1  ');    

?>

  
    <div class="row">
     <h3 class="text-center"> Sélectionnez une journée et <br> cliquez sur Update pour modifier un match</h3>


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
        <div id="prono_l1"></div>

        </div>

        </div>
      </div>


    <!-- END # MODAL LOGIN -->
		
<?php
include('../includes/bas.php');
?>
