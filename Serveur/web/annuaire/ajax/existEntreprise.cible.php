<?php
/**
 * -----------------------------------------------------------
 * EXISTENTREPRISE - CIBLE PHP
 * -----------------------------------------------------------
 * Auteur : Benjamin (Bill) Planche - Aldream (4IF 2011/12)
 *          Contact - benjamin.planche@aldream.net
 * ---------------------
 * Cible recevant en entrée un nom d'entreprise et renvoyant un booléen à true si cette entreprise existe en BDD et false sinon.
 */
 
 
 // Vérification de l'authentification :
require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('commun', 'authentification.class', 'php');
inclure_fichier('controleur', 'entreprise.class', 'php');

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
/* int */ $nom_entreprise = NULL;

/* TODO : better check */
if (verifierPresent('name')) {
	$nom_entreprise = Protection_XSS(urldecode($_POST['name']));
}

/*
 * Appeler la couche du dessous
 */
/* booléen */ $existsName = Entreprise::ExistsName($nom_entreprise);

/*
 * Renvoyer le JSON
 * TODO : Normaliser le retour
 */
$json['answer'] = $existsName;
echo json_encode($json);

?>
