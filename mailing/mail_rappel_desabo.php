<?php

header('Content-type: text/html; charset=utf-8');

include('.././includes/config.php');



/********Actualisation de la session...**********/



include('.././includes/fonctions.php');



$pseudo = $_GET['name'];

$id = $_GET['id'];





             $req = $bdd->prepare('UPDATE membres SET 

                membre_mail_rappel = 1    WHERE membre_id = :id  AND membre_pseudo= :pseudo');

                $req->execute(array('id' => $id,

                                    'pseudo' => $pseudo));

                $req->closeCursor();

include('.././includes/haut.php'); //contient le doctype, et head.

?>

    <div class= "main">
            <div class="container">

<h1> La désinscription au rappel a été effectuée.</h1>

        </div>
        
        </div>

		<?php
		include('.././includes/bas.php'); 
       // session_destroy();
		?>

