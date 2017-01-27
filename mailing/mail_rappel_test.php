
<?php


header('Content-type: text/html; charset=utf-8');

include('/home/pronospogg/pronos-potos.fr/includes/config.php');

/********Actualisation de la session...**********/

include(ABSPATH.'/includes/fonctions.php');

                $membre_query2 = $bdd->query('SELECT membre_pseudo, membre_id, membre_mail  FROM membres  WHERE membre_mail_rappel = 0 AND membre_id=1   ');



            while ($membre_query = $membre_query2->fetch())

            {  

                $pseudo1 = htmlspecialchars($membre_query['membre_pseudo']);

                $pseudo = str_replace(' ','%20',$pseudo1);

                $email_envoi = htmlspecialchars($membre_query['membre_mail']);

                $id = htmlspecialchars($membre_query['membre_id']);



$url=ROOTPATH."/mailing/mail_rappel_contenu.php?name=".$pseudo."&mail=".$email_envoi."&id=".$id."";
$message = file_get_contents($url);
$sujet = utf8_decode('Pronos-potos : faîtes vos pronostics');
				
$headers ='From: "Lo�c de pronos-potos"<admin@pronos-potos.fr>'."\n"; 
$headers .='Reply-To: admin@pronos-potos.fr'."\n"; 
$headers .='Content-Type: text/html; charset="UTF-8"'."\n"; 
$headers .='Content-Transfer-Encoding: 8bit'; 


                            mail($email_envoi, $sujet, $message, $headers); 



            }

                        ?>

			<h1>Email envoyé !</h1>
			<p>	Vous pouvez retourner sur l'accueil en cliquant <a href="<?php echo ROOTPATH; ?>/accueil.php">ici</a>.
			</p><br/>