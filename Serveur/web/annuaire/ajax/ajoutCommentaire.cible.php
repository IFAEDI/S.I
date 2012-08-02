<?php
/**
 * -----------------------------------------------------------
 * AJOUTCOMMENTAIRE - CIBLE PHP
 * -----------------------------------------------------------
 * Auteur : Benjamin (Bill) Planche - Aldream (4IF 2011/12)
 *          Contact - benjamin.planche@aldream.net
 * ---------------------
 * Cible pour l'ajout d'un commentaire.
 * Le principe (repris de Bnj Bouv) est très simple :
 * 1) On récupère l'ensemble des variables qui ont été insérées.
 * 2) On appelle le contrôleur 
 * 3) On renvoit les résultats en JSON
 * Le résultat sera de la forme :
 		{
			code : "ok", // ou "error" - si error, le champ id n'est pas présent
			id : 1 		// ID de du commentaire ajouté
		}
 */


header('Content-Type: application/json');
 
 // Vérification de l'authentification :
require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('commun', 'authentification.class', 'php');
inclure_fichier('controleur', 'commentaire_entreprise.class', 'php');

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
		/* TODO : améliorer le retour */
		$json['code'] = 'errorChamp';
	}
	elseif ($id === CommentaireEntreprise::getErreurExecRequete()) {
		/* TODO : améliorer le retour */
		$json['code'] = 'errorBDD';
	}
	else {
		$json['code'] = 'ok';
		$json['id'] = $id;
	}
}
else {
	/* TODO : améliorer le retour */
	$json['code'] = 'Donnees_manquantes'.$etatVerif;
}
echo json_encode($json);

?>
