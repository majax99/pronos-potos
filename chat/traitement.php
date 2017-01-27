


<!DOCTYPE html>

<!-- <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" class="no-js"> -->
<html>
	<head>




	</head>

	<body>
<?php
/********Actualisation de la session...**********/


// on se connecte à notre base de données
/*$_SESSION['pseudo'] = "PYB";
$pseudo = $_SESSION['pseudo'];*/



include('.././includes/fonctions.php');


if(isset($_POST['submit'])){ // si on a envoyé des données avec le formulaire

    if(!empty($_POST['pseudo']) AND !empty($_POST['message'])){ // si les variables ne sont pas vides
    
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $message = htmlspecialchars($_POST['message']); // on sécurise nos données

        // puis on entre les données en base de données :
        $insertion = $bdd->prepare('INSERT INTO message VALUES("", :pseudo, :message,NOW())');
        $insertion->execute(array(
            'pseudo' => $pseudo,
            'message' => $message
        ));

    }
    else{
        echo "Vous avez oublié de remplir un des champs !";
    }

}

?>
	</body>
</html>