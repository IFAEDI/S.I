/**************************************************
* Author : Sébastien Mériot			  *
* Date : 25.03.2012				  *
* Description : Gestion de l'authentification par *
* le CAS ou de façon plus classique (user/pass)	  *
**************************************************/


$(document).ready( function() {

	/* Enregistrement du handler */
	$( "a#cas_login" ).click( cas_login );
	$( "a#regular_login" ).click( regular_login );
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

	$( "#login_form #username" ).parent().parent().removeClass( "error" );
	$( "#login_form #password" ).parent().parent().removeClass( "error" );

	if( password.length == 0 || username.length == 0 ) {
		if( username.length == 0 ) {
			$( "#login_form #username" ).parent().parent().addClass( "error" );
		}

		if ( password.length == 0 ) {
			$( "#login_form #password" ).parent().parent().addClass( "error" );
		}

		$( "#login_form #error" ).html( "Merci de remplir les champs ci-dessous." );
		$( "#login_form #error" ).slideDown();

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
				$( "#login_form #error" ).html( msg.mesg );
				$( "#login_form #error" ).slideDown();
			}
			
		},
		error: function( obj, ex, msg ) {

			alert( ex + ' - ' + msg + '\n' + obj.responseText );
		}
	
	} );
}
