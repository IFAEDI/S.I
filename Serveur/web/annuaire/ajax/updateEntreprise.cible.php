<?php
/**
 * -----------------------------------------------------------
 * UPDATEENTREPRISE - CIBLE PHP
 * -----------------------------------------------------------
 * Auteur : Benjamin (Bill) Planche - Aldream (4IF 2011/12)
 *          Contact - benjamin.planche@aldream.net
 * ---------------------
 * Cible pour l'ajout/modification d'une entreprise.
 * Le principe (repris de Bnj Bouv) est tr�s simple :
 * 1) On r�cup�re l'ensemble des variables qui ont �t� ins�r�es.
 * 2) On appelle le contr�leur 
 * 3) On renvoit les r�sultats en JSON
 * Le r�sultat sera de la forme :
 		{
			code : "ok", // ou "error" - si error, le champ id n'est pas pr�sent
			id : 1 		// ID de l'entreprise ajout�e
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
inclure_fichier('controleur', 'entreprise.class', 'php');

/*
 * R�cup�rer et transformer le JSON
 */
/* string */ $nom_entreprise = NULL;
/* string */ $secteur_entreprise = NULL;
/* string */ $desc_entreprise = NULL;
/* string */ $com_entreprise = NULL;
/* int */ $id_entreprise = 0;

if (verifierPresent('nom')) {
	$nom_entreprise = Protection_XSS(urldecode($_POST['nom']));
}
if (verifierPresent('secteur')) {
	$secteur_entreprise = Protection_XSS(urldecode($_POST['secteur']));
}
if (verifierPresent('description')) {
	$desc_entreprise = Protection_XSS(urldecode($_POST['description']));
}
if (verifierPresent('commentaire')) {
	$com_entreprise = Protection_XSS(urldecode($_POST['commentaire']));
}
if (verifierPresent('id_entreprise')) {
	$id_entreprise = intval($_POST['id_entreprise']);
}

/*
 * Appeler la couche du dessous
 */
 
/* int */ $id = Entreprise::UpdateEntreprise($id_entreprise, $nom_entreprise, $desc_entreprise, $secteur_entreprise, $com_entreprise);

/*
 * Renvoyer le JSON
 */
if ($id === 0) {
	$json['code'] = 'errorChamp';
}
elseif ($id === Entreprise::getErreurExecRequete()) {
	$json['code'] = 'errorBDD';
}
else {
	$json['code'] = 'ok';
	$json['id'] = ($id_entreprise != 0)?0:$id;
}
echo json_encode($json);


?>
