/**************************************************
* Author : Sébastien Mériot			  *
* Date : 25.03.2012				  *
* Description : Gestion de l'authentification par *
* le CAS de l'INSA.				  *
**************************************************/


$(document).ready( function() {

	/* Enregistrement du handler */
	$( "a#cas_login" ).click( cas_login );
	$( "a#regular_login" ).click( regular_login );
} );


function cas_login() {

	alert( "TODO : Redirection CAS" );

}

function regular_login() {
	
	alert( "TODO : Check des identifiants" );
}
