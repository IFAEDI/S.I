<?php
/**
 * -----------------------------------------------------------
 * AJOUTENTREPRISE - CIBLE PHP
 * -----------------------------------------------------------
 * Auteur : Benjamin (Bill) Planche - Aldream (4IF 2011/12)
 *          Contact - benjamin.planche@aldream.net
 * ---------------------
 * Cible pour l'ajout d'une entreprise.
 * Est donc appelée par le moteur JS (Ajax) de la page Annuaire quand une entreprise est sélectionnée dans la liste des noms.
 * Le principe (repris de Bnj Bouv) est très simple :
 * 1) On récupère l'ensemble des variables qui ont été insérées.
 * 2) On appelle le contrôleur 
 * 3) On renvoit les résultats en JSON
 * Le résultat sera de la forme :
 		{
			code : "ok", // ou "error" - si error, le champ id n'est pas présent
			id : 1 		// ID de l'entreprise ajoutée
		}
 */

header( 'Content-Type: application/json' );

require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('commun', 'authentification.class', 'php');
inclure_fichier('modele', 'entreprise.class', 'php');

/* Vérification que l'utilisateur est bien auhtentifié et autorisé à ajouter une entreprise */
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
/* string */ $nom_entreprise = NULL;
/* string */ $secteur_entreprise = NULL;
/* string */ $desc_entreprise = NULL;
/* string */ $com_entreprise = NULL;
/* int */ $idVille_entreprise = 0;

if (verifierPresent('nom') && verifierPresent('secteur') && verifierPresent('description') && verifierPresent('commentaire') && verifierPresent('idVille')) {
	$nom_entreprise = Protection_XSS($_POST['nom']);
	$secteur_entreprise = Protection_XSS($_POST['secteur']);
	$desc_entreprise = Protection_XSS($_POST['description']);
	$com_entreprise = Protection_XSS($_POST['commentaire']);
	$idVille_entreprise = intval($_POST['idVille']);

	/* int */ $id = Entreprise::UpdateEntreprise(0, $nom_entreprise, $desc_entreprise, $secteur_entreprise, $com_entreprise, $idVille_entreprise);

	if ($id == 0) {
		$json['code'] = 'errorChamp';
	}
	/* TODO : Check de la fonction - Pas très claire => Exception ?! */
	else if ($id == Entreprise::getErreurExecRequete()) {
		$json['code'] = 'errorBDD';
	}
	else if ($id != NULL) {
		$json['code'] = 'ok';
		$json['id'] = $id;
	}
	else {
		$json['code'] = 'errorBDD';
	}
}
else {
	$json['code'] = 'errorChamp';
	$json['mesg'] = 'Veuillez vérifier que les champs sont correctement renseignés.';
}


echo json_encode($json);

?>
