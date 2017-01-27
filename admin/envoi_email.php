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

		

        

        <div class="col-md-12">

        <h1 class="text-center">Envoi des emails de rappels</h1><br><br>

   

            <div class="row">



           <a href="<?php echo ROOTPATH; ?>/mailing/mail_rappel_test.php"> <button class="col-xs-12 btn btn-primary">Envoi du mail de test</button></a>

	    <br><br><br><br>

           <a href="<?php echo ROOTPATH; ?>/mailing/mail_rappel.php"> <button class="col-xs-12 btn btn-primary">Envoi du mail de rappel </button></a>

            </div>



        </div>

	</div>

    

    

    



    

    <!-- END # MODAL -->

 </div>

</div>



<?php

include('../includes/bas.php');

?>