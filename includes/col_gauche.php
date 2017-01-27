







<!-- BEGIN # BOOTSNIP INFO -->



<nav class="navbar navbar-inverse sidebar" >

    <div class="container-fluid">

		<!-- Brand and toggle get grouped for better mobile display -->

		<div class="navbar-header">

			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-sidebar-navbar-collapse-1">

				<span class="sr-only">Toggle navigation</span>

				<span class="icon-bar"></span>

				<span class="icon-bar"></span>

				<span class="icon-bar"></span>

			</button>

		</div>

				<!-- SIDEBAR USERPIC -->

   <?php

        

               if(isset($_SESSION['membre_pseudo'])) {

 ?>



               <div class="profile hidden-xs">

				<div class="profile-userpic"><br/>

                <?php if (!empty($_SESSION['membre_avatar'])) { ?>

					<img src="<?php echo ROOTPATH; ?>/img/avatars/<?php echo  $_SESSION['membre_avatar']; ?>" class="img-responsive" alt="">

                <?php } ?>

				</div>

				<!-- END SIDEBAR USERPIC -->

				<!-- SIDEBAR USER TITLE -->

				<div class="profile-usertitle">

					<div class="profile-usertitle-name" style = " color: #5a7391;">

						<?php echo $_SESSION['membre_pseudo']; ?>

					</div>

					<div class="profile-usertitle-job">

						<?php echo $_SESSION['membre_statut'];?>

						

					</div>

				</div>

               </div>         

    <?php

}

 

 ?>



		<!-- Collect the nav links, forms, and other content for toggling -->



		<div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">

			<ul class="nav navbar-nav">

   <?php

               if(isset($_SESSION['membre_id'])) {

 ?>

                 

				<li id=accueil class="active" ><a href="<?php echo ROOTPATH; ?>/index.php">Accueil<span style="font-size:16px; " class="pull-right hidden-xs showopacity glyphicon glyphicon-home"></span></a></li>

                

                <li class="dropdown">

					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Pronostics <span class="caret"></span><span style="font-size:16px;" class="pull-right hidden-xs showopacity fa fa-futbol-o"></span></a>

					<ul class="dropdown-menu forAnimate" role="menu">

						<li><a href="<?php echo ROOTPATH; ?>/pronostic/prono_ligue1.php">Faire mes pronostics</a></li>

				        <li><a href="<?php echo ROOTPATH; ?>/pronostic/mesPronos_L1_ajax.php">Mes résultats</a>  </li>              

					</ul>

				</li>   

                

				<li class="dropdown">

					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Classement <span class="caret"></span><span style="font-size:16px;" class="pull-right hidden-xs showopacity fa fa-list-ol"></span></a>

					<ul class="dropdown-menu forAnimate" role="menu">

						<li><a href="<?php echo ROOTPATH; ?>/pronostic/classement.php">Classement général</a></li>

				        <li><a href="<?php echo ROOTPATH; ?>/pronostic/classement_journee_ajax.php">Classement par journée</a>  </li>  

                        <li class="divider"></li>
                        <li><a href="<?php echo ROOTPATH; ?>/pronostic/classement_HorsBonus.php">Classement <i>(hors bonus)</i></a>  </li>              

					</ul>

				</li>   

                		<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Le coin des stats<span class="caret"></span><span style="font-size:16px;" class="pull-right hidden-xs showopacity fa fa-bar-chart"></span></a>
					<ul class="dropdown-menu forAnimate" role="menu">
						<li><a href="<?php echo ROOTPATH; ?>/statistiques/coin_des_stats_global.php">Statistiques globales</a></li>   
			                     <li><a href="<?php echo ROOTPATH; ?>/statistiques/coin_des_stats_indiv.php">Mes statistiques</a></li>  
						<li><a href="<?php echo ROOTPATH; ?>/statistiques/statistiques_club_AJAX.php">Statistiques par club</a></li> 
						<li><a href="<?php echo ROOTPATH; ?>/statistiques/stats_graphique_ajax.php">Graphiques</a></li>            
					</ul>
				</li> 


				<li ><a href="<?php echo ROOTPATH; ?>/membres/profil.php">Profil<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-user"></span></a></li>

				<li class= "hidden"><a href="#" >Le coin des stats<span style="font-size:16px;" class="pull-right hidden-xs showopacity fa fa-bar-chart"></span></a></li>

				<li class="dropdown">

					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Paramètres <span class="caret"></span><span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-cog"></span></a>

					<ul class="dropdown-menu forAnimate" role="menu">

						<li><a href="<?php echo ROOTPATH; ?>/pronostic/regle.php">Règlement</a></li>

						<li class="divider"></li>

				        <li><a href="<?php echo ROOTPATH; ?>/membres/deconnexion.php">Deconnexion<span style="font-size:16px; " class="pull-right hidden-xs                           showopacity"></span></a>  </li>              

						<li class="divider"></li>

						<li><a href="<?php echo ROOTPATH; ?>/membres/desinscrire.php">Desinscription</a></li>

					</ul>

				</li>


  <?php
               if(isset($_SESSION['membre_statut']) && ( $_SESSION['membre_statut'] == 'ADMINISTRATEUR' || $_SESSION['membre_statut'] == 'ADMIN_SCORE' ))
               {
 ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Outils admin <span class="caret"></span><span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-wrench"></span></a>
					<ul class="dropdown-menu forAnimate" role="menu">
                        
    <?php   if(isset($_SESSION['membre_statut']) && ($_SESSION['membre_statut'] == 'ADMIN_SCORE' || $_SESSION['membre_statut'] == 'ADMINISTRATEUR' )) { ?>
						<li><a href="<?php echo ROOTPATH; ?>/admin/resultat_L1_ajax.php">Mettre &agrave; jour les scores</a></li>
    <?php } if (isset($_SESSION['membre_statut']) && ( $_SESSION['membre_statut'] == 'ADMINISTRATEUR')) { ?>
						<li><a href="<?php echo ROOTPATH; ?>/admin/modif_user.php">Modifier un utilisateur</a></li>
                        <li><a href="<?php echo ROOTPATH; ?>/admin/modif_match_ajax.php">Mettre &agrave; jour les matchs</a></li>
			   <li><a href="<?php echo ROOTPATH; ?>/admin/envoi_email.php">Envoi des mails</a></li>
			   <li><a href="<?php echo ROOTPATH; ?>/admin/news.php">Gestion des news</a></li>
			   <li><a href="<?php echo ROOTPATH; ?>/admin/MAJ_donnees.php">MAJ des données</a></li>
                <?php } ?>
					</ul>
				</li>
            <?php } ?>



<?php }
?>

      			</ul>

		</div>


        <?php
//Initialisation de la variable
$count_online = 0;

//D�compte des membres
$texte_a_afficher = "<br />Liste des personnes en ligne : ";
$time_max = time() - (60 * 5);

$nbinscrit = $bdd->query('SELECT count(membre_id) as nb_inscrits FROM membres');   
$nbinscrit = $nbinscrit->fetch(PDO::FETCH_ASSOC);        
        
$nbConnectes = $bdd->query('SELECT count(online_id) as Nb_connectes FROM membres_online WHERE online_time > '.$time_max.'');   
$nbConnectes = $nbConnectes->fetch(PDO::FETCH_ASSOC);
           
echo '
<div class="row">
<div class = "hidden-xs">
<br> <br>
<p  style = "color : white;padding-left :9%;font-size : 90%;">'.$nbinscrit['nb_inscrits'].' membres inscrits</p>
<p  style = "color : white;padding-left :9%;font-size : 90%;">'.$nbConnectes['Nb_connectes'].' membre(s) connecté(s)</p>
</div>
</div>';
?>

	</div>

</nav>









