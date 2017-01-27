

<?php


 // on se connecte à notre base de données

include('.././includes/fonctions.php');

if(!empty($_GET['id'])){ // on vérifie que l'id est bien présent et pas vide



    $id = $_GET['id']; // on s'assure que c'est un nombre entier



    // on récupère les messages ayant un id plus grand que celui donné

    $requete = $bdd->prepare('SELECT * FROM message WHERE id > :id ORDER BY ID '); // PAS DE LIMITE

    $requete->execute(array("id" => $id));



    $messages = null;



    // on inscrit tous les nouveaux messages dans une variable

    while($donnees = $requete->fetch()){
	
        $date = new DateTime($donnees['date']);
        $messages .= "<p id=\"" . $donnees['id'] . "\"><span style='font-size: 0.8em;' class='label label-default'> ".$date->format('d/m H\hi')."</span>  
<span  style='padding-left:10px;'><strong class='label label-primary'>" . $donnees['pseudo'] . "</strong></span> " . $donnees['message'] . "</p>";

    }



    echo $messages; // enfin, on retourne les messages à notre script JS



}



?>

