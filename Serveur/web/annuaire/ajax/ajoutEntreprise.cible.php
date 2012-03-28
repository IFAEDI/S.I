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

require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('controleur', 'entreprise.class', 'php');

/*
 * Récupérer et transformer le JSON
 */
/* string */ $nom_entreprise = NULL;
/* string */ $secteur_entreprise = NULL;
/* string */ $desc_entreprise = NULL;

if (verifierPresent('nom')) {
	$nom_entreprise = Protection_XSS($_POST['nom']);
}
if (verifierPresent('secteur')) {
	$nom_entreprise = Protection_XSS($_POST['secteur']);
}
if (verifierPresent('description')) {
	$nom_entreprise = Protection_XSS($_POST['description']);
}

/*
 * Appeler la couche du dessous
 */
 
/* int */ $id = Entreprise::UpdateEntreprise(0, $nom_entreprise, $desc_entreprise, $secteur_entreprise, '', NULL);

/*
 * Renvoyer le JSON
 */
$json['code'] = ($id != NULL) ? 'ok' : 'error';
// FIXME comment distinguer s'il n'y a pas de résultats ou une erreur ?
if ($id != NULL) {
	$json['id'] = $id;
}
echo json_encode($json);


?>
