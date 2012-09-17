<?php
/**
 * -----------------------------------------------------------
 * UPDATEENTREPRISE - CIBLE PHP
 * -----------------------------------------------------------
 * Auteur : Benjamin (Bill) Planche - Aldream (4IF 2011/12)
 *          Contact - benjamin.planche@aldream.net
 * ---------------------
 * Cible pour l'ajout/modification d'une entreprise.
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
 
 // Vérification de l'authentification :
require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('modele', 'entreprise.class', 'php');
inclure_fichier('commun', 'authentification.class', 'php');

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
/* int */ $id_entreprise = 0;

if (verifierPresent('nom')) {
	$nom_entreprise = Protection_XSS(urldecode($_POST['nom']));
}
if (verifierPresent('secteur')) {
	$secteur_entreprise = Protection_XSS(urldecode($_POST['secteur']));
}
if (verifierPresent('description')) {
	$desc_entreprise = Protection_XSS(urldecode($_POST['description']));
}
if (verifierPresent('commentaire')) {
	$com_entreprise = Protection_XSS(urldecode($_POST['commentaire']));
}
if (verifierPresent('id_entreprise')) {
	$id_entreprise = intval($_POST['id_entreprise']);
}

/*
 * Appeler la couche du dessous
 */
 
/* int */ $id = Entreprise::UpdateEntreprise($id_entreprise, $nom_entreprise, $desc_entreprise, $secteur_entreprise, $com_entreprise);

/*
 * Renvoyer le JSON
 */
if ($id === 0) {
	$json['code'] = 'erreurChamp';
}
elseif ($id === Entreprise::getErreurExecRequete()) {
	$json['code'] = 'errorBDD';
}
else {
	$json['code'] = 'ok';
	$json['id'] = ($id_entreprise != 0) ? 0 : $id;
}

echo json_encode($json);

?>
