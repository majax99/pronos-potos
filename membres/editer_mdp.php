<?php
/*

Edition du mot de passe

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

$titre = 'Edition du mot de passe';
include('.././includes/haut.php'); //contient le doctype, et head.

/**********Fin entête et titre***********/
?>		

		<?php

include('.././includes/col_gauche.php');

		?>
    <div class= "main">
            <div class="container">
                

<?php
if (empty($_POST['sent'])) // Si la variable est vide, on peut considérer qu'on est sur la page de formulaire
    {                
                //Si on choisit de modifier son profil

        //On commence par s'assurer que le membre est connecté
        if (isset ($_SESSION['membre_id'])) 
        {
        //On prend les infos du membre
            
        $data = $bdd->prepare('SELECT membre_pseudo FROM membres WHERE membre_id = :id ');
        $data->execute(array('id' => $_SESSION['membre_id'] ));
        $data = $data->fetch(PDO::FETCH_ASSOC);
?>

        <h1>Modifier mon profil</h1>
        
        <form method="post" action="editer_mdp.php">
       
 
        <fieldset><legend>Identifiants</legend>
        Pseudo : <strong><?php echo stripslashes(htmlspecialchars($data['membre_pseudo'])) ;?> </strong><br />       
        <label for="password">Nouveau mot de Passe :</label>
        <input type="password" name="password" id="password" /><br />
        <label for="confirm">Confirmer le mot de passe :</label>
        <input type="password" name="confirm" id="confirm"  />
        </fieldset><br />

        <br /><br />

    
        </fieldset>
        <p>
            
        <input class=" btn btn-primary " type="submit" value="Modifier mon password" />
        <input type="hidden" id="sent" name="sent" value="1" />
        </p></form>';

<?php
        }
    }

else //Cas du traitement
    {
     //On déclare les variables 

    $mdp_erreur = NULL;

    //Encore et toujours notre belle variable $i :p
    $i = 0;

    $temps = time(); 
    $pass = md5($_POST['password']);
    $confirm = md5($_POST['confirm']);

    //Vérification du mdp
    if ($pass != $confirm || empty($_POST['confirm']) || empty($_POST['password']))
    {
         $mdp_erreur = "Votre mot de passe et votre confirmation diffèrent ou sont vides";
         $i++;
    }

 

    echo '<h1>Modification du password</h1>';

 
    if ($i == 0) // Si $i est vide, il n'y a pas d'erreur
    {
       
        echo'<h1>Modification terminée</h1>';
        echo'<p>Votre password a été modifié avec succès !</p>';
        echo'<p>Cliquez <a href="'.ROOTPATH.'/index.php">ici</a> 
        pour revenir à la page d accueil</p>';
 
        //On modifie la table
        $req = $bdd->prepare('UPDATE membres SET membre_mdp = :pass');
        $req->execute(array('pass' => $pass));
        $req->closeCursor();
    }
    else
    {
        echo'<h1>Modification interrompue</h1>';
        echo'<p>Une ou plusieurs erreurs se sont produites pendant la modification du password</p>';
        echo'<p>'.$i.' erreur(s)</p>';
        echo'<p>'.$mdp_erreur.'</p>';
        echo'<p> Cliquez <a href="'.ROOTPATH.'/membres/editer_password.php">ici</a> pour recommencer</p>';
    }
} //Fin du else
      
	?>
  </div>
                </div>
		<?php
		include('.././includes/bas.php'); 
       // session_destroy();
		?>
<?php
//Créer un dossier 'fichiers/1/'

  //mkdir('../img/avatars/1/', 0777, true);
 
//Créer un identifiant difficile à deviner
 // $nom = md5(uniqid(rand(), true));
?>