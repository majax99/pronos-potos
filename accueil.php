<?php

/*

Accueil du site.



Le membre une fois connect√© peut se diriger vers les diff√©rents menus



*/



session_start();

header('Content-type: text/html; charset=utf-8');

include('includes/config.php');



/********Actualisation de la session...**********/



include('includes/fonctions.php');

actualiser_session();



/********Fin actualisation de session...**********/



/********Ent√™te et titre de page*********/



$titre = 'Accueil';

include('includes/haut.php'); //contient le doctype, et head.



/**********Fin ent√™te et titre***********/

?>


<?php      

if(!isset($_SESSION['membre_id'])) 

{

   	header('Location: '.ROOTPATH.'/index.php');

	exit();



}



?>



<?php

include('includes/col_gauche.php');    

?>



  

<div class="main" style= "background-image : url(img/Euro_2016/stade1bis.jpg) ; background-repeat : no-repeat;background-size: cover;">

        <!-- Corps de page

    ================================================== -->

    





    <div class="container">



      <div class="jumbotron row"  id="accueil">

          <h1 class="text-center">Bienvenue <?php echo $_SESSION['membre_pseudo']; ?> sur pronos-potos </h1><br>

        <img class=" col-md-offset-1 col-md-3 hidden-sm hidden-xs img-circle"  src="./img/logo_pronospotos.png" alt="Accueil "> 
      <!--  <p class="col-sm-12 col-md-offset-2 col-md-5" style= "font-family:Verdana;"><strong>Les nouveautÈs du site : </strong> <br> Mise en place du coin des statistiques avec les statistiques par club<br>
            <strong>Prochainement :</strong> <br>
          - Statistiques individuelles et globales<br>
          - Statistiques par journÈe </p> -->        
          
        <div class="col-xs-12  col-md-8  " >
         <div class="panel" >
            <div class="panel-body" style = "word-wrap: break-word;">
              <div class="row">
                 <div class= "table-responsive" style="height: 200px;overflow-y: scroll;">                  
                    <?php
    $i=0;

                $news_site = $bdd->query('SELECT * from news_site  ORDER BY news_date_MAJ DESC LIMIT 20 ');
    

           
                while ($donnees = $news_site->fetch())
                {
    
                $news = $donnees['news_texte'];
                $date_news =  new datetime ($donnees['news_date']);
                $type_news = $donnees['news_type'];
   
if ($type_news == 'MAJ cote')  {
    $label_news = '<span class="label label-success">';
}    
else if ($type_news == 'nouvelles fonctionnalit√©s')  {
    $label_news = '<span class="label label-primary">';
}
else if ($type_news == 'Information')  {
    $label_news = '<span class="label label-warning">';
}
else if ($type_news == 'Rappel')  {
    $label_news = '<span class="label label-danger">';
}
else if ($type_news == 'Autre')  {
    $label_news = '<span class="label label-default">';
}
else {$label_news = '<span class="label label-default">' ;}

                   echo  $label_news.' le '.date_format($date_news,'d/m/Y H:i').'</span>  ' .$news.'<br>' ;
                    $i++;
                     
                }
        $news_site->closeCursor();
            
                        
        if($i == 0) echo '<tr><td colspan="3">Pas de news.</td></tr>';
       
                ?>   

                    
                  
                     </div>
                </div>
                </div>
            </div>
          </div>

      </div>

        

 <!-- PROCHAINS MATCHS -->  

       <div class="row"><br>

        <div class="col-xs-12 col-md-5  toppad" >
         <div class="panel panel-info" >
            <div class="panel-heading">
              <h3 class="panel-title ">Prochains matchs</h3>
            </div>
            <div class="panel-body" style="padding-top: 0;padding-bottom:0;">
              <div class="row">
                 <div class= "table-responsive" style="height: 375px;">       
                  <table class="table table-user-information" style= "font-size:0.8em;">
                    <tbody >
                                             
                    <?php
    $i=0;

                $proch_match_query = $bdd->query('SELECT * from match_ligue1 where matchl1_resultat = "" and   matchl1_date <>"0000-00-00" ORDER BY matchl1_date LIMIT 10 ');
    
        
           
                while ($donnees = $proch_match_query->fetch())
                {
    
                $match = htmlspecialchars($donnees['matchl1_name'], ENT_QUOTES);
                $journee =  htmlspecialchars($donnees['matchl1_journee'], ENT_QUOTES);
                 // On veut rÈcupÈrer les noms d'Èquipe
                $pos = strpos($match,'-');
                $equipe1 = strtolower(substr($match,0,$pos - 1)); // Nom de l'Èquipe 1
                $equipe2 = strtolower(substr($match,$pos+2,strlen($match)-($pos+1))); // Nom de l'Èquipe 2
                $date = new datetime($donnees['matchl1_date']);

                   echo ' <tr >
                        <td style= "vertical-align:middle;"> Le '.date_format($date,'d/m').' - '.date_format($date,'H\hi').' </td> 
                        <td style= "vertical-align:middle;"><img src = "./img/ligue1/'.$equipe1.'.png" "vertical-align :middle;">   '.$match.' <img src = "./img/ligue1/'.$equipe2.'.png" "vertical-align :middle;"> </td>
                        <td style= "vertical-align:middle;">   J'.$journee.' </td>
                         </tr>' ;
                    $i++;
                     
                }
        $proch_match_query->closeCursor();
            
                        
        if($i == 0) echo '<tr><td colspan="3">Pas de membre trouvÈ.</td></tr>';
       
                ?>   

                    </tbody>
                  </table>
                     </div>
                </div>
                </div>
            </div>
          </div>

   
 <!---------------------------------------------------------------------------------------->
  <!------------------------          CLASSEMENT JOURNEE       ----------------------------->
  <!---------------------------------------------------------------------------------------->     




