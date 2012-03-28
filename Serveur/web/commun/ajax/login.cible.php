<?php
/*****************************************
* Author : Sébastien Mériot		 *
* Date : 25.03.2012			 *
* Description : Cible des requêtes ajax  *
* concernant l'authentification          *
*****************************************/

require_once( '../php/base.inc.php' );


inclure_fichier( 'commun', 'authentification.class', 'php' );


/* Réception d'une action à effectuer dans le cadre d'une requête AJAX */
if( @isset( $_GET['action'] ) ) {

	$authentification = new Authentification();
	$val = array();

	/* Authentification régulière via user/pass */
	if( $_GET['action'] == "regular_auth" ) {

		if( !( @isset( $_GET['username'] ) && @isset( $_GET['password'] ) ) ) {
			$val = array( "code" => "fail", "mesg" => "Variables manquantes." );
		}
		else {

			$login = mysql_escape_string( $_GET['username'] );
			$mdp   = mysql_escape_string( $_GET['password'] );

			/* Recherche dans la base si le couple utilisateur/passwd existe */
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
	/* Mise à jour des informations de l'utilisateur */
	else if( $_GET['action'] == "user_info_save" ) {

		/* On check que le user est bien authentifié */
		if( $authentification->isAuthentifie() == true ) {

			/* On check que l'on a bien nos variables */
			if( !( @isset( $_GET['password'] ) && @isset( $_GET['nom'] ) && @isset( $_GET['prenom'] ) 
						&& @isset( $_GET['annee'] ) && @isset( $_GET['mail'] ) ) ) {

				$val = array( "code" => "error", "mesg" => "Variables manquantes." );
			}
			else {

				$utilisateur = $authentification->getUtilisateur();
				$continue = true;
	
				$password = strip_tags( mysql_escape_string( $_GET['password'] ) );
				$nom      = strip_tags( mysql_escape_string( $_GET['nom'] ) );
				$prenom   = strip_tags( mysql_escape_string( $_GET['prenom'] ) );
				$annee    = strip_tags( mysql_escape_string( $_GET['annee'] ) );
				$mail	  = strip_tags( mysql_escape_string( $_GET['mail'] ) );

				/* On regarde s'il faut mettre à jour le mot de passe */
				if( @strlen( $_GET['password'] ) > 0 ) {
	
					if( $utilisateur->changePassword( $password ) == false ) {

						$val = array( "code" => "fail", "mesg" => "Une erreur est survenue lors de la modification du mot de passe." );
						$continue = false;
					}
				}

				if( $continue ) {

					$result = $utilisateur->changeInfoPerso( $nom, $prenom, $mail, $annee );

					if( $result ) {
						$val = array( "code" => "ok", "nom" => $nom, "prenom" => $prenom );
					}
					else {
						$val = array( "code" => "fail", "mesg" => "Une erreur est survenue lors de la mise à jour des infos. Veuillez réessayer ultèrieurement." );
					}
				}
			}
		}
		else {

			$val = array( "code" => "error", "mesg" => "Vous n'êtes pas authentifié." );
		}
	}
	else {
		$val = array( "code" => "error", "mesg" => "Action non autorisée." );
	}

	echo json_encode( $val );
}


?>
