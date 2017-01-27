     

      <?php

session_start();

include('.././includes/fonctions.php');

actualiser_session();
                // on se connecte sur la BDD

            $requete = $bdd->query('SELECT * FROM message ORDER BY id ASC'); //PAS DE LIMITE

                while($donnees = $requete->fetch()){
                     $date = new DateTime($donnees['date']);
                     $label = ($donnees['pseudo'] == $_SESSION['membre_pseudo'])? "label label-success" : "label label-primary" ; 
                    // on affiche le message (l'id servira plus tard)
                    echo "<p id=\"" . $donnees['id'] . "\"><span style='font-size: 0.8em;' class='label label-default' > ".$date->format('d/m H\hi')."</span>  <span  style='padding-left:10px;'><strong class='".$label."'>" . $donnees['pseudo'] . "</strong></span> " . $donnees['message'] . "</p>";
                }

                $requete->closeCursor();
            ?>