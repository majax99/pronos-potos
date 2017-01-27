<?php

header('Content-type: text/html; charset=utf-8');

include('.././includes/config.php');



/********Actualisation de la session...**********/



include('.././includes/fonctions.php');

   // On va chercher la définition de la classe

  require_once('phpmailer/class.phpmailer.php');



                $membre_query2 = $bdd->query('SELECT membre_pseudo, membre_id, membre_mail  FROM membres  WHERE membre_mail_rappel = 0 AND membre_id = 1 LIMIT 1   ');



            while ($membre_query = $membre_query2->fetch())

            {  

                $pseudo1 = trim(htmlspecialchars($membre_query['membre_pseudo']));

                $pseudo = str_replace(' ','%20',$pseudo1);

                $email_envoi= htmlspecialchars($membre_query['membre_mail']);

                $id = htmlspecialchars($membre_query['membre_id']);



$urlAdress=ROOTPATH."/mailing/mail_contenu.php";

  

   // On créé une nouvelle instance de la classe

   $mail = new PHPMailer();

  

   // De qui vient le message, e-mail puis nom

   $mail->From = "admin@pronosPotos.fr";

   $mail->FromName = "Admin pronosPotos";

  

   // Définition du sujet/objet

   $mail->Subject = "Pronos-potos : faîtes vos pronostics";

  

   // On lit le contenu d'une page html

$body= file_get_contents(urlAdress);

   // On définit le contenu de cette page comme message

   $mail->MsgHTML($body);

  

   // On pourra définir un message alternatif pour les boîtes de

   // messagerie n'acceptant pas le html

   $mail->AltBody = "Ce message est au format HTML, votre messagerie n'accepte pas ce format.";

  

   // Il reste encore à ajouter au moins un destinataire

   $mail->AddAddress($email_envoi, "PYB");

    

   // Pour finir, on envoi l'e-mail

   $mail->send();

  

            }

?>