<?php
/**
 * -----------------------------------------------------------
 * AJOUTCOMMENTAIRE - CIBLE PHP
 * -----------------------------------------------------------
 * Auteur : Benjamin (Bill) Planche - Aldream (4IF 2011/12)
 *          Contact - benjamin.planche@aldream.net
 * ---------------------
 * Cible pour l'ajout d'un commentaire.
 * Le principe (repris de Bnj Bouv) est tr�s simple :
 * 1) On r�cup�re l'ensemble des variables qui ont �t� ins�r�es.
 * 2) On appelle le contr�leur 
 * 3) On renvoit les r�sultats en JSON
 * Le r�sultat sera de la forme :
 		{
			code : "ok", // ou "error" - si error, le champ id n'est pas pr�sent
			id : 1 		// ID de du commentaire ajout�
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
    if (($utilisateur == null) || (($utilisateur->getPersonne()->getRole() != Personne::AEDI) && ($utilisateur->getPersonne()->getRole() != Personne::ADMIN))) {
        $authentification->forcerDeconnexion();
		inclure_fichier('', '401', 'php');
		die;
    }
}

require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('controleur', 'commentaire_entreprise.class', 'php');

/*
 * R�cup�rer et transformer le JSON
 */

/* int */ $categorie = 0;
/* string */ $contenu = NULL;
/* int */ $id_entreprise = 0;
/* int */ $id_personneCom = $utilisateur->getPersonne()->getId();

/* int */ $etatVerif = 0;

if (verifierPresent('categorie')) {
	$categorie = Protection_XSS($_POST['categorie']);
}
if (verifierPresent('contenu')) {
	$contenu = Protection_XSS(urldecode($_POST['contenu']));
	$etatVerif++;
}
if (verifierPresent('id_entreprise')) {
	$id_entreprise = Protection_XSS($_POST['id_entreprise']);
	$etatVerif++;
}

if ($etatVerif == 2) {
	/*
	 * Appeler la couche du dessous
	 */
	 
	/* int */ $id = CommentaireEntreprise::UpdateCommentaire(0, $id_personneCom, $id_entreprise, $contenu, $categorie, 0);

	/*
	 * Renvoyer le JSON
	 */
	 if ($id === 0 || $id === CommentaireEntreprise::getErreurChampInconnu()) {
		$json['code'] = 'errorChamp';
	}
	elseif ($id === CommentaireEntreprise::getErreurExecRequete()) {
		$json['code'] = 'errorBDD';
	}
	else {
		$json['code'] = 'ok';
		$json['id'] = $id;
	}
}
else {
	$json['code'] = 'Donnees_manquantes'.$etatVerif;
}
echo json_encode($json);

?>
