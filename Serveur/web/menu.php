<?php
	global $authentification;
	global $utilisateur;
?>

<div class="navbar navbar-fixed-top" >
  <div class="navbar-inner">
	<div class="container">
	  <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	  </a>
	  <a class="brand" href="./index.php"><img src="commun/img/logo_bin.png" alt="AEDI" height="20px" width="18px"/> AEDI</a>
	  <div class="nav-collapse">
		<ul class="nav">
			<li class="">
			<a href="./index.php"><i class="icon-home icon-white"></i> Accueil</a>
			</li>
			<li class="divider-vertical"></li>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user icon-white"></i> Etudiants <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li class="nav-header"><i class="icon-road"></i> Espace Pro.</li>
					<li><a href="index.php?page=entretienEtudiant">Simulations d'entretiens</a></li>
					<li><a href="index.php?page=stages">Stages</a></li>
					<li><a href="index.php?page=accueil_cv">CV</a></li>

					<li class="divider"></li>

					<li class="nav-header"><i class="icon-glass"></i> Espace Détente</li>
					<li><a href="#">Evénements</a></li>
					<li><a href="#">Souvenirs</a></li>
				</ul>
			</li>
			<li class="divider-vertical"></li>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-flag icon-white"></i> Entreprises <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li class="nav-header"><i class="icon-search"></i> Informations</li>
					<li><a href="#">Nos offres</a></li>
					<li><a href="index.php?page=cvtheque">CVtheque</a></li>

					<li class="divider"></li>

					<li class="nav-header"><i class="icon-star"></i> Evénements</li>
					<li><a href="index.php?page=rifs">Rencontres IF</a></li>
					<li><a href="index.php?page=inscription">Simulations d'entretiens</a></li>
					<li><a href="#">Stages</a></li>	
				</ul>
			</li>

			<li class="divider-vertical"></li>
			<li class=""><a href="#"><i class="icon-info-sign icon-white"></i> A propos</a></li>

			<li class=""><a href="#"><i class="icon-envelope icon-white"></i> Contact</a></li>

			<li class="divider-vertical"></li>
		  
		</ul>
		<form class="navbar-search pull-left" action="">
			<input type="text" class="search-query span2" placeholder="Recherche">
		</form>
		
                <script>
                    //Fonction permettant de changer le theme par le numéro
                    // du theme passé en paramètre
                    function change_theme(theme){
                        $.ajax({ // fonction permettant de faire de l'ajax
                            type: "POST", // methode de transmission des données au fichier php
                            url: "index.php", // url du fichier php
                            data: {theme : theme},
                            success: function(retour){ // si l'appel a bien fonctionné
                                location.reload() ; 
                            }
                        });
                    } 
                </script>

		<ul class="nav pull-right">
			<li class="">
				<a data-toggle="modal" href="#login_dialog">
					<i class="icon-user icon-white"></i>
					<?php 
						if( $authentification->isAuthentifie() ) {
							echo $utilisateur->getPrenom()." ".$utilisateur->getNom();
						}
						else {
							 echo "Se connecter";
						}
					?>
				</a>
			</li>
			<li class="divider-vertical"></li>
		</ul>
		  
	  </div>
	</div>
  </div>
</div>


<div id="login_dialog" class="modal hide fade">
   <div class="modal-header">
        <a class="close" data-dismiss="modal" >&times;</a>
        <h3>Authentification</h3>
    </div>
    <div class="modal-body" style="text-align: center;">

		<div style="width: 49%; display: inline-block; margin-top: 60px; vertical-align: top;">
			<form id="cas_login_form" method="post">
	        	<a id="cas_login" href="#" class="btn btn-primary" ><i class="icon-user icon-white"></i> Authentification par le CAS INSA</a>
			<input type="hidden" name="action" value="login_cas" />
			</form>
		</div>
		<div style="width: 49%; display: inline-block; border-left: 1px dotted #E0E0E0;">

		<form id="login_form">
			<div id="error" class="alert alert-error hide" style="padding-right: 10px;" > </div>

			<fieldset>
				 <div class="control-group">
			                <label class="control-label" for="username">Utilisateur</label>
			                <div class="controls">
			                 <input class="input-medium" style="margin: 0px;" id="username" type="text" />
			                </div>
			          </div>
				 <div class="control-group">
			                <label class="control-label" for="password">Mot de passe</label>
			                <div class="controls">
			                <input class="input-medium" style="margin: 0px;" id="password" type="password" />
			                </div>
			          </div>
			</fieldset>

			<p><a id="regular_login" href="#" class="btn btn-primary">S'authentifier</a></p>
		</form>
		</div>
    </div>
    <div class="modal-footer" style="text-align: center;">
		<a href="#" data-dismiss="modal" class="btn btn-danger">Annuler</a>
    </div>
</div>

