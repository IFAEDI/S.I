<?php
/**
 * -----------------------------------------------------------
 * SUPPRCOMMENTAIRE - CIBLE PHP
 * -----------------------------------------------------------
 * Auteur : Benjamin (Bill) Planche - Aldream (4IF 2011/12)
 *          Contact - benjamin.planche@aldream.net
 * ---------------------
 * Cible pour la suppression d'un comm'.
 * Est donc appelée par le moteur JS (Ajax) de la page Annuaire quand un comm' est sélectionné.
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
inclure_fichier('controleur', 'commentaire_entreprise.class', 'php');

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
 
/* bool */ $codeRet = CommentaireEntreprise::SupprimerCommentaireByID($id);

/*
 * Renvoyer le JSON
 */
 if ($codeRet === 0) {
	$json['code'] = 'errorChamp';
}
elseif ($codeRet === CommentaireEntreprise::getErreurExecRequete()) {
	$json['code'] = 'errorBDD';
}
else {
	$json['code'] = 'ok';
}
echo json_encode($json);


?>
