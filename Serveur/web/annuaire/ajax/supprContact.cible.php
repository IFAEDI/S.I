<?php
/**
 * -----------------------------------------------------------
 * SUPPRCONTACT - CIBLE PHP
 * -----------------------------------------------------------
 * Auteur : Benjamin (Bill) Planche - Aldream (4IF 2011/12)
 *          Contact - benjamin.planche@aldream.net
 * ---------------------
 * Cible pour la suppression d'un contact.
 * Est donc appel�e par le moteur JS (Ajax) de la page Annuaire quand un contact est s�lectionn�.
 * Le principe (repris de Bnj Bouv) est tr�s simple :
 * 1) On r�cup�re l'ensemble des variables qui ont �t� ins�r�es.
 * 2) On appelle le contr�leur 
 * 3) On renvoit les r�sultats en JSON
 * Le r�sultat sera de la forme :
 		{
			code : "ok", // ou "error"
		}
 */

 // V�rification de l'authentification :
require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('commun', 'authentification.class', 'php');
$authentification = new Authentification();
$utilisateur = null;
if ($authentification->isAuthentifie()) {

    /* On r�cup�re l'objet utilisateur associ� */
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
 * R�cup�rer et transformer le JSON
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
 if ($codeRet === 0) {
	$json['code'] = 'errorChamp';
}
elseif ($codeRet === Contact::getErreurExecRequete()) {
	$json['code'] = 'errorBDD';
}
else {
	$json['code'] = 'ok';

}

// FIXME comment distinguer s'il n'y a pas de r�sultats ou une erreur ?
echo json_encode($json);


?>
