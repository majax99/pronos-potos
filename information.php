<?php
/*
Gère les informations (page incluse).

Quelques indications : (Utiliser l'outil de recherche et rechercher les mentions données)

Liste des fonctions :
--------------------------
Aucune fonction
--------------------------


Liste des informations/erreurs :
--------------------------
Erreur interne
--------------------------
*/

if(!isset($informations))
{
	$informations = Array(/*Erreur*/
					true,
					'Erreur',
					'Une erreur interne est survenue...',
					'',
					ROOTPATH.'/index.php',
					3
					);
}

if($informations[0] === true) $type = 'erreur';
else $type = 'information';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
	<head>
		<title><?php echo $informations[1]; ?> : <?php echo TITRESITE; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="language" content="fr" />
		<meta http-equiv="Refresh" content="<?php echo $informations[5]; ?>;url=<?php echo $informations[4]; ?>">
	</head>
	
	
	<body>
		<div id="info">
			<div id="<?php echo $type; ?>"><?php echo $informations[2]; ?> Redirection en cours...<br/>
			<a href="<?php echo $informations[4]; ?>">Cliquez ici si vous ne voulez pas attendre...</a><?php echo $informations[3]; ?></div>
		</div>
	</body>
</html>
<?php
unset($informations);
?>