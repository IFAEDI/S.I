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

inclure_fichier('modele', 'commentaire_entreprise.class', 'php');

$logger = Logger::getLogger("Annuaire.ajoutCommentaire");

$utilisateur = controlerAuthentificationJSON( $logger, array( Personne::ADMIN, Personne::AEDI ) );
$logger->debug( "\"".$utilisateur->getLogin()."\" a lancé une requête." );

/*
 * Récupérer et transformer le JSON
 */

/* int */ $categorie = 0;
/* string */ $contenu = NULL;
/* int */ $id_entreprise = 0;
/* int */ $id_personneCom = $utilisateur->getPersonne()->getId();

/* int */ $etatVerif = 0;

/* Vérification des champs indispensables */
if (verifierPresent('contenu') && verifierPresent('id_entreprise')) {
	$contenu = $_POST['contenu'];

	$id_entreprise = $_POST['id_entreprise'];

	/* Vérification du champ optionnel */
	if (verifierPresent('categorie')) {
		$categorie = $_POST['categorie'];
	}

	/* int */ $id = CommentaireEntreprise::UpdateCommentaire(0, $id_personneCom, $id_entreprise, $contenu, $categorie, 0);

	if ($id === 0 || $id === CommentaireEntreprise::getErreurChampInconnu()) {
		$json = genererReponseStdJSON( 'erreurChamp', 'Veuillez vérifier que tous les champs sont renseignés.' );
	}
	elseif ($id === CommentaireEntreprise::getErreurExecRequete()) {
		$logger->error( 'Une erreur est survenue.' );
		$json = genererReponseStdJSON( 'errorBDD', 'Une erreur est survenue lors de l\'enregistrement des données.' );
	}
	else {
		$logger->info( '"'.$utilisateur->getLogin().'" a ajouté un commentaire à l\'entreprise #'.$id_entreprise.'.' );
		$json['code'] = 'ok';
		$json['id'] = $id;
	}
}
else {
	$json = genererReponseStdJSON( 'erreurChamp', 'Veuillez vérifier que tous les champs sont renseignés.' );
}

echo json_encode(array_map('Protection_XSS', $json));

?>
