<?php
/*

MAJ des profils des utilisateurs

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


include('.././includes/haut.php'); //contient le doctype, et head.

/**********Fin entête et titre***********/
?>		

		<?php
include('.././includes/col_gauche.php');
		?>


<?php

      $arrayligue1 = array('Angers', 'Auxerre', 'Bastia', 'Bordeaux' , 'Caen' , 'Dijon', 'Guingamp', 'Juventus', 'Lille' , 'Lorient' 
,'Lyon', 'Marseille', 'Metz', 'Monaco' , 'Montpellier' ,'Nancy', 'Nantes', 'Nice'  ,'PSG', 'Rennes', 'Saint Etienne' , 'Toulouse');      

$id=0;
	if ( !empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}
     
        if ( !empty($_POST)) {

            // on initialise nos erreurs
            $pseudoError = null;
            $sexeError = null;
            $groupeError = null;
            $mailError = null;
            $statutError = null;
            $clubError = null;


            // On assigne nos valeurs
            $pseudo = $_POST['pseudo'];
            $sexe = $_POST['sexe'];
            $groupe = $_POST['groupe'];
            $mail= $_POST['mail'];
            $statut = $_POST['statut'];
            $club = $_POST['club'];
            


            // On verifie que les champs sont remplis
            $valid = true;
            if (empty($pseudo)) {
                $pseudoError = 'Entrez un pseudo';
                $valid = false;
            }
            if (empty($sexe)) {
                $sexeError = 'Indiquez le sexe';
                $valid = false;
            }
            
            if (empty($groupe)) {
                $groupeError = 'Indiquez le groupe';
                $valid = false;
            }

            if (empty($mail)) {
                $mailError = 'Entrez un email';
                $valid = false;
            } else if  (!preg_match("#^[a-z0-9A-Z._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $mail)) {
                $mailError = 'Entrez un email valide';
                $valid = false;
            }

            if (empty($statut)) {
                $statutError = 'Indiquez le statut';
                $valid = false;
            }

            // mise à jour des donnés
                if ($valid) {
                
                // on récupère le chemin de l'avatar avant modification
                $update = $bdd->prepare('SELECT membre_avatar FROM membres WHERE membre_id = :id ');
                $update->execute(array('id' => $id ));
                $update = $update->fetch(PDO::FETCH_ASSOC);
                $avatar2 = $update['membre_avatar'];
                $chemin = ABSPATH."/img/avatars/".$avatar2;   
                    
                    
                if (!empty($_FILES['avatar']['size']))
        {
            // Si le membre a déjà un avatar et qu'il souhaite le modifier, on supprime l'ancien de la base
            
            (!empty($avatar2)) ? unlink($chemin) : '';
            
            
        //On modifie la table
                    
            $nomavatar=(!empty($_FILES['avatar']['size']))?move_avatar($_FILES['avatar']):''; // On déplace le fichier de l'avatar

        $req = $bdd->prepare('UPDATE membres SET membre_avatar= :avatar WHERE membre_id = :id ');
        $req->execute(array('avatar' => $nomavatar,
                            'id' => $id));
        $req->closeCursor();
        }
 
        //Une nouveauté ici : on peut choisir de supprimer l'avatar
        if (isset($_POST['delete']))
        {
            (!empty($avatar2)) ? unlink($chemin) : '';
                $req = $bdd->prepare('UPDATE membres SET membre_avatar="" WHERE membre_id = :id ');
                $req->execute(array('id' => $id));
                $req->closeCursor();
        }            
                    
        $req = $bdd->prepare('UPDATE membres SET membre_pseudo= :pseudo, membre_sexe = :sexe, membre_groupe = :groupe, membre_club = :club, 
                                                membre_mail = :mail, membre_statut = :statut WHERE membre_id = :id ');
        $req->execute(array('pseudo' => $pseudo,
                            'sexe' => $sexe,
                            'groupe' => $groupe,
                            'club' => $club,
                            'mail' => $mail,
                            'statut' => $statut,
                            'id' => $id));
        $req->closeCursor();  
                    

                    
            } 
           }
    else {

                $data = $bdd->prepare('SELECT * FROM membres WHERE membre_id = :id ');
                $data->execute(array('id' => $id ));
                $data = $data->fetch(PDO::FETCH_ASSOC);
                $pseudo = $data['membre_pseudo'];
                $sexe = $data['membre_sexe'];
                $mail = $data['membre_mail'];
                $groupe = $data['membre_groupe'];
                $statut = $data['membre_statut'];
                $avatar = $data['membre_avatar'];
                $club = $data['membre_club'];
         }
        
        ?>


     
<div class= "main">
<div class="container">

<div class="row">

<h3>Modifier un contact</h3>

</div>

            <form method="post" action="update.php?id=<?php echo $id ;?>" enctype="multipart/form-data">

<!-- PSEUDO -->              
<div class="control-group <?php echo!empty($pseudoError) ? 'error' : ''; ?>">
                    <label class="control-label">Pseudo</label>

<div class="controls">
                        <input name="pseudo" type="text"  placeholder="Pseudo" value="<?php echo!empty($pseudo) ? $pseudo : ''; ?>">
                        <?php if (!empty($pseudoError)): ?>
                            <span class="help-inline"><?php echo $pseudoError; ?></span>
                        <?php endif; ?>
</div>

</div>
<br><br>   


<!-- Adresse mail -->  
<div class="control-group<?php echo!empty($mailError) ? 'error' : ''; ?>">
                    <label class="control-label">Mail</label>

<div class="controls">
                        <input type="text" name="mail" value="<?php echo!empty($mail) ? $mail : ''; ?>">
                        <?php if (!empty($mailError)): ?>
                            <span class="help-inline"><?php echo $mailError; ?></span>
                        <?php endif; ?>
</div>

</div>
<br><br>   

<!-- Sexe -->                  
<div class="control-group<?php echo!empty($sexeError) ? 'error' : ''; ?>">
                    Sexe<label class="checkbox-inline"></label><br>

        <label class="radio-inline" for="radios-0">
        <input type="radio" name="sexe" id="radios-H" value="Homme" <?php if (isset($sexe) && $sexe == "Homme") echo "checked"; ?> >
        Homme
        </label> 
        <label class="radio-inline" for="radios-1">
        <input type="radio" name="sexe" id="radios-F" value="Femme" <?php if (isset($sexe) && $sexe == "Femme") echo "checked"; ?> >
        Femme
       </label> 

       </div>                
 <br><br>      
 
<!-- Groupe -->               
<div class="control-group<?php echo!empty($groupeError) ? 'error' : ''; ?>">
                    <label class="control-label">Groupe</label>

<div class="controls">
                        <input type="text" name="groupe" value="<?php echo !empty($groupe) ? $groupe : ''; ?>">
                        <?php if (!empty($groupeError)): ?>
                            <span class="help-inline"><?php echo $groupeError; ?></span>
                        <?php endif; ?>
</div>

</div>
 <br><br>       
                
 <!-- Club de coeur -->                   
<div class="control-group<?php echo!empty($clubError) ? 'error' : ''; ?>">
  <label class="control-label">Club de coeur</label>
 <div class="controls ">    
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
 <br><br>                 
                
 <!-- Statut -->                  
<div class="control-group<?php echo!empty($statutError) ? 'error' : ''; ?>">
                    <label class="control-label">Statut</label>

<div class="controls">
                        <input type="text" name="statut" value="<?php echo!empty($statut) ? $statut : ''; ?>">
                        <?php if (!empty($statutError)): ?>
                            <span class="help-inline"><?php echo $statutError; ?></span>
                        <?php endif; ?>
</div>

</div>
<br>             

<!-- avatar -->      
<div class="control-group">
                    <label class="control-label">Avatar</label>

<div class="controls">
                        <input type="file" name="avatar" id="avatar" />
<br>
        <label><input type="checkbox" name="delete" value="Delete" />
        Supprimer l'avatar</label><br />
        Avatar actuel :
        <img src="<?php echo ROOTPATH ;?>/img/avatars/<?php echo $avatar ;?>"
        alt="pas d'avatar" style="height:150px;" />
</div>

</div>

<br /><br />
<div class="form-actions">
                    <input type="submit" class="btn btn-success" name="submit" value="submit">
                    <a class="btn" href="<?php echo ROOTPATH; ?>/admin/modif_user.php">Retour</a>
</div>

            </form>



</div>
    
    
    
  </div>
    
		<?php
		include('.././includes/bas.php'); 
		?>
