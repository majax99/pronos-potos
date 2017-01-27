<?php

/*



Page d'inscritpion sur le site



*/



session_start();

header('Content-type: text/html; charset=utf-8');

include('.././includes/config.php');



/********Actualisation de la session...**********/



include('.././includes/fonctions.php');

actualiser_session();





/* Si le membre n'est pas connecté, on le renvoie sur l'index */

if(isset($_SESSION['membre_id']))

{

	header('Location: '.ROOTPATH.'/index.php');

	exit();

}

/********Fin actualisation de session...**********/



/********Entête et titre de page*********/



$titre = 'Inscription';



include('.././includes/haut.php'); //contient le doctype, et head.



/**********Fin entête et titre***********/

?>



		<?php

		include('.././includes/col_gauche.php');

		?>





        





        <!-- Corps de page

    ================================================== -->



    <div class= "main">

            <div class="container">

			<?php

                

      $arrayligue1 = array('Angers', 'Bastia', 'Bordeaux' , 'Caen' , 'Dijon', 'Guingamp', 'Lille' , 'Lorient' ,'Lyon', 'Marseille', 'Metz', 'Monaco' , 'Montpellier' ,'Nancy', 'Nantes', 'Nice'  ,'PSG', 'Rennes', 'Saint Etienne' , 'Toulouse');                      

                

			if(isset($_SESSION['erreurs']) && $_SESSION['erreurs'] > 0) 

			{

			?>

			<h1>Note :</h1>

			<p>

			<strong>Lors de votre dernière tentative d'inscription, des erreurs sont survenues, en voici la liste :</strong><br/>

			<div class="border-red">



			<?php

				//echo $_SESSION['nb_erreurs'];

				echo $_SESSION['pseudo_info'];

				echo $_SESSION['mdp_info'];

				echo $_SESSION['mdp_verif_info'];

				echo $_SESSION['mail_info'];

		        echo $_SESSION['sexe_info'];

				/*echo $_SESSION['date_naissance_info'];*/

				echo $_SESSION['club_info'];

				echo $_SESSION['clef_info'];

			?>

			Les champs qui étaient corrects sont pré-remplis.<br/>

			</p>

			</div>

			<?php

			}

			?>    

    

 			

<form class="form-horizontal" action="trait-inscription.php" method="post" name="Inscription" enctype="multipart/form-data">

<fieldset>



<!-- Form Name -->



<legend class="text-center">Formulaire d'inscription</legend>



<!-- Text input-->

<div class="form-group ">

  <label class="col-md-4 control-label " for="textinput">pseudo</label>  

  <div class="col-md-4 ">

  <input id="pseudo" name="pseudo" type="text" placeholder="pseudo" maxlength="20" class="form-control input-md" value="<?php  if(isset($_SESSION['pseudo_info']) && $_SESSION['pseudo_info'] == '') echo htmlspecialchars($_SESSION['form_pseudo'], ENT_QUOTES) ; ?>" >

  </div>

</div>

    

<!-- Text input-->

<div class="form-group">

  <label class="col-md-4 control-label " for="textinput">Adresse email</label>  

  <div class="col-md-4">

  <input id="mail" name="mail" type="text" placeholder="email" class="form-control input-md" value="<?php  if(isset($_SESSION['mail_info']) && $_SESSION['mail_info'] == '') echo htmlspecialchars($_SESSION['form_mail'], ENT_QUOTES) ; ?>">

  </div>

</div>



<!-- Password input-->

<div class="form-group">

  <label class="col-md-4 control-label" for="passwordinput">Mot de passe</label>

  <div class="col-md-4">

    <input id="mdp" name="mdp" type="password" placeholder="password" class="form-control input-md" value="<?php  if(isset($_SESSION['mdp_info']) && $_SESSION['mdp_info'] == '') echo htmlspecialchars($_SESSION['form_mdp'], ENT_QUOTES) ; ?>">

  </div>

</div>

    

<!-- Password input-->

<div class="form-group">

  <label class="col-md-4 control-label" for="passwordinput">Mot de passe (vérification)</label>

  <div class="col-md-4">

    <input id="mdp_verif" name="mdp_verif" type="password" placeholder="réécrire le password" class="form-control input-md" value="<?php  if(isset($_SESSION['mdp_verif_info']) && $_SESSION['mdp_verif_info'] == '') echo htmlspecialchars($_SESSION['form_mdp_verif'], ENT_QUOTES) ; ?>">

  </div>

</div>

    



<!-- Multiple Radios (inline) -->

<div class="form-group">

  <label class="col-md-4 control-label" for="radios" >Sexe</label>

  <div class="col-md-4"> 

    <label class="radio-inline" for="radios-0">

      <input type="radio" name="sexe" id="radios-H" value="Homme" >

      Homme

    </label> 

    <label class="radio-inline" for="radios-1">

      <input type="radio" name="sexe" id="radios-F" value="Femme">

      Femme

    </label> 

  </div>

</div>



<!-- Text input-->

<!-- <div class="form-group">

  <label class="col-md-4 control-label" for="textinput">Date de naissance <em>(format JJ/MM/AAAA)</em></label>  

  <div class="col-md-4">

  <input id="date_naissance" name="date_naissance" type="date" placeholder="Date de naissance" class="form-control input-md" value="<?php  /*if(isset($_SESSION['date_naissance_info']) && $_SESSION['date_naissance_info'] == '') echo htmlspecialchars($_SESSION['form_date_naissance'], ENT_QUOTES) ; */?>">

  </div>

</div> -->



<!-- Select Basic -->

<div class="form-group">

  <label class="col-md-4 control-label" for="selectbasic">Club de coeur<br/> (Ligue 1)</label>

  <div class="col-md-4">

<?php 

       echo "<select name='club' class='form-control ' style='width:auto' >

 

     <option >Sélectionnez</option>"  ;

  

     foreach( $arrayligue1 as $ligue1 ) {

         

          if ($ligue1 == $club) {

              echo "<option value='$ligue1' selected>$ligue1</option>"  ;

          }

         

         else {

          echo "<option value='$ligue1'>$ligue1</option>"  ;

          }

                                        }

?>

    </select>

  </div>

</div>



<!-- Avatar --> 

<div class="form-group">

  <label class="col-md-4 control-label hidden-xs" for="filebutton">Photo de profil (facultatif)</label>

  <div class="col-md-4 hidden-xs">

    <input id="avatar" name="avatar" class="input-file" type="file">

  </div>

</div>

    

<br/>

    

<div class="form-group">

  <label class="col-md-4 control-label " for="textinput">Indiquer la clé qu'on vous a donné</label>  

  <div class="col-md-4">

  <input id="clef" name="clef" type="password" placeholder="clef" maxlength="20" class="form-control input-md">

  </div>

</div>



</fieldset>

<!-- Button submit -->

<div class="text-center" style="padding:20px;"><input class=" btn btn-primary" type="submit" value="Valider l'inscription" /></div>

</form>

    </div>

</div>









		<?php

		include('.././includes/bas.php');

       // session_destroy();

		?>