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

/*
 * TODO Durée non encore prise en compte.
 */
/*
if (verifierPresent('duree')) {
	$duree = $_POST['duree'];
}
 */

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
$json['code'] = ($resultats != NULL) ? 'ok' : 'error';
// FIXME comment distinguer s'il n'y a pas de résultats ou une erreur ?
$json['msg'] = $resultats;
echo json_encode($json);


?>
