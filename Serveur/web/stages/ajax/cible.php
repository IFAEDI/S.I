<?php

require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('controleur', 'stages.class', 'php');
inclure_fichier( 'commun', 'authentification.class', 'php' );

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


/* Avant tout, on vérifie que l'on a bien le niveau d'accréditation nécessaire ! */
$authentification = new Authentification();

if( $authentification->isAuthentifie() == false ) {
        die( json_encode( array( 'code' => 'fail', 'mesg' => 'Vous n\'êtes pas authentifié.' ) ) );
}
else if( $authentification->getUtilisateur()->getPersonne()->getRole() != Personne::ETUDIANT &&
	 $authentification->getUtilisateur()->getPersonne()->getRole() != Personne::ADMIN) {
        die( json_encode( array( 'code' => 'critical', 'mesg' => 'Vous n\'êtes pas autorisé à effectuer cette action.' ) ) );
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
$json['code'] = ($resultats != Stages::ERROR) ? 'ok' : 'error';
$json['mesg'] = $resultats;
echo json_encode($json);


?>
