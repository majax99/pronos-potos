<?php

     // Plusieurs destinataires

     $to  = 'jeff.realfox@orange.fr' /*. ', '*/; // notez la virgule

    // $to .= 'wez@example.com';



     // Sujet

     $subject = 'Calendrier des anniversaires pour Août';



     // message

    /* $message = '

     <html>

      <head>

       <title>Calendrier des anniversaires pour Août</title>

      </head>

      <body>

       <p>Voici les anniversaires à venir au mois d\'Août !</p>

       <table>

        <tr>

         <th>Personne</th><th>Jour</th><th>Mois</th><th>Année</th>

        </tr>

        <tr>

         <td>Josiane</td><td>3</td><td>Août</td><td>1970</td>

        </tr>

        <tr>

         <td>Emma</td><td>26</td><td>Août</td><td>1973</td>

        </tr>

       </table>

      </body>

     </html>

     ';
 /*$message = file_get_contents('mail_contenu.php');"test";*/


     // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini

     $headers  = 'MIME-Version: 1.0' . "\r\n";

     $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";



     // En-têtes additionnels

$headers .= 'To: PYB <jeff.realfox@orange.fr>' . "\r\n";
$headers .= "Reply-To: ADMIN <admin@gmail.com>\r\n"; 
$headers .= "Return-Path: ADMIN <admin@gmail.com>\r\n"; 
$headers .= "From: ADMIN <admin@gmail.com>\r\n"; 
$headers .= "Organization: ADMIN\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
$headers .= "X-Priority: 3\r\n";
$headers .= "X-Mailer: PHP". phpversion() ."\r\n" ;





     // Envoi

     mail($to, $subject, "test", $headers);

?>
