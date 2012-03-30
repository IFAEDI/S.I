/**************************************************
* Author : Sébastien Mériot			  *
* Date : 25.03.2012				  *
* Description : Gestion de l'authentification par *
* le CAS ou de façon plus classique (user/pass)	  *
**************************************************/


$(document).ready( function() {

	/* Enregistrement des handlers */
	$( "a#cas_login" ).click( cas_login );
	$( "a#regular_login" ).click( regular_login );

	$( "a#user_info_save" ).click( user_info_save );
} );

/**
* Permet l'authentification par le CAS
* <=> Balancer le form sur la page courante avec une variable cachée (voir le form)
*/
function cas_login() {

	$( "#cas_login_form" ).submit();
}

/**
* Authentification de façon normale par user/mdp et en AJAX !
*/
function regular_login() {
	
	/* Vérification que les champs sont bien remplis */
	var username = $( "#login_form #username" ).val();
	var password = $( "#login_form #password" ).val();

	$( "#login_form fieldset" ).children( ".control-group" ).removeClass( "error" );

	if( password.length == 0 || username.length == 0 ) {
		if( username.length == 0 ) {
			$( "#login_form #username" ).parent().parent().addClass( "error" );
		}

		if ( password.length == 0 ) {
			$( "#login_form #password" ).parent().parent().addClass( "error" );
		}

		$( "#login_form #login_error" ).html( "Merci de remplir les champs ci-dessous." );
		$( "#login_form #login_error" ).slideDown();

		return;
	}

	/* Envoie des données */
	$.ajax( {
		type: "GET",
		dataType: "json",
		url: "commun/ajax/login.cible.php",
		data: { action : "regular_auth", username: username, password: hex_sha1(password) }, 
		success: function( msg ) {

			if( msg.code == "ok" ) {
				document.location.reload();
			}
			else {
				$( "#login_form #login_error" ).html( msg.mesg );
				$( "#login_form #login_error" ).slideDown();
			}
			
		},
		error: function( obj, ex, msg ) {

			alert( ex + ' - ' + msg + '\n' + obj.responseText );
		}
	
	} );
}

/**
* Sauvegarde les informations relatives à l'utilisateur courant
*/
function user_info_save() {

	var password = $( "#user_info_form #password" ).val();
	var nom	     = $( "#user_info_form #nom" ).val();
	var prenom   = $( "#user_info_form #prenom" ).val();
	var mail     = $( "#user_info_form #mail" ).val();
	var annee    = $( "#user_info_form #annee" ).val();

	$( "#user_info_form fieldset" ).children( ".control-group" ).removeClass( "error" );

	/* Vérifie si password existe ou pas */
	if( password == void 0  ) {
		/* Il n'existe pas alors on lui met une vide */
		password = '';
	}
	else {
		/* Sinon encodage en SHA1 pour le changer */
		password = hex_sha1(password);
	}

	/* Préparation des données à balancer */
	$.ajax( {
		type: "GET",
                dataType: "json",
                url: "commun/ajax/login.cible.php",
                data: { 
			action  : "user_info_save",
			password: password, 
			nom     : nom,
			prenom  : prenom,
			mail    : mail,
			annee   : annee
		},
                success: function( msg ) {

                        if( msg.code == "ok" ) {
				$( "#navbar_username" ).html( ' ' + msg.prenom + ' ' + msg.nom );

				$( "#user_info_dialog" ).modal( 'hide' );
                        }
                        else {
				$( "#user_info_form #user_info_error" ).addClass( 'alert-error' );

                                $( "#user_info_form #user_info_error" ).html( msg.mesg );
                                $( "#user_info_form #user_info_error" ).slideDown();
                        }

                },
                error: function( obj, ex, msg ) {

                        alert( ex + ' - ' + msg + '\n' + obj.responseText );
                }

	} );
}
