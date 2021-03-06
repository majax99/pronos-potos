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



/**********Fin entête et titre***********/

?>



<?php 

 

/*************************************************************************************/

/******************* VERIFICATION DES PRONOSTICS *************************************/

/*************************************************************************************/   



// on récupère le numéro de la journée dans une variable

$j = intval($_GET['j']);
$jEnCours = intval($_GET['jEnCours']);


// on indique la bonne écriture en fonction de la journée

if ($j == 1) {$journee_ecriture = $j.'ère journée'; }

else if ($j == 2) {$journee_ecriture =  $j.'nd journée'; }

else  {$journee_ecriture =  $j.'ème journée';}



// création de la date du jour en datetime                      

$now = date('Y-m-d H:i:s'); 

$now = new DateTime( $now );


/**********************************************************************************/
/**************** ON DEFINIT LE CLASSEMENT DE CHAQUE EQUIPE ***********************/

 $classement_l1 = $bdd->query('SELECT equipe, sum(points_equipe) as points FROM (SELECT substr(matchl1_name,1,INSTR( matchl1_name, "-" ) -2) AS equipe, 
case when matchl1_resultat = "1" then 3 when matchl1_resultat = "N" then 1 when matchl1_resultat = "2" then 0 end as points_equipe 
FROM match_ligue1 where matchl1_resultat<>"" AND matchl1_journee <'.$jEnCours.' UNION ALL 
SELECT substr(matchl1_name,INSTR( matchl1_name, "-" ) +2,length(matchl1_name) - INSTR( matchl1_name, "-" )) AS equipe, 
case when matchl1_resultat = "1" then 0 when matchl1_resultat = "N" then 1 when matchl1_resultat = "2" then 3 end as points_equipe 
FROM match_ligue1 where matchl1_resultat<>"" AND matchl1_journee <'.$jEnCours.') tt group by equipe order by points DESC');

$rang=0;
$i=1;
$point=0;
            while ($classement_l1_2 = $classement_l1->fetch())
                
            { 
                if ($classement_l1_2['points'] != $point) { $rang=$i; } //en cas d'�galit� entre 2 �quipes, elles ont le m�me rang
                
                $equipe= $classement_l1_2['equipe'];
                $classementl1[$equipe]=$rang;   // on enregistre les donn�es dans un tableau pour cr�er le classement
                
                $i++;
                $point=$classement_l1_2['points'];
            }

unset($i);
//print_r($classementl1);
//echo $classementl1['Nantes'];




 // requête pour récupérer la date du premier match de la journée

 /*$date_firstMatch = $bdd->query('SELECT min(matchl1_date) as dateMin  FROM match_ligue1  WHERE matchl1_journee = "'.$j.'" LIMIT 1');

 $date_firstMatch = $date_firstMatch->fetch(PDO::FETCH_ASSOC);

 $datemin = new DateTime( $date_firstMatch['dateMin'] );

 $val = ($datemin < $now)? 'disabled' : '' ;*/

// on r�cup�re la date minimum pour laquelle on a le bonus
 $date_bonusMatch = $bdd->query('SELECT matchl1_date  FROM match_ligue1 as A  INNER JOIN prono_ligue1 as B ON A.matchl1_name = B.pronol1_match WHERE matchl1_journee = "'.$j.'" AND pronol1_bonus=5 AND pronol1_membre_id = '.$_SESSION['membre_id'].'   LIMIT 1');

if ($date_bonusMatch ) {
 $date_bonusMatch = $date_bonusMatch->fetch(PDO::FETCH_ASSOC);
 $datebonus = new DateTime( $date_bonusMatch['matchl1_date'] );
    if ($datebonus < $now) {$val = 'disabled';}
    else {$val = "";}
}

else {$val="";}

?>

