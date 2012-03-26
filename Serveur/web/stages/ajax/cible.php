<?php

require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('stages', 'controleur', 'php');

/**
 * Ce fichier sert de cible à la recherche de stages. C'est celui qui
 * est appelé quand une requête est effectuée depuis le formulaire.
 * Le principe est très simple :
 * 1) On récupère l'ensemble des variables qui ont été insérées.
 * 2) On appelle le contrôleur 
 * 3) On renvoit les résultats en JSON
 *
 * Auteur : benjamin.bouvier@gmail.com (2011/2012)
 */

/**
 * Fonction qui vérifie si un item donné est présent dans le 
 * tableau global $_POST, et s'il est non vide.
 */
function verifierPresent($index) {
	if (!isset($_POST[$index])) {
		return false;
	}

	$sansBlanc = trim($_POST[$index]);
	return !empty($sansBlanc);
}

/*
 * Récupérer et transformer le JSON
 */
$mots_cles = NULL;
$annee = NULL;
$duree = NULL;
$lieu = NULL;
$entreprise = NULL;

if (verifierPresent('mots_cles')) {
	$mots_cles = explode(' ', $_POST['mots_cles']);
}

if (verifierPresent('annee')) {
	$annee = $_POST['annee'];
}

if (verifierPresent('duree')) {
	$duree = $_POST['duree'];
}

if (verifierPresent('lieu')) {
	$lieu = $_POST['lieu'];
}

if (verifierPresent('entreprise')) {
	$entreprise = $_POST['entreprise'];
}

/*
 * Appeler la couche du dessous
 */
$resultats = Stages::rechercher($mots_cles, $annee, $duree, $lieu, 
       			$entreprise);

/*
 * Renvoyer le JSON
 */
echo json_encode($resultats);

?>
