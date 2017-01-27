<?php

/*



MAJ des donn�es (réserver à l'administrateur)



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

        <h1 class="text-center">MAJ des données</h1><br><br>

   

            <div class="row">



           <a href="<?php echo ROOTPATH; ?>/statistiques/MAJ_tables_stat.php"> <button class="col-xs-12 btn btn-primary">MAJ données statistiques</button></a>

            </div>



        </div>

	</div>

    

    

    



    

    <!-- END # MODAL -->

 </div>

</div>



<?php

include('../includes/bas.php');

?>