<!--- PRONOSTICS -->    

     

  <form class="form-horizontal" action="verif_pronol1.php" method="post"  >      

    <div class= "row" >

     <div class="col-xs-12  col-lg-offset-2 col-lg-8   toppad" >

          <div class="panel panel-info">

            <div class="panel-heading">

              <h3 class="panel-title text-center"> <?php echo $journee_ecriture;?> </h3>

            </div>

            <div class="panel-body">

                <div class="row">

                     <div   class= "table-responsive" > 

                  <table id="tableProno" class="table">



                <?php





                // requête pour récupérer les matchs d'une journée

                $match_query2 = $bdd->query('SELECT matchl1_name as l1_match,matchl1_journee, matchl1_cote1, matchl1_coteN, matchl1_cote2,matchl1_date  FROM match_ligue1  
							WHERE matchl1_journee = "'.$j.'" ORDER BY matchl1_date ');

                $i = 0;



                ?>

                      

                                        <thead>

                                            <th class= "text-center" style="vertical-align:middle;">Match </th>
                                            <th class= "text-center" style="vertical-align:middle;"> Résultat </th>
                                            <th class= "text-center" style="vertical-align:middle;"> Bonus <br> X50 </th>
                                            <th class= "text-center" style="vertical-align:middle;">1 </th>
                                            <th class= "text-center" style="vertical-align:middle;">N </th>
                                            <th class= "text-center" style="vertical-align:middle;">2 </th>

                                            

                                        </thead>

                                        <tbody>

            <?php

            while ($match_query = $match_query2->fetch())

            {                

                $date_match= new DateTime($match_query['matchl1_date']);

                if ($date_match > $now ) { // on vérifie que le match n'est pas déjà passé



                $match = htmlspecialchars($match_query['l1_match'], ENT_QUOTES);

                 // On veut récupérer les noms d'équipe

                $pos = strpos($match,'-');

                 $equipe1 = strtolower(substr($match,0,$pos - 1)); // Nom de l'équipe 1

                 $equipe2 = strtolower(substr($match,$pos+2,strlen($match)-($pos+1))); // Nom de l'équipe 2

                 $equipe1_MAJ = substr($match,0,$pos - 1); // Nom de l'�quipe 1 (info telle que dans la BDD)
                 $equipe2_MAJ = substr($match,$pos+2,strlen($match)-($pos+1)); // Nom de l'�quipe 2 (info telle que dans la BDD)

                 $journee = htmlspecialchars($match_query['matchl1_journee'], ENT_QUOTES);

                 $cote1 = htmlspecialchars($match_query['matchl1_cote1'], ENT_QUOTES);

                 $coteN = htmlspecialchars($match_query['matchl1_coteN'], ENT_QUOTES);

                 $cote2 = htmlspecialchars($match_query['matchl1_cote2'], ENT_QUOTES);  

                 // tableau avec les différents types de résultats

                  $arrayresultat = array('1', 'N', '2');     

                

     // requête pour vérifier si les résultats ont déjà été entrés par le membre          

       $verif_resultat = $bdd->prepare('SELECT pronol1_resultat , pronol1_bonus FROM prono_ligue1 WHERE pronol1_membre_id = :id AND pronol1_journee = :journee AND pronol1_match = :match LIMIT 1');

              $verif_resultat->execute(array('id' => $_SESSION['membre_id'],

                                                   'match' => $match,

                                                   'journee' => $journee));

              $verif_resultat = $verif_resultat->fetch(PDO::FETCH_ASSOC); 

                    

            // requete pour voir si le membre peut encore indiquer le bonus   

    /*     $bonus_query2 = $bdd->query('SELECT pronol1_bonus, pronol1_match, matchl1_date    FROM match_ligue1 INNER JOIN prono_ligue1 ON pronol1_match = matchl1_name

                WHERE matchl1_journee = "'.$j.'" AND pronol1_membre_id = "'.$_SESSION['membre_id'].'"');           */    

                

                $resultat = $verif_resultat['pronol1_resultat']; // resultat

                $bonus = $verif_resultat['pronol1_bonus']; // bonus

                $class1=$classementl1[$equipe1_MAJ]; // Position dans le classement de la ligue 1 de l'�quipe s�lection�e
                $class2=$classementl1[$equipe2_MAJ]; // Position dans le classement de la ligue 1 de l'�quipe s�lection�e

      

                   echo ' 

                       <tr><td style = "width:50%;vertical-align:middle;" class= "text-center"><img class="hidden-xs" src = "../img/ligue1/'.$equipe1.'.png" "vertical-align :middle;">  '.$equipe1_MAJ.'<i style="font-size:0.7em;">('.$class1.')</i> 
				- '.$equipe2_MAJ.'<i style="font-size:0.7em;">('.$class2.')</i> <img class="hidden-xs" src = "../img/ligue1/'.$equipe2.'.png" style="vertical-align :middle;"> </td>
                         <td style = "vertical-align:middle;" class= "text-center">
                        <select  class="select" id="resultat_match" name="'.$match.'!'.$j.'"  >



                        <option > </option>';

  // pour le bouton select, on boucle pour vérifier si le membre n'a pas déjà indiqué le résultat 

     foreach( $arrayresultat as $res ) {

         

          if ($res == $resultat) {

              echo "<option value='$res' selected>$res</option>"  ;

          }

         

         else {

          echo "<option value='$res'>$res</option>"  ;

          }

                                        }   

        

   echo ' </select></td>   

  <td style = "vertical-align:middle;" class= "text-left">

    

    <label class="checkbox-inline" for="checkboxes-0">';  

                

  // on vérifie si le membre a indiqué son bonus              

    if ($bonus == 5) {

    

     echo ' <input  class="radio" type="radio" name="checkboxes" id="checkboxes" value="5" checked '.$val.'>' ;

        }  

                

    else {

        echo ' <input class="radio" type="radio" name="checkboxes" id="checkboxes" value="5" '.$val.'>' ;

    }

 /*****************************************************************/                  

echo '    </label>

</td>
 

			<td style = "vertical-align:middle;" class= "text-center cote1">  '.$cote1.'  </td>

                        <td style = "vertical-align:middle;" class= "text-center coteN">  '.$coteN.'  </td>

                        <td style = "vertical-align:middle;" class= "text-center cote2">  '.$cote2.'  </td>

   

  

        </tr> ';

                  

                    $i++;

                }

            }

                

                if($i == 0) echo '<tr><td colspan="3">Pas de match trouvé.</td></tr>';

                                            



                ?>                      

                        

                     </tbody>

                 </table>
			<span style="font-size:1.2em; "class="col-xs-12 col-md-offset-4 col-md-6 ">Points possibles : <strong><span id="total" name="total" type="text" size = 3 > </span></strong></span>  <br><br>
			<i style = "color:red;"> 1-Le bonus de la journée peut être indiqué pendant la journée<br>2- les 2 points retirés par la ligue pour Metz ne sont pas pris en compte dans le classement des équipes</i>
			</div>
                </div>

              </div>

            </div>

          </div>

</div>

<!-- Button submit -->

<div class="text-center" style="padding:20px;"><input class=" btn btn-primary btn-lg" type="submit" value="Valider les pronostics" /></div>  

      

</form>

    <script>

// On initialise les variables tableaux
var var1 = [];
var varN = [];
var var2 = [];
var select = [];
var radio = [];
var total = [];
var potentielTotal = 0;
        
function RecupDonnees(tableau,chaine) {
var i=0;
$('#tableProno tbody > tr ').each(function() {
    
 if (tableau == radio) {tableau[i] = $(this).find(chaine).val(); } // cas du bouton radio
else {tableau[i] = $(this).find(chaine).text(); }

 // si la veleur de tableau[i] est 0 ou vide ou undefined, on la met � 1 
    i++;
 });
};

function potentielPoint() {
    
RecupDonnees(var1,"td.cote1");     
RecupDonnees(varN,"td.coteN");
RecupDonnees(var2,"td.cote2");   
RecupDonnees(select,".select option:selected");  
RecupDonnees(radio,"input:checked");  
    
 for (var i=0; i< var1.length; i++) {
     
radio[i] = radio[i] || 1;       
 
// condition sur le choix du prono     
if (select[i] == "1") { total[i] = Math.round(var1[i] * 10 * radio[i]);  }   
else  if (select[i] == "N") { total[i] = Math.round(varN[i] * 10 * radio[i]);  }   
else  if (select[i] == "2") { total[i] = Math.round(var2[i] * 10 * radio[i]);  }   
else {total[i]=0 ; } 

// calcul du total de points
if  (i==0) {potentielTotal = total[i]; }
else if (i > 0) {potentielTotal = potentielTotal + total[i]; }
                                    } 
$("#total").html(potentielTotal) ;
};          
potentielPoint();        

$(".select").change(function() {
    potentielPoint();
});      

$(".radio").click(function() {
    potentielPoint();
});      
  

</script>





    <!-- END # MODAL LOGIN -->