<?php
    
    /* on rÈcupËre la derniËre journÈe o˘ un score a ÈtÈ indiquÈ */
  $date_prochainMatch = $bdd->query('SELECT matchl1_journee FROM (select matchl1_journee from match_ligue1 where matchl1_resultat <> "" order by matchl1_date DESC LIMIT 1) l ');
 $date_prochainMatch = $date_prochainMatch->fetch(PDO::FETCH_ASSOC);
$journeeEnCours = $date_prochainMatch['matchl1_journee'] ;  

    
?>
       <div class="row" ><br>
        <div class="col-xs-12 col-md-offset-1 col-md-5" >
         <div class="panel panel-info"  >
            <div class="panel-heading">
              <h3 class="panel-title ">Classement sur la derni√®re journ√©e (J<?php echo $journeeEnCours ;?>)</h3>
            </div>
            <div class="panel-body" style="padding-top: 0;padding-bottom:0;">
              <div class="row">
                 <div class= "table-responsive" style="height: 375px;overflow-y: scroll;">       
                  <table class="table table-user-information" style= "font-size:0.8em;" >
  
<?php                      
                     $classement_query= $bdd->query ('SELECT membre_pseudo ,membre_avatar ,membre_club, round(sum(coalesce(points,0))) as points, sum(coalesce(bonus_trouve,0)) as bonus_trouve, sum(coalesce(resultat_trouve,0)) as nb_resultat, sum(coalesce(prono_realise,0)) as nb_prono , round(sum(coalesce(points_bonus,0))) as pts_bonus
FROM membres  INNER JOIN v_classement_L1 as d ON membre_id = pronol1_membre_id 
WHERE matchl1_journee = "'.$journeeEnCours.'"
GROUP BY membre_pseudo 
ORDER BY points DESC , bonus_trouve DESC'); 
                
                $i = 0;
                ?>
                <thead>
                    <tr>
                        <th></th>
                        <th class= "text-center" style="vertical-align:middle;">Nom</th>
                        <th class= "text-center" style="vertical-align:middle;">Points</th>
                        <th class= "text-center" style="vertical-align:middle;">R√©sultats <br> trouv√©s </th>
                        <th class= "text-center" style="vertical-align:middle;">Bonus <br> trouv√©s(pts)</th>   
                    </tr>
                </thead>                  
         <tbody>
                    
                <?php
                
                while ($donnees = $classement_query->fetch())
                {
                    $pts_bonus = $donnees['pts_bonus'];
                    ?>
                  
                        <tr>
                        <td class= "text-left"   style="width:5%;"><strong><?php echo ($i + 1) ; ?></strong></td>
                        <td class="text-center" ><?php echo $donnees['membre_pseudo'] ; ?></td>
                        <td class="text-center"  ><strong><?php echo $donnees['points']; ?></strong></td>
                        <td class="text-center" style="width:15%;"><?php echo $donnees['nb_resultat'] ; ?></td>
                        <td class="text-center" style="width:15%;"><?php echo '<strong class="label label-success">'.$donnees['bonus_trouve'].' </strong>(<i>'.$pts_bonus.'</i>)' ; ?></td>
                        </tr>
                    <?php
                        $i++;
                }
                
                if($i == 0) echo '<tr><td colspan="3">Pas de membre trouvÈ.</td></tr>';
                ?>
                </tbody>
                  </table>
                     </div>
                  </div>
                </div>
            </div>
          </div>  

  <!---------------------------------------------------------------------------------------->
  <!----------------------          FIN CLASSEMENT JOURNEE       --------------------------->
  <!---------------------------------------------------------------------------------------->     
    

        </div>


  <!--------------------------------------------------------------------------------------->
  <!------------------------          PARTIE FLUX RSS         ----------------------------->
  <!--------------------------------------------------------------------------------------->   

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
    <option id= "selectflux" >Flux d'actu - s√©lectionnez votre actu </option>
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
<div class="table-responsive">
<table class="table" >
<div id="rssOutput" style = "height: 380px;word-wrap: break-word;margin-left:20px;margin-right:10px;margin-top:10px;overflow-y: scroll;" >
</div>
</table >
</div>
</div>
</div>




  <!--------------------------------------------------------------------------------------->
  <!------------------------          FIN FLUX RSS         -------------------------------->
  <!--------------------------------------------------------------------------------------->    


 <!--------------------------------------------------------------------------------------->
  <!------------------------          PARTIE TCHAT         -------------------------------->
  <!--------------------------------------------------------------------------------------->

           
            <div class="col-xs-12 col-md-offset-1 col-md-5 toppad" >
         <div class="panel panel-info" >   
            <div class="panel-heading">
                <!-- ON AFFICHE LES USERS DANS UN TOOLTIP -->
                <span class= "pull-right hidden-xs"> 
                    <a style="font-size: 0.6em;" href="#" data-toggle="tooltip" data-html="true" title="<?php include("chat/users.php");?>">Voir les Connect√©s</a>
                </span> 
                
<h3 class="panel-title ">Tchat room</h3>
        
            </div>
                           <div class="row">
                 <div class= "table-responsive" id="table_chat">       
                  <table class="table"   >
    
<script>
var auto_refresh = setInterval(
  function ()
  {
    $('#messages').load('chat/index.php');
  //  element = document.getElementById('messages');
 //   element.scrollTop = element.scrollHeight;
  }, 500);
</script>
                  
        <div id="messages" style = "height: 300px;padding-left: 5px;padding-top:5px;padding-right:10px;word-wrap: break-word;overflow-y: scroll;" >
            <!-- les messages du tchat -->
            <?php
            
		//include("chat/index.php");

            ?>

        </div>
<!-- method="POST" action="traitement.php" onclick="ajaxrequest(this.value)"-->

             
                           </table>
                     </div>
                </div>
             
            <div class= "row">
                <form >
                   <input type="hidden" name="pseudo" id="pseudo" value = "<?php echo $_SESSION['membre_pseudo']; ?>" <br />
            <div style="padding:5px;">
                    <input class= "col-xs-12" name="message" id="message" placeholder = "Indiquez Votre message"  required></input>
            </div>
            <br><br>
                <div class="text-center" style="padding-bottom:10px;">
                    <input  type="submit" name="submit" value="Envoi !" id="envoi"  />
                </div>
                </form>
            </div>
             
   
         </div> 
             </div> 
 
  <!--------------------------------------------------------------------------------------->
  <!------------------------          FIN TCHAT         ----------------------------------->
  <!--------------------------------------------------------------------------------------->   

</div>

  <!--------------------------------------------------------------------------------------->
  <!-----------------         PROPOSITION AMELIORATION DU SITE        --------------------->
  <!--------------------------------------------------------------------------------------->     
<div class= "row">
    <div class= "col-xs-12 toppad">
    <form style= "background-color:#E2EDEE;padding: 20px;" action="accueil.php" method="post" >  
  <h4>Des propositions pour am√©liorer le site ? </h4>
      <textarea name="suggestion"  class="form-control" rows="4" placeholder="Indiquez vos suggestions"></textarea><br>
    <button class="btn btn-primary" type="submit">Envoyer</button>
    </form>
        
<?php 
if (isset($_POST['suggestion'])) {

  $contenu =   htmlspecialchars($_POST['suggestion']);
  $pseudo = $_SESSION['membre_pseudo'];

            //On modifie la table
                $req = $bdd->prepare('INSERT INTO suggestion SET contenu_suggestion= :contenu , pseudo_suggestion= :pseudo, date_suggestion = NOW()');
                $req->execute(array('contenu' => $contenu,
                                    'pseudo' => $pseudo ));
                $req->closeCursor();
}
?>        

        
</div>    
    </div>            
<br> 


   </div> 

    



</div> <!-- FIN MAIN -->

    





    <!-- END # MODAL LOGIN -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <script src="<?php echo ROOTPATH; ?>/chat/main.js"></script>

		

		<?php

		include('includes/bas.php');

		?>

