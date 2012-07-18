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
/* string */ $com_entreprise = NULL;
/* int */ $idVille_entreprise = 0;

if (verifierPresent('nom')) {
	$nom_entreprise = Protection_XSS($_POST['nom']);
}
if (verifierPresent('secteur')) {
	$secteur_entreprise = Protection_XSS($_POST['secteur']);
}
if (verifierPresent('description')) {
	$desc_entreprise = Protection_XSS($_POST['description']);
}
if (verifierPresent('commentaire')) {
	$com_entreprise = Protection_XSS($_POST['commentaire']);
}
if (verifierPresent('idVille')) {
	$idVille_entreprise = intval($_POST['idVille']);
}

/*
 * Appeler la couche du dessous
 */
 
/* int */ $id = Entreprise::UpdateEntreprise(0, $nom_entreprise, $desc_entreprise, $secteur_entreprise, $com_entreprise, $idVille_entreprise);

/*
 * Renvoyer le JSON
 */
if ($id == 0) {
	$json['code'] = 'errorChamp';
}
else if ($id == Entreprise::getErreurExecRequete()) {
	$json['code'] = 'errorBDD';
}
if ($id != NULL) {
	$json['code'] = 'ok';
	$json['id'] = $id;
}
echo json_encode($json);


?>
