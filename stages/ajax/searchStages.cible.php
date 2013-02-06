<?php

header( 'Content-Type: application/json' );

require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('modele', 'stages.class', 'php');

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

$logger = Logger::getLogger("Stages.searchStages");

/* Avant tout, on vérifie que l'on a bien le niveau d'accréditation nécessaire ! */
$utilisateur = controlerAuthentificationJSON( $logger, array( Personne::ADMIN, Personne::ETUDIANT ) );
$logger->debug( "\"".$utilisateur->getLogin()."\" a lancé une requête." );


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
$resultats = Stages::rechercher($mots_cles, $annee, $duree, $lieu, $entreprise);


/*
 * Renvoyer le JSON
 */
$json = array();
if( $resultats == Stages::ERROR ) {
	$json['code'] = 'error';
	$json = genererReponseStdJSON( 'error', Stages::getLastError() );
}
else {
	$json['code'] = 'ok';
	$json['stages'] = $resultats;
}

echo json_encode($json);


?>
