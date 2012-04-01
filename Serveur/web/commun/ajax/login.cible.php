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
			try {
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
			catch( Exception $e ) {

				$val = array( "code" => "error", "mesg" => $e->getMessage() );
			}
		}
	}
	/* Mise à jour des informations de l'utilisateur */
	else if( $_GET['action'] == "user_info_save" ) {

		/* On check que le user est bien authentifié */
		if( $authentification->isAuthentifie() == true ) {

			/* On check que l'on a bien nos variables */
			if( !( @isset( $_GET['password'] ) && @isset( $_GET['nom'] ) && @isset( $_GET['prenom'] ) && @isset( $_GET['mails'] ) 
				&& @isset( $_GET['telephones'] ) ) ) {

				$val = array( "code" => "error", "mesg" => "Variables manquantes." );
			}
			else {

				$utilisateur = $authentification->getUtilisateur();
				$continue = true;
	
				$password = strip_tags( mysql_escape_string( $_GET['password'] ) );
				$nom      = strip_tags( mysql_escape_string( $_GET['nom'] ) );
				$prenom   = strip_tags( mysql_escape_string( $_GET['prenom'] ) );
				
				/* On regarde s'il faut mettre à jour le mot de passe */
				if( @strlen( $_GET['password'] ) > 0 ) {
	
					try {
						$result = $utilisateur->changePassword( $password );
						if( $result == false ) {

							$val = array( "code" => "fail", "mesg" => "Une erreur est survenue lors de la modification du mot de passe." );
							$continue = false;
						}
					}
					catch( Exception $e ) {

						$msg = "Une erreur est survenue lors de la requête à la base (".BD::getDerniereErreur().")";
						$val = array( "code" => "error", "mesg" => $msg );
						$continue = false;
					}
				}

				if( $continue ) {

					try {
						$result = $utilisateur->getPersonne()->changeInfo( $nom, $prenom );
						if( $result ) {

							$result = $utilisateur->getPersonne()->changeMails( $_GET['mails'] );
							if( $result ) {
							
								$result = $utilisateur->getPersonne()->changeTelephones( $_GET['telephones'] );
								if( $result ) {
									$val = array( "code" => "ok", "nom" => $nom, "prenom" => $prenom );
								}
								else {
									$val = array( "code" => "fail", "mesg" => "Une erreur est survenue lors de la mise à jour des numéros de téléphone. Veuillez réessayer ultèrieurement." );
								}
							}
							else {
								$val = array( "code" => "fail", "mesg" => "Une erreur est survenue lors de la mise à jour des mails. Veuillez réessayer ultèrieurement." );
							}
						
						}
						else {
							$val = array( "code" => "fail", "mesg" => "Une erreur est survenue lors de la mise à jour des infos. Veuillez réessayer ultèrieurement." );
						}
					}
					catch( Exception $e ) {

						$msg = "Une erreur est survenue lors de la requête à la base (".BD::getDerniereErreur().")";
						$val = array( "code" => "fail", "mesg" => $msg );
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
