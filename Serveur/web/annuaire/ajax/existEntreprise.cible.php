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
$authentification = new Authentification();
$utilisateur = null;
if ($authentification->isAuthentifie()) {

    /* On récupère l'objet utilisateur associé */
    $utilisateur = $authentification->getUtilisateur();
    if (($utilisateur == null) || (($utilisateur->getPersonne()->getRole() != Personne::AEDI) && ($utilisateur->getPersonne()->getRole() != Personne::ADMIN))) {
        $authentification->forcerDeconnexion();
		inclure_fichier('', '401', 'php');
		die;
    }
}

require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('controleur', 'entreprise.class', 'php');

/*
 * Récupérer et transformer le JSON
 */
/* int */ $nom_entreprise = NULL;

if (verifierPresent('name')) {
	$nom_entreprise = Protection_XSS(urldecode($_POST['name']));
}

/*
 * Appeler la couche du dessous
 */
/* booléen */ $existsName = Entreprise::ExistsName($nom_entreprise);

/*
 * Renvoyer le JSON
 */
$json['answer'] = $existsName;
echo json_encode($json);

?>
