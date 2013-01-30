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

$logger = Logger::getLogger("Annuaire.updateEntreprise");

$utilisateur = controlerAuthentificationJSON( $logger, array( Personne::ADMIN, Personne::AEDI ) );
$logger->debug( "\"".$utilisateur->getLogin()."\" a lancé une requête." );


/*
 * Vérification que les champs requis sont bien renseignés
 */
/* string */ $nom_entreprise = NULL;
/* string */ $secteur_entreprise = NULL;
/* string */ $desc_entreprise = NULL;
/* string */ $com_entreprise = NULL;
/* int */ $id_entreprise = 0;

if (verifierPresent('nom') && verifierPresent('secteur') && verifierPresent('description')) {
	$nom_entreprise = Protection_XSS(urldecode($_POST['nom']));
	$secteur_entreprise = Protection_XSS(urldecode($_POST['secteur']));
	$desc_entreprise = Protection_XSS(urldecode($_POST['description']));

	/* Vérification des champs optionnels */
	if (verifierPresent('commentaire')) {
		$com_entreprise = Protection_XSS(urldecode($_POST['commentaire']));
	}
	if (verifierPresent('id_entreprise')) {
		$id_entreprise = intval($_POST['id_entreprise']);
	}

	/* int */ $id = Entreprise::UpdateEntreprise($id_entreprise, $nom_entreprise, $desc_entreprise, $secteur_entreprise, $com_entreprise);

	/* Vérification des erreurs */
	if ($id === 0) {
		$json = genererReponseStdJSON( 'erreurChamp', 'Veuillez vérifier que tous les champs sont renseignés.' );
	}
	elseif ($id === Entreprise::getErreurExecRequete()) {
		$logger->error( 'Une erreur est survenue (Login: '.$utilisateur->getLogin().').' );
		$json = genererReponseStdJSON( 'errorBDD', 'Une erreur est survenu lors de l\'enregistrement des données.' );
	}
	else {
		$logger->info( '"'.$utilisateur->getLogin().'" a modifié l\'entreprise "'.$nom_entreprise.'" ('.$id.').' );

		$json['code'] = 'ok';
		$json['id'] = ($id_entreprise != 0) ? 0 : $id;
	}
}
else {
	$json = genererReponseStdJSON( 'erreurChamp', 'Veuillez vérifier que tous les champs sont renseignés.' );
}

echo json_encode($json);

?>
