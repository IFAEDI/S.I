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

header( 'Content-Type: application/json' );

 // Vérification de l'authentification :
require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('modele', 'commentaire_entreprise.class', 'php');

$logger = Logger::getLogger("Annuaire.supprCommentaire");

$utilisateur = controlerAuthentificationJSON( $logger, array( Personne::ADMIN, Personne::AEDI ) );
$logger->debug( "\"".$utilisateur->getLogin()."\" a lancé une requête." );

/*
 * Récupérer et transformer le JSON
 */
/* int */ $id = 0;
if (verifierPresent('id')) {
	$id = (int) $_POST['id'];

	/* bool */ $codeRet = CommentaireEntreprise::SupprimerCommentaireByID($id);

	/*
	 * Renvoyer le JSON
	 */
	if ($codeRet === 0) {
		$json = genererReponseStdJSON( 'erreurChamp', 'Veuillez vérifier que tous les champs sont renseignés.' );
	}
	elseif ($codeRet === CommentaireEntreprise::getErreurExecRequete()) {
		$json = genererReponseStdJSON( 'errorBDD', 'Une erreur est survenue lors de l\'enregistrement des données.' );
		$logger->error( 'Une erreur est survenue.' );
	}
	else {
		$json = genererReponseStdJSON( 'ok', 'Commentaire supprimé.' );
		$logger->info( '"'.$utilisateur->getLogin().'" a supprimé le commentaire #'.$id.'.' );
	}
}
else {
	$json = genererReponseStdJSON( 'erreurChamp', 'Veuillez vérifier que tous les champs sont renseignés.' );
}

echo json_encode(array_map('Protection_XSS', $json));

?>
