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
	/* Suppression d'un utilisateur */
	else if( $_GET['action'] == "del_user" ) {
		$val = array( 'code' => 'info', 'mesg' => 'Not implemented yet.' );
	}
	/* Edition d'un utilisateur */
	else if( $_GET['action'] == "edit_user" ) {
		$val = array( 'code' => 'info', 'mesg' => 'Not implemented yet.' );
	}
	/** Ajout d'un utilisateur */
	else if( $_GET['action'] == "add_user" ) {
		$val = array( 'code' => 'info', 'mesg' => 'Not implemented yet.' );
	}
	else {
		$val = array( 'code' => 'error', 'mesg' => 'Action invalide.' );
	}

	echo json_encode( $val );
}


?>
