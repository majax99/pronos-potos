<?php
/*
Neoterranos & LkY
Page haut.php

Page incluse créant le doctype etc etc.

Quelques indications : (utiliser l'outil de recherche et rechercher les mentions données)

Liste des fonctions :
--------------------------
Aucune fonction
--------------------------


Liste des informations/erreurs :
--------------------------
Aucune information/erreur
--------------------------
*/
include('connectes.php'); 
?>
<!DOCTYPE html>

<!-- <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" class="no-js"> -->
<html>
	<head>
	<?php
	/**********Vérification du titre...*************/
	
	if(isset($titre) && trim($titre) != '')
	$titre = $titre.' : '.TITRESITE;
	
	else
	$titre = TITRESITE;
	
	/***********Fin vérification titre...************/
	?>
		<title><?php echo $titre; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="language" content="fr" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link href="<?php echo ROOTPATH; ?>/assets/css/bootstrap.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css"> 
        <link href="<?php echo ROOTPATH; ?>/sidebar.css" type="text/css" rel="stylesheet" media="screen">   
	 <link href="<?php echo ROOTPATH; ?>/mobile.css" type="text/css" rel="stylesheet" media="screen">  
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script src="http://code.highcharts.com/highcharts.js"></script>
	</head>

	<body>
