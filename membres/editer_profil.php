<?php

/*



Page d'édition du profil



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



$titre = 'Edition du profil';

include('.././includes/haut.php'); //contient le doctype, et head.



/**********Fin entête et titre***********/

?>		



		<?php



include('.././includes/col_gauche.php');



		?>

    <div class= "main">

            <div class="container">

                



<?php



     $arrayligue1 = array('Angers', 'Auxerre', 'Bastia', 'Bordeaux' , 'Caen' , 'Dijon', 'Guingamp', 'Juventus', 'Lille' , 'Lorient' ,'Lyon', 'Marseille', 'Metz', 'Monaco' , 'Montpellier' ,'Nancy', 'Nantes', 'Nice'  ,'PSG', 'Rennes', 'Saint Etienne' , 'Toulouse');                    

                

if (empty($_POST['sent'])) // Si la variable est vide, on peut considérer qu'on est sur la page de formulaire

    {                

                //Si on choisit de modifier son profil



        //On commence par s'assurer que le membre est connecté

        if (isset ($_SESSION['membre_id'])) 

        {

        //On prend les infos du membre

        $data = $bdd->prepare('SELECT * FROM membres WHERE membre_id = :id ');

        $data->execute(array('id' => $_SESSION['membre_id'] ));

        $data = $data->fetch(PDO::FETCH_ASSOC);

?>



        <h1>Modifier mon profil</h1>

        

        <form method="post" action="editer_profil.php" enctype="multipart/form-data">



        Pseudo : <strong><?php echo stripslashes(htmlspecialchars($data['membre_pseudo'])) ;?> </strong><br/>   <br/>    

 

        <fieldset><legend>Contacts</legend>



  <!-- Adresse mail -->  

        <label for="email">Votre adresse email :</label>

        <input type="text" name="email" id="email"

        value="<?php echo stripslashes(htmlspecialchars($data['membre_mail'])) ;?>" /><br><br><br>



               

        <fieldset><legend>Profil</legend>

   

  <!-- sexe -->          

        <div class="form-group">

        <label class=" control-label" for="radios" >Sexe : </label>



        <label class="radio-inline" for="radios-0">

        <input type="radio" name="sexe" id="radios-H" value="Homme" <?php echo ($data['membre_sexe']== 'Homme') ? 'checked="checked"' : ''; ?> >

        Homme

        </label> 

        <label class="radio-inline" for="radios-1">

        <input type="radio" name="sexe" id="radios-F" value="Femme" <?php echo ($data['membre_sexe']== 'Femme') ? 'checked="checked"' : ''; ?> >

        Femme

       </label> 



       </div><br>

    

  <!-- Club de coeur -->               

                 

<div class="control-group">

  <label class="control-label">Club de coeur</label>

 <div class="controls ">    

<?php 

       echo "<select name='club' class='form-control ' style='width:auto' >

 

     <option >Sélectionnez</option>"  ;

  

     foreach( $arrayligue1 as $ligue1 ) {

         

          if ($ligue1 == $data['membre_club']) {

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

<br><br>



  <!-- Avatar -->              

        <label for="avatar">Changer votre avatar :</label>

        <input type="file" name="avatar" id="avatar" />

        <br /><br />

        <label><input type="checkbox" name="delete" value="Delete" />

        Supprimer l'avatar</label>

        Avatar actuel :

        <img src="<?php echo ROOTPATH ;?>/img/avatars/<?php echo $data['membre_avatar'] ;?>"

        alt="pas d'avatar" style="height:150px;" />

     

        <br /><br />



    

        </fieldset>

        <p>

        <input type="submit" value="Modifier son profil" />

        <input type="hidden" id="sent" name="sent" value="1" />

        </p></form>';



<?php

        }

    }



else //Cas du traitement

    {

     //On déclare les variables 



    $email_erreur1 = NULL;

    $email_erreur2 = NULL;

    $sexe_erreur = NULL;

    $club_erreur = NULL;

    $avatar_erreur = NULL;

    $avatar_erreur1 = NULL;

    $avatar_erreur2 = NULL;

    $avatar_erreur3 = NULL;



    // On initialise le nombre d'erreurs

    $i = 0;



    $temps = time(); 

    $sexe = $_POST['sexe'];

    $email = $_POST['email'];

    $club = $_POST['club'];



    //Vérification de l'adresse email

    //Il faut que l'adresse email n'ait jamais été utilisée (sauf si elle n'a pas été modifiée)



    //On commence donc par récupérer le mail

        $data = $bdd->prepare('SELECT membre_mail FROM membres WHERE membre_id = :id ');

        $data->execute(array('id' => $_SESSION['membre_id'] ));

        $data = $data->fetch(PDO::FETCH_ASSOC);



    if (strtolower($data['membre_mail']) != strtolower($email))

    {

        //Il faut que l'adresse email n'ait jamais été utilisée

        $result = $bdd->prepare('SELECT COUNT(*) AS nbr FROM membres WHERE membre_mail = :mail ');

        $result->execute(array('mail' => $email ));

        $result = $result->fetch(PDO::FETCH_ASSOC);    

           

        if($result['nbr'] > 0)    

        {

            $email_erreur1 = "Votre adresse email est déjà utilisé par un membre";

            $i++;

        }



        //On vérifie la forme maintenant

        if (!preg_match("#^[a-z0-9A-Z._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $email) || empty($email))

        {

            $email_erreur2 = "Votre nouvelle adresse E-Mail n'a pas un format valide";

            $i++;

        }

    }



    //Vérification de la signature

    if ($sexe == '')

    {

        $sexe_erreur = "Pas de sexe renseigné";

        $i++;

    }

 

 

    //Vérification de l'avatar

    

        if (!empty($_FILES['avatar']['size']))

    {

        //On définit les variables :

        $extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png', 'bmp', 'ico' ); //Liste des extensions valides

        

        if ($_FILES['avatar']['error'] > 0)

        {

               $avatar_erreur1= "Erreur lors du transfert de l'avatar : ";

		       $i++;

        }

        

        $extension_upload = strtolower(substr(  strrchr($_FILES['avatar']['name'], '.')  ,1));

        if (!in_array($extension_upload,$extensions_valides) )

        {

                $avatar_erreur2= "Extension de l'avatar incorrecte";

                $i++;

        }

    }



    echo '<h1>Modification du profil</h1><br>';



 

    if ($i == 0) // Si $i est vide, il n'y a pas d'erreur

    {

                        // on récupère le chemin de l'avatar avant modification

                $update = $bdd->prepare('SELECT membre_avatar FROM membres WHERE membre_id = :id ');

                $update->execute(array('id' => $_SESSION['membre_id'] ));

                $update = $update->fetch(PDO::FETCH_ASSOC);

                $avatar2 = $update['membre_avatar'];

                $chemin = ABSPATH."/img/avatars/".$avatar2;   

        

        if (!empty($_FILES['avatar']['size']))

        {

            (!empty($avatar2)) ? unlink($chemin) : '';

        //On modifie la table

            $nomavatar=(!empty($_FILES['avatar']['size']))?move_avatar($_FILES['avatar']):''; // On déplace le fichier de l'avatar



        $req = $bdd->prepare('UPDATE membres SET membre_avatar= :avatar WHERE membre_id = :id ');

        $req->execute(array('avatar' => $nomavatar,

                            'id' => $_SESSION['membre_id']));

        $req->closeCursor();

        }

 

        //Une nouveauté ici : on peut choisis de supprimer l'avatar

        if (isset($_POST['delete']))

        {

            (!empty($avatar2)) ? unlink($chemin) : '';

                $req = $bdd->prepare('UPDATE membres SET membre_avatar="" WHERE membre_id = :id ');

                $req->execute(array('id' => $_SESSION['membre_id']));

                $req->closeCursor();

        }

   

       

        echo'<h3>Modification terminée</h3>';

        echo'<p>Votre profil a été modifié avec succès !</p>';

        echo'<p>Cliquez <a href="'.ROOTPATH.'/index.php">ici</a> 

        pour revenir à la page d accueil</p>';

 

        //On modifie la table

                $req = $bdd->prepare('UPDATE membres SET membre_mail= :mail , membre_sexe= :sexe, membre_club= :club  WHERE membre_id = :id');

                $req->execute(array('mail' => $email,

                                    'sexe' => $sexe,

                                    'club' => $club,

                                    'id' => $_SESSION['membre_id']));

                $req->closeCursor();

     }

    else

    {

        echo'<h1>Modification interrompue</h1>';

        echo'<p>Une ou plusieurs erreurs se sont produites pendant la modification du profil</p>';

        echo'<p>'.$i.' erreur(s)</p>';

        echo'<p>'.$email_erreur1.'</p>';

        echo'<p>'.$email_erreur2.'</p>';

        echo'<p>'.$sexe_erreur.'</p>';

        echo'<p>'.$club_erreur.'</p>';

        echo'<p>'.$avatar_erreur.'</p>';

        echo'<p>'.$avatar_erreur1.'</p>';

        echo'<p>'.$avatar_erreur2.'</p>';

        echo'<p>'.$avatar_erreur3.'</p>';

        echo'<p> Cliquez <a href="'.ROOTPATH.'/membres/editer_profil.php">ici</a> pour recommencer</p>';

    }

} //Fin du else

      

	?>

  </div>

                </div>

		<?php

		include('.././includes/bas.php'); 

       // session_destroy();

		?>

