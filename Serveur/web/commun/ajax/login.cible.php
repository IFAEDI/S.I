<?php
/*****************************************
* Author : Sébastien Mériot		 *
* Date : 25.03.2012			 *
* Description : Cible des requêtes ajax  *
* concernant l'authentification          *
*****************************************/

require_once( '../php/base.inc.php' );


inclure_fichier( 'commun', 'authentification.class', 'php' );


if( @isset( $_GET['action'] ) ) {

	$val = array();

	if( $_GET['action'] == "regular_auth" ) {

		if( !( @isset( $_GET['username'] ) && @isset( $_GET['password'] ) ) ) {
			$val = array( "code" => "fail", "mesg" => "Variables manquantes." );
		}
		else {

			$login = $_GET['username'];
			$mdp   = $_GET['password'];

			/* Recherche dans la base si le couple utilisateur/passwd existe */
			$authentification = new Authentification();
			$result = $authentification->authentificationNormale( $login, $mdp );

			switch( $result ) {
				/* Authentification réussie */
				case Authentification::ERR_OK:
					$val = array( "code" => "ok" );
					break;
				/* Authentification foirée */
				case Authentification::ERR_ID_INVALIDE:
					$val = array( "code" => "fail", "mesg" => "Identifiants non valides." );
					break;
				case Authentification::ERR_AMBIGUITE:
					$val = array( "code" => "error", "mesg" => "Une ambiguité est survenue lors de la procédure d'authentification." );
					break;
				case Authentification::ERR_BD:
					$val = array( "code" => "error", "mesg" => "La base de données a rencontré une erreur. Veuillez réessayer ultérieurement." );
					break;
			}
		}
	}
	else {
		$val = array( "code" => "error", "mesg" => "Action non autorisée." );
	}

	echo json_encode( $val );
}


?>
