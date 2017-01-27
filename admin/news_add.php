<?php

/*



Modification des profils des utilisateurs ou suppression des utilisateurs (réserver à l'administrateur)



*/



session_start();

header('Content-type: text/html; charset=utf-8');

include('.././includes/config.php');



/********Actualisation de la session...**********/



include('.././includes/fonctions.php');

actualiser_session();





/********Fin actualisation de session...**********/





/* Si le membre n'est pas administrateur, on le renvoie sur l'index */

if(!isset($_SESSION['membre_id']) || $_SESSION['membre_statut'] != 'ADMINISTRATEUR')

{

	header('Location: '.ROOTPATH.'/index.php');

	exit();

}



/********Entête et titre de page*********/



/*$titre = 'Inscription';*/



include('.././includes/haut.php'); //contient le doctype, et head.



/**********Fin entête et titre***********/

?>



<?php

include('.././includes/col_gauche.php');

?>





        

<div class="main">



<div class="container">

	<div class="row">

		

        <h1 class="text-center">Ajouter une news</h1><br><br>



<?php if (!isset($_POST['textAjout']) || !isset($_POST['typeAjout'])) { 

    

?>

        

<form class="form-horizontal" method="post" action="news_add.php">

<fieldset>



<!-- Form Name -->

<legend>Ajout de news</legend>



<!-- Textarea -->

<div class="form-group">

  <label class="col-md-4 control-label" for="textarea">Indiquer le texte à ajouter</label>

  <div class="col-md-4">                     

    <textarea class="form-control" id="textarea" name="textAjout"></textarea>

  </div>

</div>



<!-- Select Basic -->

<div class="form-group">

  <label class="col-md-4 control-label" for="selectbasic">Type de news</label>

  <div class="col-md-4">

    <select id="selectbasic" name="typeAjout" class="form-control">

      <option value="MAJ cote">MAJ cote</option>

      <option value="nouvelles fonctionnalités">Nouvelles fonctionnalités</option>

      <option value="Information">Information</option>

      <option value="Rappel">Rappel</option>

      <option value="Autre">Autre</option>

    </select>

  </div>

</div>

<br><br>

<!-- Button -->

<div class="form-group">

  <label class="col-md-4 control-label" for="singlebutton"></label>

  <div class="col-md-4 col-xs-12">

    <button id="singlebutton" name="formAjout" class="btn btn-primary">Valider</button>

  </div>

</div>



</fieldset>

</form>



<?php

    

}

        

else {

    

    $texte = $_POST['textAjout'];

    $type = $_POST['typeAjout'];

    

  				$insertion = $bdd->prepare( 'INSERT INTO  news_site (news_texte, news_date, news_date_MAJ,news_type)   VALUES(:news_texte, NOW(), NOW(), :news_type)');



$insertion->execute(array(

	'news_texte' => $texte,

	'news_type' => $type

	));



echo '<br><h3> Une nouvelle news a été ajoutée !</h3><br>' ;    

echo '<a href="'.ROOTPATH.'/admin/news_add.php"> <button class="col-md-6 col-xs-12 btn btn-primary">Ajouter une autre news</button></a> ' ; 

} 

        

?>        

        

            <br><br>



        </div>

    

    

    



    

    <!-- END # MODAL -->

 </div>

</div>



<?php

include('../includes/bas.php');

?>