<?php

/*

Neoterranos & LkY

Page deconnexion.php



Permet de se déconnecter du site.



Quelques indications : (Utiliser l'outil de recherche et rechercher les mentions données)



Liste des fonctions :

--------------------------

Aucune fonction

--------------------------





Liste des informations/erreurs :

--------------------------

Déconnexion

--------------------------

*/



session_start();

include('../includes/config.php');

include('../includes/fonctions.php');

$id = $_SESSION['membre_id'];

vider_cookie();
session_destroy();

// on supprime l'individu de la table des connectes
$query=$bdd->prepare('DELETE FROM membres_online WHERE online_id= :id');
$query->bindValue(':id',$id,PDO::PARAM_INT);
$query->execute();
$query->CloseCursor();



$informations = Array(/*Déconnexion*/

				false,

				'Déconnexion',

				'Vous êtes à présent déconnecté.',

				' - <a href="'.ROOTPATH.'/membres/connexion.php">Se connecter</a>',

				ROOTPATH.'/index.php',

				5

				);



require_once('../information.php');

exit();

?>