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
			$val = genererReponseStdJSON( 'fail', 'Variables manquantes.' );
		}
		else {
			$logger = Logger::getLogger("Login.RegularAuth");

			$login = mysql_escape_string( $_GET['username'] );
			$mdp   = mysql_escape_string( $_GET['password'] );

			/* Recherche dans la base si le couple utilisateur/passwd existe */
			try {
				$result = $authentification->authentificationNormale( $login, $mdp );

				switch( $result ) {
					/* Authentification réussie */
					case Authentification::ERR_OK:
						$val = genererReponseStdJSON( 'ok', 'Authentification réussie.' );
						$logger->info( "L'utilisateur \"".$login."\" s'est connecté. - ".$_SERVER['REMOTE_ADDR'] );
						break;
					/* Authentification foirée */
					case Authentification::ERR_ID_INVALIDE:
						$val = genererReponseStdJSON( 'fail', 'Identifiants non valides.' );
						$logger->warn( "Tentative d'authentification échouée. - ".$_SERVER['REMOTE_ADD'] );
						break;
					case Authentification::ERR_AMBIGUITE:
						$val = genererReponseStdJSON( 'error', 'Une ambiguité est survenue lors de la procédure d\'authentification.' );
						$logger->warn( "Une ambiguité existe avec l'utilisateur \"".$login."\"." );
						break;
					case Authentification::ERR_BD:
						$val = genererReponseStdJSON( 'error', 'Une erreur interne survenue lors de la procédure d\'authentification. Veuillez réessayer ultèrieurement.' );
						$logger->error( "Une erreur est survenue lorsque l'utilisateur \"".$login."\" a voulu se connecter." );
						break;
				}
			}
			catch( Exception $e ) {
				$val = genererReponseStdJSON( 'error', $e->getMessage() );
				$logger->error( "Une erreur est survenue lorsque l'utilisateur \"".$login."\" a voulu se connecter : ".$e->getMessage() );
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

				$val = genererReponseStdJSON( 'error', 'Variables manquantes.' );
			}
			else {
				$logger = Logger::getLogger("Login.UserInfoSave");

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
							$val = genererReponseStdJSON( 'fail', 'Une erreur est survenue lors de la modification du mot de passe.');
							$logger->error( "Une erreur est survenue lorsque l'utilisateur \"".$utilisateur->getLogin()."\" a voulu changer son mdp." );
							$continue = false;
						}
					}
					catch( Exception $e ) {

						$logger->error( "Une erreur est survenue lorsque l'utilisateur \"".$utilisateur>getLogin()."\" a voulu changer son mdp : ".$e->getMessage()." [Trace BD: ".BD::getDerniereErreur()."]" );
						$val = genererReponseStdJSON( 'error', 'Une erreur est survenue lors de la modification du mot de passe.');
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
									$logger->info( "L'utilisateur \"".$utilisateur->getLogin()."\" a mis à jour ses infos." );
								}
								else {
									$logger->error( "Une erreur est survenue lorsque l'utilisateur \"".$utilisateur->getLogin()."\" a voulu mettre à jour ses infos (téléphone)." );
									$val = genererReponseStdJSON( 'fail', 'Une erreur est survenue lors de la modification des informations.');
								}
							}
							else {
								$logger->error( "Une erreur est survenue lorsque l'utilisateur \"".$utilisateur->getLogin()."\" a voulu mettre à jour ses infos (mail)." );
								$val = genererReponseStdJSON( 'fail', 'Une erreur est survenue lors de la modification des informations.');
							}
						
						}
						else {
							$logger->error( "Une erreur est survenue lorsque l'utilisateur \"".$utilisateur->getLogin()."\" a voulu mettre à jour ses infos (civilité)." );
							$val = genererReponseStdJSON( 'fail', 'Une erreur est survenue lors de la modification des informations.');
						}
					}
					catch( Exception $e ) {

						$logger->error( "Une erreur est survenue lorsque l'utilisateur \"".$utilisateur->getLogin()."\" a voulu mettre à jour ses infos : ".$e->getMessage()."  / Trace BD : [".BD::getDerniereErreur()."]" );
						$val = genererReponseStdJSON( 'fail', 'Une erreur est survenue lors de la modification des informations.');
					}
				}
			}
		}
		else {
			$logger->warn( "Requête d'un utilisateur non authentifié - ".$_SERVER['REMOTE_ADDR'] );
			$val = genererReponseStdJSON( 'error', 'Vous n\'êtes pas authentifié.');
		}
	}
	else {
		$logger = Logger::getLogger( "Login" );
		$logger->warn( "Requête non valide [contrôleur inexistant] - ".$_SERVER['REMOTE_ADDR'] );
		$val = genererReponseStdJSON( 'error', 'Action non autorisée.');
	}

	echo json_encode( $val );
}


?>
