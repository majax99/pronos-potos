<?php

/*

Accueil du site.



Le membre une fois connecté peut se diriger vers les différents menus



*/



session_start();

header('Content-type: text/html; charset=utf-8');

include('.././includes/config.php');



/********Actualisation de la session...**********/



include('.././includes/fonctions.php');





/********Fin actualisation de session...**********/



/********Entête et titre de page*********/



$titre = 'Accueil';

//include('includes/haut.php'); //contient le doctype, et head.

	?>





<html>

	<head>





		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

		<meta name="language" content="fr" />

        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"> 

        <link href="<?php echo ROOTPATH; ?>/assets/css/bootstrap.css" rel="stylesheet">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css"> 

        <link href="<?php echo ROOTPATH; ?>/sidebar.css" type="text/css" rel="stylesheet" media="screen">  



	</head>



	<body>











<?php      

/*if(!isset($_SESSION['membre_id'])) 

{

   	header('Location: '.ROOTPATH.'/index.php');

	exit();



}*/



$pseudo = $_GET['name'];

$id = $_GET['id'];

$mail = $_GET['mail'];

        

$urlDesabo =    ROOTPATH."/mailing/mail_rappel_desabo.php?name=".$pseudo."&id=".$id."&mail=".$mail."" ;     

?>









<?php 

 $date_prochainMatch = $bdd->query('SELECT min(matchl1_date) as dateMin, matchl1_journee FROM (select matchl1_date, matchl1_journee from match_ligue1 where matchl1_resultat = "" order by matchl1_date) l ');

 $date_prochainMatch = $date_prochainMatch->fetch(PDO::FETCH_ASSOC);

 $datemin = new DateTime( $date_prochainMatch['dateMin'] );

$journee = $date_prochainMatch['matchl1_journee'] ;





// on indique la bonne écriture en fonction de la journée

if ($journee == 1) {$journee_ecriture = $journee.'ère journée'; }

else if ($journee == 2) {$journee_ecriture =  $journee.'nd journée'; }

else  {$journee_ecriture =  $journee.'ème journée';}





?>



   <p>Bonjour <?php echo $_GET['name'] ?>,</p><br>

          

        <p> Pour rappel, la prochaine journée de championnat de ligue 1 débutera le <strong><?php echo $datemin->format('d/m/Y à H\hi')?></strong>.  <br>

          Si vous n'avez pas encore fait vos pronostics, cliquer  <strong><a href=" <?php echo ROOTPATH ?>/pronostic/prono_ligue1.php">ici</strong></a>

          .</p> <br> 



            <p> La liste des prochains matchs ci-dessous : </p>





<div class= "row" >

     <div class="col-xs-12 col-lg-4   toppad" >

          <div class="panel panel-info">

            <div class="panel-heading">

              <h3 class="panel-title text-center"> Matchs de la <?php echo $journee_ecriture;?> </h3>

            </div>

            <div class="panel-body">

                <div class="row">

                  <div class="table-responsive ">  

                  <table class="table table-condensed">



                <?php





                // requête pour récupérer les matchs d'une journée

                $match_query2 = $bdd->query('SELECT matchl1_name as l1_match,matchl1_journee, matchl1_cote1, matchl1_coteN, matchl1_cote2,matchl1_date  FROM match_ligue1  WHERE matchl1_journee = "'.$journee.'" ORDER BY matchl1_date ');

                $i = 0;



                ?>

                      

                                        <thead>

                                                <th align="left"  style= "color:blue;" >Match </th>

                                            <th class= "text-center">1 </th>

                                            <th class= "text-center">N </th>

                                            <th class= "text-center">2 </th>

                                            

                                        </thead>

                                        <tbody>

            <?php

            while ($match_query = $match_query2->fetch())

            {                

                $date_match= new DateTime($match_query['matchl1_date']);





                $match = htmlspecialchars($match_query['l1_match'], ENT_QUOTES);

                 // On veut récupérer les noms d'équipe

                $pos = strpos($match,'-');

                 $equipe1 = strtolower(substr($match,0,$pos - 1)); // Nom de l'équipe 1

                 $equipe2 = strtolower(substr($match,$pos+2,strlen($match)-($pos+1))); // Nom de l'équipe 2

                 $journee = htmlspecialchars($match_query['matchl1_journee'], ENT_QUOTES);

                 $cote1 = htmlspecialchars($match_query['matchl1_cote1'], ENT_QUOTES);

                 $coteN = htmlspecialchars($match_query['matchl1_coteN'], ENT_QUOTES);

                 $cote2 = htmlspecialchars($match_query['matchl1_cote2'], ENT_QUOTES);  

                 // tableau avec les différents types de résultats

                  $arrayresultat = array('1', 'N', '2');     

                

      

                   echo ' 

                       <tr><td align="left" style = "vertical-align:middle;" style = "width:50%;" >   '.$match. ' </td>

                        <td align="center" style = "vertical-align:middle;" class= "text-center">  '.$cote1.'  </td>

                        <td align="center" style = "vertical-align:middle;" class= "text-center">  '.$coteN.'  </td>

                        <td align="center" style = "vertical-align:middle;" class= "text-center">  '.$cote2.'  </td>

                        

        </tr> ';

                  

                    $i++;

                

            }

                

                if($i == 0) echo '<tr><td colspan="3">Pas de match trouvé.</td></tr>';

                                            



                ?>                      

                        

                     </tbody>

                 </table>
<br>



                 </div>

                </div>

              </div>

            </div>

          </div>

</div>





    

          

                  <p> Si vous souhaitez vous désinscrire de ce mail, cliquez sur <strong><a href="<?php echo $urlDesabo?>">me désinscrire</strong></a>

          .</p>  

        <!-- METTRE L ID DANS L URL DU LIEN -->

          

          <p> Bonne journée et bons pronostics ! </p><br>

    

         <p> L'équipe de pronos-potos </p>



		<?php

		include('.././includes/bas.php');

		?>

