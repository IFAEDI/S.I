<?php
/**************************************
* Author : Sébastien Mériot
* Date : 28.03.2012
* Description : Ajout de code html en fonction de l'authentification de l'utilisateur
* pour le logger ou le délogger justement.
****************************************/

global $authentification;
global $utilisateur;


if( $authentification->isAuthentifie() == false ) {
	/**************************** Non authentifie ! *********************/
?>

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
                        <div id="login_error" class="alert alert-error hide" style="padding-right: 10px;" > </div>

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

<?php
}
else {

	/****************************** Authentifié ! ***************************/

?>

<div id="user_info_dialog" class="modal hide fade">
   <div class="modal-header">
        <a class="close" data-dismiss="modal" >&times;</a>
        <h3>Vos informations</h3>
    </div>
    <div class="modal-body" style="text-align: center;">

	<form id="user_info_form">
		<div id="user_info_error" class="alert alert-error hide" style="padding-right: 10px; text-align: justify;" > </div>

		<fieldset>
			 <div class="control-group">
				<label class="control-label" for="username">Utilisateur</label>
				<div class="controls">
				 <input class="input-medium disabled" style="margin: 0px;" id="username" type="text" disabled="disabled" value="<?=$utilisateur->getLogin();?>"/>
				</div>
			  </div>
                <?php
                        if( $authentification->getAuthentificationMethode() == Authentification::AUTH_NORMAL ) {
                ?>
                         <div class="control-group">
                                <label class="control-label" for="password">Mot de passe</label>
                                <div class="controls">
                                <input class="input-medium" style="margin: 0px;" id="password" type="password" />
                                </div>
                          </div>
                <?php
                        }
		?>

			 <div class="control-group">
				<label class="control-label" for="password">Nom</label>
				<div class="controls">
				<input class="input-medium" style="margin: 0px;" id="nom" type="text" value="<?=$utilisateur->getNom();?>"/>
				</div>
			</div>
			 <div class="control-group">
				<label class="control-label" for="password">Prénom</label>
				<div class="controls">
				<input class="input-medium" style="margin: 0px;" id="prenom" type="text" value="<?=$utilisateur->getPrenom();?>" />
				</div>
			</div>
			 <div class="control-group">
				<label class="control-label" for="password">Année</label>
				<div class="controls">
					<select class="input-medium" id="annee">
						<option></option>
						<option value="3" <? if( $utilisateur->getAnnee() == 3 ) echo "SELECTED"; ?> >3IF</option>
						<option value="4" <? if( $utilisateur->getAnnee() == 4 ) echo "SELECTED"; ?> >4IF</option>
						<option value="5" <? if( $utilisateur->getAnnee() == 5 ) echo "SELECTED"; ?> >5IF</option>
						<option value="M" <? if( $utilisateur->getAnnee() == 'M' ) echo "SELECTED"; ?> >Master IF</option>
						<option value="6" <? if( $utilisateur->getAnnee() == 6 ) echo "SELECTED"; ?> >Diplomé</option>
					</select>
				</div>
			</div>
			 <div class="control-group">
				<label class="control-label" for="email">Adresse mail</label>
		                <div class="controls">
                		  <div class="input-prepend">
		                        <span class="add-on" style="margin-top: -9px;">@</span><input class="input-medium" id="mail" type="text" value="<?=$utilisateur->getMail();?>" />
                		  </div>
		                </div>
			</div>
		</fieldset>

	</form>
    </div>
    <div class="modal-footer">
	<a id="user_info_save" href="#" class="btn btn-primary">Enregistrer les modifications</a>
    </div>
</div>

<?php
}




/**
* Si l'utilisateur se connecte pour la première fois avec le CAS, on le force à rentrer ses informations perso
*/
if( $utilisateur != null && $utilisateur->premiereConnexion() == true ) {

	?>
		<script type="text/javascript">
			$( document ).ready( function() {
			
				$( "#user_info_error" ).html( "C'est la première fois que vous vous connectez? Prenez le temps de saisir ces quelques informations pour votre confort ! Vous pourrez les modifier à tout moment, en cliquant sur votre nom d'utilisateur, en haut, à gauche." )
					.removeClass( "hide" )
					.removeClass( "alert-error" );

				$( "#user_info_dialog" ).modal( {
					backdrop: true,
					keyboard: false,
					show: true
				} );
			} );
		</script>
	<?php
}

?>
