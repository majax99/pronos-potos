<?php
/*
Index du site.

C'est ici que les membres se connectent ou que les futurs membres s'inscrivent
*/

session_start();
header('Content-type: text/html; charset=utf-8');
include('includes/config.php');

/********Actualisation de la session...**********/

include('includes/fonctions.php');
actualiser_session();

/********Fin actualisation de session...**********/

/********Entête et titre de page*********/

/*$titre = 'Inscription';*/

include('includes/haut.php'); //contient le doctype, et head.

if (isset($_SESSION['membre_pseudo'])) {
    
    header('Location: '.ROOTPATH.'/accueil.php');
	exit();
}
/**********Fin entête et titre***********/
?>
<div class="container" style= "background-image : url(img/terrain2.jpg); ">    
        <div id="loginbox" style="margin-top:200px;" class="mainbox col-md-4 col-md-offset-1 col-sm-8 col-sm-offset-2">                    
            <div class="panel panel-info" >
                    <div class="panel-heading">
                        <div class="panel-title">Se connecter</div>
                        <div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="#">Mot de passe oublié?</a></div>
                    </div>     

                    <div style="padding-top:30px;height:270px;" class="panel-body" >

                        <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                            
                        <form class="form-horizontal" name="connexion" id="connexion" method="post" action="<?php echo ROOTPATH; ?>/membres/connexion.php">
                                    
                            <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input id="login-username" type="text" class="form-control" name="pseudo" value="" placeholder="pseudo">                                        
                                    </div>
                                
                            <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                        <input id="login-password" type="password" class="form-control" name="mdp" placeholder="password">
                                    </div>
                                    

                                
                            <div class="input-group">
                                      <div class="checkbox">
                                        <label>
                                          <input type="hidden" name="validate" id="validate" value="ok"/>
                                          <input id="cookie" type="checkbox" name="cookie" value="1"> Se souvenir de moi
                                        </label>
                                      </div>
                                    </div>


                                <div style="margin-top:10px" class="form-group">
                                    <!-- Button -->

                                    <div class=" col-sm-12 controls text-center ">
                                      <input style ="margin-top:20px" id="btn-login1" type="submit" class="btn btn-success" value = "connexion"/>


                                    </div>
                                </div>


                              <!--   <div class="form-group">
                                    <div class="col-md-12 control">
                                        <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
                                            Vous n'avez pas encore de compte ! 
                                        <a href="#" onClick="$('#loginbox').hide(); $('#signupbox').show()">
                                            Inscrivez-vous ici
                                        </a>
                                        </div>
                                    </div>
                                </div>    -->
                            </form>     



                        </div>                     
                    </div>  
        </div>
    
    
            <div id="inscriptionbox" style="margin-top:200px;" class="mainbox col-md-4 col-md-offset-1 col-sm-8 col-sm-offset-2">                    
            <div class="panel panel-info" >
                    <div class="panel-heading">
                        <div class="panel-title">S'inscrire</div>
                    </div>     

                    <div style="padding-top:5px;height:270px;" class="panel-body" >
                        <h3>Bonjour</h3>
                        <p>on ne se connaît pas...Aucun problème, inscrivez-vous en-dessous.</p>



                                    <!-- Button -->
                                    
                                    <div class=" col-sm-12  text-center form-group " style="margin-top:94px">
                                    <a id="btn-login2" href="<?php echo ROOTPATH ; ?>/membres/inscription.php " class="btn btn-success" >Créer mon compte</a>
                                </div>                     
                    </div>  
        </div>
    
    
    </div>
</div>

    <!-- END # MODAL LOGIN -->
		
		<?php
		include('includes/bas.php');
		?>
