<?php

/*



Question de mi saison sur les clubs qui descendent et le meilleur buteur



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

	header('Location: '.ROOTPATH.'/pronostic/prono_ligue1.php');

	exit();

}

/********Fin actualisation de session...**********/



/********Entête et titre de page*********/



$titre = 'Inscription';



include('.././includes/haut.php'); //contient le doctype, et head.



/**********Fin entête et titre***********/

?>











<?php

include('.././includes/col_gauche.php');

?>



<script>


 $(document).ready(function(){   
flux='Ligue1';
$.get('RSS/getrss.php?q='+flux, function( data ) {
    $('#rssOutput').html( data );
});
     
$("#selectbasic").change(function () {
    $("#selectflux").remove();
flux = $("#selectbasic option:selected").val();
$.get('RSS/getrss.php?q='+flux, function( data ) {
    $('#rssOutput').html( data );
});
    })

}); 


</script>

<br>

<?php

$rss_query = $bdd->query('SELECT *  FROM club_l1 WHERE club_name = "'.$_SESSION['membre_club'].'" ');    

?>

<div class="row" >
            <div class="col-xs-12 col-md-5 toppad" >
         <div class="panel panel-info" >   
            <div class="panel-heading">
                


                <!-- ON AFFICHE LES USERS DANS UN TOOLTIP -->

<form>

    <select id="selectbasic" name="selectbasic" class="form-control" >
    <option id= "selectflux" >Flux d'actu - sélectionner le fil d'actu </option>
    <option value = "Ligue1" >Ligue 1 </option>   
        
<?php
            while ($rss_query2 = $rss_query->fetch())
            { 
$rss1=$rss_query2['club_rss1'];
$rss2=$rss_query2['club_rss2'];
             
 if ($rss1 != "")    { echo '<option value="'.$rss1.'">'.$_SESSION['membre_club'].' - Actu 1 </option>' ;}        
 if ($rss2 != "")    { echo '<option value="'.$rss2.'">'.$_SESSION['membre_club'].' - Actu 2 </option>' ;}     
   
            }

$rss_query->closeCursor();
?>
    </select>
</form>   
            

     
            </div>

<div id="list_de_trucs" class="table-responsive">
<table class="table table-responsive">
<div id="rssOutput" style = "height: 300px;word-wrap: break-word;margin-left:20px;margin-right:10px;margin-top:10px" >
</div>
</table>
</div>
</div>
</div>
</div>
</div>
		<?php

		include('.././includes/bas.php');

       // session_destroy();

		?>