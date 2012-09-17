<?php
/**
 * -----------------------------------------------------------
 * SUPPRCONTACT - CIBLE PHP
 * -----------------------------------------------------------
 * Auteur : Benjamin (Bill) Planche - Aldream (4IF 2011/12)
 *          Contact - benjamin.planche@aldream.net
 * ---------------------
 * Cible pour la suppression d'un contact.
 * Est donc appelée par le moteur JS (Ajax) de la page Annuaire quand un contact est sélectionné.
 * Le principe (repris de Bnj Bouv) est très simple :
 * 1) On récupère l'ensemble des variables qui ont été insérées.
 * 2) On appelle le contrôleur 
 * 3) On renvoit les résultats en JSON
 * Le résultat sera de la forme :
 		{
			code : "ok", // ou "error"
		}
 */

 // Vérification de l'authentification :
require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('commun', 'authentification.class', 'php');
inclure_fichier('controleur', 'contact.class', 'php');


$authentification = new Authentification();
if( $authentification->isAuthentifie() == false ) {
        die( json_encode( array( 'code' => 'fail', 'mesg' => 'Vous n\'êtes pas authentifié.' ) ) );
}
else if( $authentification->getUtilisateur()->getPersonne()->getRole() != Personne::ADMIN &&
        $authentification->getUtilisateur()->getPersonne()->getRole() != Personne::AEDI) {
        die( json_encode( array( 'code' => 'critical', 'mesg' => 'Vous n\'êtes pas autorisé à effectuer cette action.' ) ) );
}

// Conservation de l'utilisateur
$utilisateur = $authentification->getUtilisateur();


/*
 * Récupérer et transformer le JSON
 */
/* int */ $id = 0;
if (verifierPresent('id')) {
	$id = intval($_POST['id']);

	/* bool */ $codeRet = Contact::SupprimerContactByID($id);
	if ($codeRet === Contact::getErreurExecRequete()) {
		$json['code'] = 'errorBDD';
	}
	else {
		$json['code'] = 'ok';
	}
}
else {
	$json['code'] = 'errorChamp';
}

echo json_encode($json);

?>
