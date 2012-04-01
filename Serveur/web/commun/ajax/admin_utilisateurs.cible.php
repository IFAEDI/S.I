<?php
/***********************************************
* Author : Sébastien Mériot		       *
* Date : 30.03.2012			       *
* Description : Cible des requêtes ajax        *
* concernant l'administration des utilisateurs *
***********************************************/

require_once( '../php/base.inc.php' );

inclure_fichier( 'commun', 'authentification.class', 'php' );

/* Avant tout, on vérifie que l'on a bien le niveau d'accréditation nécessaire ! */
$authentification = new Authentification();

if( $authentification->isAuthentifie() == false ) {
	die( json_encode( array( 'code' => 'fail', 'mesg' => 'Vous n\'êtes pas authentifié.' ) ) );
}
else if( $authentification->getUtilisateur()->getPersonne()->getRole() != Personne::ADMIN ) {
	die( json_encode( array( 'code' => 'critical', 'mesg' => 'Vous n\'êtes pas autorisé à effectuer cette action.' ) ) );
}


/* Traitement des requêtes reçues */


/* Réception d'une action à effectuer dans le cadre d'une requête AJAX */
if( @isset( $_GET['action'] ) ) {

	$val = array();

	/* Récupération de l'ensemble des utilisateurs */
	if( $_GET['action'] == "get_user_list" ) {

		try {
			/* Récupération */
			$utilisateurs = Utilisateur::RecupererTousLesUtilisateurs();

			/* On formatte nos données ! */
			/* On renvoit : id, login, nom, prenom, service et type ce qui est pas mal */

			$data = array();
			$i = 0;
			foreach( $utilisateurs as $current ) {

				$data[$i] = array( 'id' => $current->getId(), 
						'login' => $current->getLogin(),
						'nom' => $current->getPersonne()->getNom(), 
						'prenom' => $current->getPersonne()->getPrenom(),
						'service' => $current->getService(),
						'type' => $current->getPersonne()->getRole() );

				$i++;
			}

			$val = array( 'code' => 'ok', 'utilisateurs' => $data );
		}
		catch( Exception $e ) {
			$val = array( 'code' => 'error', 'mesg' => $e->getMessage() );
		}
	}
	/* Récupérations des libellés des services et des accréditations */
	else if( $_GET['action'] == "get_labels" ) {

		$service = Authentification::$AUTH_TYPES; 
		$type    = Personne::$ROLES; 

		$val = array( 'code' => 'ok', 'services' => $service, 'types' => $type );
	}
	/* Récupération des informations d'un utilisateur précis */
	else if( $_GET['action'] == "get_user_info" ) {

		/* On check que l'on a tous les paramètres */
		if( ! (@isset( $_GET['id'] ) ) ) {
			$val = array( 'code' => 'error', 'mesg' => 'Variable manquante.' );
		}
		else {

			$id = mysql_escape_string( $_GET['id'] );

			try {
				/* On fait dans les noms de variables cours car faire un tableau associatif, c'est chiant. */
				$u = Utilisateur::RecupererUtilisateur( $id );
				if( $u != null ) {

					$p = $u->getPersonne();

					$info = array( 'login' => $u->getLogin(), 'service' => $u->getService(),
							'nom' => $p->getNom(), 'prenom' => $p->getPrenom(), 'role' => $p->getRole(),
							'mails' => $p->getMails(), 'telephones' => $p->getTelephones() );

					$val = array( 'code' => 'ok', 'utilisateur' => $info );
				}
				else {
					$val = array( 'code' => 'fail', 'mesg' => 'Utilisateur introuvable.' );
				}
			}
			catch( Exception $e ) {
				$val = array( 'code' => 'error', 'mesg' => 'Une erreur s\'est produite en interrogeant la base : '.$e->getMessage() );
			}
		}
	}
	/* Suppression d'un utilisateur */
	else if( $_GET['action'] == "del_user" ) {

		/* Check que l'on a bien toutes les infos */
		if( ! (@isset( $_GET['id'] ) && @isset( $_GET['delP'] ) ) ) {

			$val = array( 'code' => 'error', 'mesg' => 'Variables manquantes.' );
		}
		else {
		
			$id	= mysql_escape_string( $_GET['id'] );
			$delP   = mysql_escape_string( $_GET['delP'] );

			$utilisateur = Utilisateur::RecupererUtilisateur( $id );
			if( $utilisateur == null ) {
				$val = array( 'code' => 'fail', 'mesg' => 'Utilisateur introuvable.' );
			}
			else {

				if( $utilisateur->supprimerUtilisateur( $delP ) == true ) {
					$val = array( 'code' => 'ok' );
				}
				else {
					$val = array( 'code' => 'fail', 'mesg' => 'Une erreur est survenue lors de la suppression.' );
				}
			}
		}
	}
	/* Edition d'un utilisateur */
	else if( $_GET['action'] == "edit_user" ) {

		/* Check que l'on a bien toutes les infos */
		if( ! (@isset( $_GET['id'] ) && @isset( $_GET['login'] ) && @isset( $_GET['pwd'] ) && @isset( $_GET['nom'] ) &&
			@isset( $_GET['prenom'] ) && @isset( $_GET['role'] ) && @isset( $_GET['mails'] ) && @isset( $_GET['telephones'] ) ) ) {

			$val = array( 'code' => 'error', 'mesg' => 'Variables manquantes.' );
		}
		else {
			$id     = strip_tags( mysql_escape_string( $_GET['id'] ) );
			$login  = strip_tags( mysql_escape_string( $_GET['login'] ) );
			$pwd    = strip_tags( mysql_escape_string( $_GET['pwd'] ) );
			$nom    = strip_tags( mysql_escape_string( $_GET['nom'] ) );
			$prenom = strip_tags( mysql_escape_string( $_GET['prenom'] ) );
			$role   = strip_tags( mysql_escape_string( $_GET['role'] ) );

			try {
				/* Si $id < 0 on ajoute un nouveau, sinon on édite */
				if( $id < 0 ) {

					/* Check que l'utilisateur n'existe pas déjà */
					if( Utilisateur::UtilisateurExiste( $login ) == true ) {
						throw new Exception( "L'utilisateur '$login' existe déja." );
					}

					/* Ajout de l'utilisateur et de la personne */
					$u = Utilisateur::AjouterUtilisateur( $login, $pwd );
					if( $u == null ) {
						throw new Exception( "L'utilisateur n'a pas pu être créé." );
					}

					$p = Personne::AjouterPersonne( $nom, $prenom, $role, $u );
					if( $p == null ) {
						throw new Exception( "La personne associée à l'utilisateur n'a pas pu être créée." );
					}

					/* On met à jour les infos diverses */
					if( $p->changeMails( $_GET['mails'] ) == false ) {
						throw new Exception( "Erreur lors de la mise à jour des mails." );
					}

					if( $p->changeTelephones( $_GET['telephones'] ) == false ) {
						throw new Exception( "Erreur lors de la mise à jour des téléphones." );
					}
					

					$val = array( 'code' => 'ok' );
				}
				else {
					/* Récupération de l'utilisateur et de la personne pour travailler dessus */
					$u = Utilisateur::RecupererUtilisateur( $id );
					if( $u != null ) {
						$p = $u->getPersonne();
						if( $p != null ) {

							/* S'il faut changer le mot de passe uniquement */
							if( strlen( $pwd ) > 0 ) {
								if( $u->changePassword( $pwd ) == false ) {
									throw new Exception( "Erreur lors du changement du mot de passe." );
								}
							}

							/* Changement du login si nécessaire */
							if( strcmp( $u->getLogin(), $login ) != 0 ) {

								/* Check que l'utilisateur n'existe pas déjà */
			                                        if( Utilisateur::UtilisateurExiste( $login ) == true ) {
                        			                        throw new Exception( "L'utilisateur '$login' existe déja." );
			                                        }

								if( $u->changeLogin( $login ) == false ) {
									throw new Exception( "Erreur lors du changement de login." );
								}
							}

							/* Mise à jour des infos de la personne à présent */
							if( $p->changeInfo( $nom, $prenom ) == false ) {
								throw new Exception( "Erreur lors de la mise à jour des infos perso." );
							}

							if( $p->changeMails( $_GET['mails'] ) == false ) {
								throw new Exception( "Erreur lors de la mise à jour des mails." );
							}

							if( $p->changeTelephones( $_GET['telephones'] ) == false ) {
								throw new Exception( "Erreur lors de la mise à jour des téléphones." );
							}

							if( $p->changeRole( $role ) == false ) {
								throw new Exception( "Erreur lors de la mise à jour du rôle." );
							}

							$val = array( 'code' => 'ok' );
						}
						else {
							$val = array( 'code' => 'error', 'mesg' => 'Personne associée à l\'utilisateur introuvable.' );
						}
					}
					else {
						$val = array( 'code' => 'error', 'mesg' => 'Utilisateur introuvable.' );
					}
				}
			}
			catch( Exception $e ) {
				$val = array( 'code' => 'fail', 'mesg' => $e->getMessage() );
			}
		}
	}
	else {
		$val = array( 'code' => 'error', 'mesg' => 'Action invalide.' );
	}

	echo json_encode( $val );
}


?>
