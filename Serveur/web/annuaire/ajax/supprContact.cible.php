<?php
/**
 * -----------------------------------------------------------
 * SUPPRCONTACT - CIBLE PHP
 * -----------------------------------------------------------
 * Auteur : Benjamin (Bill) Planche - Aldream (4IF 2011/12)
 *          Contact - benjamin.planche@aldream.net
 * ---------------------
 * Cible pour la suppression d'un contact.
 * Est donc appelée par le moteur JS (Ajax) de la page Annuaire quand un contact est sélectionné.
 * Le principe (repris de Bnj Bouv) est très simple :
 * 1) On récupère l'ensemble des variables qui ont été insérées.
 * 2) On appelle le contrôleur 
 * 3) On renvoit les résultats en JSON
 * Le résultat sera de la forme :
 		{
			code : "ok", // ou "error"
		}
 */

 // Vérification de l'authentification :
require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('commun', 'authentification.class', 'php');
$authentification = new Authentification();
$utilisateur = null;
if ($authentification->isAuthentifie()) {

    /* On récupère l'objet utilisateur associé */
    $utilisateur = $authentification->getUtilisateur();
    if (($utilisateur == null) || ($utilisateur->getPersonne()->getRole() != Personne::ADMIN)) {
        $authentification->forcerDeconnexion();
		inclure_fichier('', '401', 'php');
		die;
    }
}

require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('controleur', 'contact.class', 'php');

/*
 * Récupérer et transformer le JSON
 */
/* int */ $id = 0;
if (verifierPresent('id')) {
	$id = intval($_POST['id']);
}

/*
 * Appeler la couche du dessous
 */
 
/* bool */ $codeRet = Contact::SupprimerContactByID($id);

/*
 * Renvoyer le JSON
 */
$json['code'] = ($codeRet) ? 'ok' : 'error';
// FIXME comment distinguer s'il n'y a pas de résultats ou une erreur ?
echo json_encode($json);


?>
