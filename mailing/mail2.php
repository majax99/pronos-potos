<?php
header('Content-type: text/html; charset=utf-8');
include('.././includes/config.php');

/********Actualisation de la session...**********/

include('.././includes/fonctions.php');

                // requête pour récupérer les matchs d'une journée
                $membre_query2 = $bdd->query('SELECT membre_pseudo, membre_id, membre_mail  FROM membres  WHERE membre_mail_rappel = 0   ');

            while ($membre_query = $membre_query2->fetch())
            {  
                $pseudo1 = trim(htmlspecialchars($membre_query['membre_pseudo']));
                $pseudo = str_replace(' ','%20',$pseudo1);
                $mail = htmlspecialchars($membre_query['membre_mail']);
                $id = htmlspecialchars($membre_query['membre_id']);

$adress=ROOTPATH."/mailing/mail_contenu.php?name=".$pseudo."&mail=".$mail."&id=".$id."";

$body= file_get_contents($adress);

            }
                
echo $body;
                
                
                
?>